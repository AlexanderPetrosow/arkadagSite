<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * HTML Project View class for the PortfolioPro component.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewProject extends JViewLegacy {
	protected $item;
	protected $nextItem;
	protected $prevItem;
	protected $params;
	protected $state;

	public function display($tpl = null) {
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models/projects.php');
		$projectsModel	= JModelLegacy::getInstance('Projects', 'AdvPortfolioProModel', array('ignore_request' => false));
		$orderBy 		= $projectsModel->getState('list.ordering');
		$model 			= $this->getModel();
		$value 			= null;

		if (strcmp($orderBy, 'a.created') == 0 || strcmp($orderBy, 'a.created DESC ') == 0) {
			$value = $this->item->created;
		} elseif (strcmp($orderBy, 'a.title') == 0 || strcmp($orderBy, 'a.title DESC') == 0) {
			$value = $this->item->title;
		} elseif (strcmp($orderBy, 'c.lft, a.ordering') == 0) {
			$value = $this->item->ordering;
		} else {
			$value = $this->item->id;
		}

		$this->nextItem = $model->getNextItem($orderBy, $value);
		$this->prevItem = $model->getPrevItem($orderBy, $value, false);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \JViewGenericdataexception(implode("\n", $errors), 500);
		}

		// Create a shortcut for $item.
		$item = &$this->item;

		// Merge project params. If this is single-project view, menu params override project params
		// Otherwise, project params override menu item params
		$this->params	= $this->state->get('params');
		$active			= $app->getMenu()->getActive();
		$temp			= clone $this->params;

		// Check to see which parameters should take priority
		if ($active) {
			$currentLink = $active->link;
			// If the current view is the active item and an project view for this project, then the menu item params take priority
			if (strpos($currentLink, 'view=project') && (strpos($currentLink, '&id=' . (string) $item->id))) {
				// $item->params are the project params, $temp are the menu item params
				// Merge so that the menu item params take priority
				$item->params->merge($temp);
				// Load layout from active query (in case it is an alternative menu item)
				if (isset($active->query['layout'])) {
					$this->setLayout($active->query['layout']);
				}
			} else {
				// Current view is not a single project, so the project params take priority here
				// Merge the menu item params with the project params so that the project params take priority
				$temp->merge($item->params);
				$item->params = $temp;

				// Check for alternative layouts (since we are not in a single-project menu item)
				// Single-project menu item layout takes priority over alt layout for an project
				if ($layout = $item->params->get('project_layout')) {
					$this->setLayout($layout);
				}
			}
		} else {
			// Merge so that project params take priority
			$temp->merge($item->params);
			$item->params = $temp;
			// Check for alternative layouts (since we are not in a single-project menu item)
			// Single-project menu item layout takes priority over alt layout for an project
			if ($layout = $item->params->get('project_layout')) {
				$this->setLayout($layout);
			}
		}

		// Check the view access to the project (the model has already computed the values).
		if ($item->params->get('access-view') != true && (($item->params->get('show_noauth') != true && $user->get('guest')))) {
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}

		// Process description
		$item->text	= $item->description;
		JPluginHelper::importPlugin('content');
		$offset = $this->state->get('list.offset');
		\JFactory::getApplication()->triggerEvent('onContentPrepare', array('com_advportfoliopro.project', &$item, &$item->params, $offset));

		$item->description	= $item->text;

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx'));

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_ADVPORTFOLIO_PROJECTS'));
		}

		$title	= $this->params->get('page_title', '');
		$id		= (int) @$menu->query['id'];

		// if the menu item does not concern this project
		if ($menu && ($menu->query['option'] != 'com_advportfoliopro' || $menu->query['view'] != 'project' || $id != $this->item->id)) {
			// If this is not a single project menu item, set the page title to the project title
			if ($this->item->title) {
				$title = $this->item->title;
			}

			$path = array(array('title' => $this->item->title, 'link' => ''));
		}

		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		if (empty($title)) {
			$title = $this->item->title;
		}

		$this->document->setTitle($title);

		if ($this->item->metadesc) {
			$this->document->setDescription($this->item->metadesc);
		} elseif (!$this->item->metadesc && $this->params->get('menu-meta_description')) {
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey) {
			$this->document->setMetadata('keywords', $this->item->metakey);
		} elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots')) {
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		if ($app->getCfg('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->item->author);
		}

		$mdata = $this->item->metadata->toArray();
		foreach ($mdata as $k => $v) {
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}
	}
}