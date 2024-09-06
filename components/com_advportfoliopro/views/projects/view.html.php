<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * View class for a list of projects.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewProjects extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		$app	= JFactory::getApplication();

		// Get some data from the models
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->params		= $this->state->params;

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \JViewGenericdataexception(implode("\n", $errors), 500);
		}

		// Compute all tags of list projects
		$tags			= array();
		$show_filter	= $this->params->get('show_filter', 1);

//		foreach ($this->items as $item) {
//			if ($show_filter == 1) {
//				// filter by tag
//				$tags	= array_merge($tags, $item->tags);
//			} else if ($show_filter != 0) {
//				// filter by category
//				$tags	= array_merge($tags, array($item->category_title));
//			}
//		}

		$tags	= AdvPortfolioProHelper::processTags($this->params, $show_filter);

		if ($this->params->get('filter_order')) {
			ksort($tags);
		}

		$this->tags				= $tags;
		$this->pageclass_sfx	= htmlspecialchars($this->params->get('pageclass_sfx'));

		// Check for layout override only if this is not the active menu item
		// If it is the active menu item, then the view and category id will match
		$active	= $app->getMenu()->getActive();

		if ((!$active) || (strpos($active->link, 'view=projects') === false)) {
			if ($layout	= $this->params->get('projects_layout')) {
				$this->setLayout($layout);
			}
		} else if (isset($active->query['layout'])) {
			// We need to set the layout in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document.
	 */
	protected function _prepareDocument() {
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;

		// Because the application sets the default page title,
		// we need to get it from the menu item itself
		$menu		= $menus->getActive();

		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_ADVPORTFOLIOPRO_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title)) {
			$title = $app->get('sitename');
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description')) {
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots')) {
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}