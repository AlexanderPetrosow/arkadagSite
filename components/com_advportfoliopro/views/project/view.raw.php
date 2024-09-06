<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * HTML Project View class for the Portfolio component.
 *
 * @package		Joomla.Site
 * @subpakage	Skyline.Portfolio
 */
class AdvPortfolioProViewProject extends JViewLegacy {
	protected $item;
	protected $params;
	protected $state;

	public function display($tpl = null) {
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \JViewGenericdataexception(implode("\n", $errors), 500);
		}

		$this->setLayout('media');

		parent::display($tpl);
	}
}