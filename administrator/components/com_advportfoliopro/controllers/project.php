<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Project Controller Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProControllerProject extends JControllerForm {

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param	array	$data	An array of input data.
	 * @return	bool
	 */
	protected function allowAdd($data = array()) {
		// Initialize variables.
		$user		= JFactory::getUser();
		$categoryId	= ArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow		= null;

		if ($categoryId) {
			// If the category has been passed in URL check it.
			$allow	= $user->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 * @return	bool
	 */
	protected function allowEdit($data = array(), $key = 'id') {
		// Initialize variables.
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId	= 0;

		if ($recordId) {
			$categoryId	= (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId) {
			// The Category has been set. Check the Category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);
		} else {
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}

	/**
	 * Method to run batch operations.
	 *
	 * @return	void
	 */
	public function batch($model = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model	= $this->getModel('Project', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_advportfoliopro&view=projects' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}