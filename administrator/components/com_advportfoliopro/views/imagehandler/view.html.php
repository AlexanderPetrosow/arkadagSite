<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of images.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewImageHandler extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		$app				= JFactory::getApplication();
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->folders		= $this->get('folders');
		$this->folder		= $this->state->get('folder');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->image_id		= $app->input->getCmd('image_id');
		$this->require_ftp	= !JClientHelper::hasCredentials('ftp');

		parent::display($tpl);
	}

	function setImage($index = 0) {
		if (isset($this->items[$index])) {
			$this->_tmp_img = &$this->items[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}
}