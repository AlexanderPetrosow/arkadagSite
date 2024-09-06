<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a Project.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewProject extends JViewLegacy {

	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
		}

		// If we are forcing a language in modal (used for associations).
		if ($this->getLayout() === 'modal' && $forcedLanguage = JFactory::getApplication()->input->get('forcedLanguage', '', 'cmd')) {
			// Set the language field to the forcedLanguage and disable changing it.
			$this->form->setValue('language', null, $forcedLanguage);
			$this->form->setFieldAttribute('language', 'readonly', 'true');

			// Only allow to select categories with All language or with the forced language.
			$this->form->setFieldAttribute('catid', 'language', '*,' . $forcedLanguage);

			// Only allow to select tags with All language or with the forced language.
			$this->form->setFieldAttribute('tags', 'language', '*,' . $forcedLanguage);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId     = $user->id;
		$isNew		= $this->item->id == 0;
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo 		= JHelperContent::getActions('com_advportfoliopro', 'category', $this->item->catid);

		JToolbarHelper::title($isNew ? JText::_('COM_ADVPORTFOLIOPRO_MANAGER_ADVPORTFOLIOPRO_NEW') : JText::_('COM_ADVPORTFOLIOPRO_MANAGER_ADVPORTFOLIOPRO_EDIT'), 'picture project');

		// Build the actions for new and existing records.
		if ($isNew && (count($user->getAuthorisedCategories('com_advportfoliopro', 'core.create')) > 0)) {

			JToolbarHelper::apply('project.apply');
			JToolbarHelper::save('project.save');
			JToolbarHelper::save2new('project.save2new');
			JToolbarHelper::cancel('project.cancel');

		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable) {
				JToolbarHelper::apply('project.apply');
				JToolbarHelper::save('project.save');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					JToolbarHelper::save2new('project.save2new');
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolbarHelper::save2copy('project.save2copy');
			}

			if (JComponentHelper::isEnabled('com_contenthistory') && $this->state->params->get('save_history', 0) && $itemEditable) {
				JToolbarHelper::versions('com_advportfoliopro.project', $this->item->id);
			}

			JToolbarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_ADVPORTFOLIOPRO_PROJECTS_EDIT');
	}
}