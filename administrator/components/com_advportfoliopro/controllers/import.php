<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/advportfoliopro.php';

/**
 * Import Controller Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProControllerImport extends JControllerLegacy {

	public function data() {
		JSession::checkToken() or jexit('Invalid Token');
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Import', 'AdvPortfolioProModel', array('ignore_request' => true));

		if ($model->copyCategories()) {
			$model->updateParentId();
			$app->enqueueMessage(JText::_('COM_ADVPORTFOLIOPRO_IMPORT_CATEGORIES_SUCCESS'));
		} else {
			$app->enqueueMessage(JText::_('COM_ADVPORTFOLIOPRO_IMPORT_CATEGORIES_FAIL'), 'error');
		}

		if ($model->copyProjects()) {
			$app->enqueueMessage(JText::_('COM_ADVPORTFOLIOPRO_IMPORT_PROJECTS_SUCCESS'));

			if ($model->copyImages()) {
				$app->enqueueMessage(JText::_('COM_ADVPORTFOLIOPRO_IMPORT_IMAGES_SUCCESS'));
			} else {
				$app->enqueueMessage(JText::_('COM_ADVPORTFOLIOPRO_IMPORT_IMAGES_FAIL'), 'error');
			}
		} else {
			$app->enqueueMessage(JText::_('COM_ADVPORTFOLIOPRO_IMPORT_PROJECTS_FAIL'), 'error');
		}

		$this->setRedirect('index.php?option=com_advportfoliopro&view=projects');
	}

}

