<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * ExtStore AdvPortfolioPro Factory Class.
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProFactory {
	/**
	 * Get credits footer string.
	 * @return	string
	 */
	public static function getFooter() {
		return '<p class="xcopyright"><span class="xtitle">Advanced Portfolio Pro by ExtStore - Version ' . self::getVersion() . '</span> Copyright &copy; 2013 by <strong>Skyline Technology Ltd - <a href="http://extstore.com" target="_blank">http://extstore.com</a></strong></p>';
	}

	/**
	 * Get current version of component.
	 */
	public static function getVersion() {
		$table		= JTable::getInstance('Extension');
		$table->load(array('name' => 'com_advportfoliopro'));
		$registry	= new JRegistry($table->manifest_cache);

		return $registry->get('version');
	}

	/**
	 * Get model of component
	 */
	public static function getModel($type, $config = array()) {
		return JModelLegacy::getInstance($type, 'AdvPortfolioProModel', $config);
	}
}