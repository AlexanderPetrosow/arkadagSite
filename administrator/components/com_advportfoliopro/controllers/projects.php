<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;


/**
 * Project List Controller Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProControllerProjects extends JControllerAdmin {
	/** @var string		The prefix to use with controller messages. */
	protected $text_prefix	= 'COM_ADVPORTFOLIOPRO_PROJECTS';

	/**
	 * Constructor.
	 */
	public function __construct($config = array()) {
		parent::__construct($config);

		$this->registerTask('unfeatured',	'featured');
	}

	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Project', $prefix = 'AdvPortfolioProModel', $config = array('ignore_request' => true)) {
		$model	= parent::getModel($name, $prefix, $config);
		return $model;
	}


	/**
	 * Method to toggle the featured setting of a list of projects.
	 */
	public function featured() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user	= JFactory::getUser();
		$ids	= $this->input->get('cid', array(), 'array');
		$values	= array('featured' => 1, 'unfeatured' => 0);
		$task	= $this->getTask();
		$value	= ArrayHelper::getValue($values, $task, 0, 'int');

		// Get the model.
		/** @var AdvPortfolioProModelProject $model */
		$model  = $this->getModel();

		// Access checks.
		foreach ($ids as $i => $id) {
			$item = $model->getItem($id);

			if (!JFactory::getUser()->authorise('core.edit.state', 'com_advportfoliopro.category.' . (int) $item->catid)) {
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
		}

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('COM_ADVPORTFOLIOPRO_NO_ITEM_SELECTED'));
		} else {
			// Publish the items.
			if (!$model->featured($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			}

			if ($value == 1) {
				$message = JText::plural('COM_ADVPORTFOLIOPRO_N_ITEMS_FEATURED', count($ids));
			} else {
				$message = JText::plural('COM_ADVPORTFOLIOPRO_N_ITEMS_UNFEATURED', count($ids));
			}
		}

		$this->setRedirect('index.php?option=com_advportfoliopro&view=projects', $message);
	}
}