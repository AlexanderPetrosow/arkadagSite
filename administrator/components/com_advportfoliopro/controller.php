<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * ExtStore Advanced Portfolio Pro Controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProController extends JControllerLegacy {
	protected $default_view	= 'dashboard';

	/**
	 * Method to display a view.
	 *
	 * @param	bool 				$cachable	If true, the view output will be cached.
	 * @param	bool 				$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFileterInput::clean()}.
	 * @return	JControllerLegacy	This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false) {

		JLoader::register('AdvPortfolioProHelper', JPATH_ADMINISTRATOR . '/components/com_advportfoliopro/helpers/advportfoliopro.php');

		$view		= $this->input->get('view', 'dashboard');
		$this->input->set('view', $view);
		$layout		= $this->input->get('layout', 'default');
		$id			= $this->input->getInt('id');

		// Check for edit form.
		if ($layout == 'edit') {
			switch ($view) {
				case 'project':
					$vName	= 'projects';
					break;
			}

			if (!$this->checkEditId('com_advportfoliopro.edit.' . $view, $id)) {
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect(JRoute::_('index.php?option=com_advportfoliopro&view=' . $vName, false));

				return false;
			}
		}

		return parent::display();
	}
}