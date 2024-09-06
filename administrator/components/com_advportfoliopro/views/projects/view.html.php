<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * View class for a list of Projects.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewProjects extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;
	public $filterForm;
	public $activeFilters;
	protected $sidebar;

	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		AdvPortfolioProHelper::addSubmenu('projects');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		} else {
			// In article associations modal we need to remove language filter if forcing a language.
			// We also need to change the category filter to show show categories with All or the forced language.
			if ($forcedLanguage = JFactory::getApplication()->input->get('forcedLanguage', '', 'CMD')) {
				// If the language is forced we can't allow to select the language, so transform the language selector filter into a hidden field.
				$languageXml = new SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
				$this->filterForm->setField($languageXml, 'filter', true);

				// Also, unset the active language filter so the search tools is not open by default with this filter.
				unset($this->activeFilters['language']);

				// One last changes needed is to change the category filter to just show categories with All language or with the forced language.
				$this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
			}
		}

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {

		$canDo = JHelperContent::getActions('com_advportfoliopro', 'category', $this->state->get('filter.category_id'));
		$user  = JFactory::getUser();


		// Get the toolbar object instance
		$bar = JToolbar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_ADVPORTFOLIOPRO_PROJECTS_MANAGER'), 'picture project');

		if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_advportfoliopro', 'core.create')) > 0) {
			JToolBarHelper::addNew('project.add');
		}

		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('project.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::publish('projects.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('projects.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolBarHelper::archiveList('projects.archive');
			JToolBarHelper::checkin('projects.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('projects.trash');
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_advportfoliopro') && $user->authorise('core.edit', 'com_advportfoliopro') && $user->authorise('core.edit.state', 'com_advportfoliopro')) {
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		// Add a import data button
		if (AdvPortfolioProHelper::checkFreeProjectsTable()) {
			JToolbarHelper::custom('import.data', 'upload', 'upload', 'COM_ADVPORTFOLIOPRO_IMPORT', false);
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_advportfoliopro');
		}

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.state'		=> JText::_('JSTATUS'),
			'a.title'		=> JText::_('JGLOBAL_TITLE'),
			'a.access'		=> JText::_('JGRID_HEADING_ACCESS'),
			'a.language'	=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id'			=> JText::_('JGRID_HEADING_ID')
		);
	}
}