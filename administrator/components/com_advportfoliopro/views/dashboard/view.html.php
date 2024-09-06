<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Dashboard view.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewDashboard extends JViewLegacy {
	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		AdvPortfolioProHelper::addSubmenu('dashboard');

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {
		JToolBarHelper::title(JText::_('COM_ADVPORTFOLIOPRO_DASHBOARD_MANAGER'), 'dashboard.png');

		$canDo = JHelperContent::getActions('com_advportfoliopro');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_advportfoliopro');
		}
	}

	/**
	 * Display quick icon button.
	 * 
	 * @param	string	$link
	 * @param	string	$image
	 * @param	string	$text
	 */
	protected function _quickIcon($link, $image, $text) {
		$button	= array(
			'link'	=> JRoute::_($link),
			'image'	=> 'com_advportfoliopro/' . $image,
			'text'	=> JText::_($text)
		);

		$this->button	= $button;
		echo $this->loadTemplate('button');
	}
}