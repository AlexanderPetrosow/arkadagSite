<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * HTML View class for the AdvPortfolioPro component
 *
 * @since  1.5
 */
class AdvPortfolioProViewCategory extends JViewCategory {
	protected $items;
	protected $pagination;
	protected $state;
	protected $category;
	protected $children;
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null) {
		parent::commonCategoryDisplay();

		// Get some data from the models
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$category	= $this->get('Category');
		$children	= $this->get('Children');
		$parent 	= $this->get('Parent');
		$pagination	= $this->get('Pagination');
		$params		= $state->params;

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \JViewGenericdataexception(implode("\n", $errors), 500);
		}

		$this->state			= &$state;
		$this->items			= &$items;
		$this->category  		= &$category;
		$this->children  		= &$children;
		$this->parent     		= &$parent;
		$this->pagination		= &$pagination;
		$this->params			= &$params;
		$this->pageclass_sfx	= htmlspecialchars($params->get('pageclass_sfx'));

		// Compute all tags of list projects
		$tags			= array();
		$show_filter	= $this->params->get('show_filter', 1);

		$tags	= AdvPortfolioProHelper::processTags($this->items, $show_filter);

		if ($this->params->get('filter_order')) {
			ksort($tags);
		}

		$this->tags				= $tags;

		// Check for layout override only if this is not the active menu item
		// If it is the active menu item, then the view and category id will match
		$app     = JFactory::getApplication();
		$active  = $app->getMenu()->getActive();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();
		$title   = null;

		if ((!$active) || ((strpos($active->link, 'view=category') === false) || (strpos($active->link, '&id=' . (string) $this->category->id) === false))) {
			// Get the layout from the merged category params
			if ($layout = $this->category->params->get('category_layout')) {
				$this->setLayout($layout);
			}
		} elseif (isset($active->query['layout'])) {
			// We need to set the layout from the query in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}


		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu && $menu->component == 'com_advportfoliopro' && isset($menu->query['view'], $menu->query['id']) && $menu->query['view'] == 'category' && $menu->query['id'] == $this->category->id) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
			$title = $this->params->get('page_title', $menu->title);
		} else {
			$this->params->def('page_heading', $this->category->title);
			$title = $this->category->title;
			$this->params->set('page_title', $title);
		}

		$id = (int) @$menu->query['id'];

		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->get('sitename');
		} elseif ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		} elseif ($app->get('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		if (empty($title)) {
			$title = $this->category->title;
		}

		$this->document->setTitle($title);

		if ($this->category->metadesc) {
			$this->document->setDescription($this->category->metadesc);
		} elseif ($this->params->get('menu-meta_description')) {
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->category->metakey) {
			$this->document->setMetadata('keywords', $this->category->metakey);
		} elseif ($this->params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots')) {
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		if (!is_object($this->category->metadata)) {
			$this->category->metadata = new Registry($this->category->metadata);
		}

		if (($app->get('MetaAuthor') == '1') && $this->category->get('author', '')) {
			$this->document->setMetaData('author', $this->category->get('author', ''));
		}

		$mdata = $this->category->metadata->toArray();

		foreach ($mdata as $k => $v) {
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function prepareDocument() {
		parent::prepareDocument();
		$menu = $this->menu;
		$id = (int) @$menu->query['id'];

		if ($menu && ($menu->query['option'] !== 'com_advportfoliopro' || $menu->query['view'] === 'article' || $id != $this->category->id)) {
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] !== 'com_advportfoliopro' || $menu->query['view'] === 'article' || $id != $category->id) && $category->id > 1) {
				$path[] = array('title' => $category->title, 'link' => ContentHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item) {
				$this->pathway->addItem($item['title'], $item['link']);
			}
		}

		parent::addFeed();
	}
}
