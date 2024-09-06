<?php
/**
 * @copyright	Copyright (c) 2015 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * System - Extensions Update Helper Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	ExtStore.extstore_update_helper
 */
class plgSystemExtStore_Update_Helper extends JPlugin {

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);
	}

	/**
	 * onInstallerBeforePackageDownload hook.
	 */
	public function onInstallerBeforePackageDownload(&$url, &$headers) {
		$uri = JUri::getInstance($url);

		// I don't care about download URLs not coming from our site
		$host = $uri->getHost();
		if ($host != 'extstore.com') {
				return true;
		}

		// Get the download ID
		$dlid = $this->params->get('dlid', '');

		// If the download ID is invalid, return without any further action
		if (!preg_match('/^([0-9]{1,}:)?[0-9a-f]{32}$/i', $dlid)) {
				return true;
		}

		// Append the Download ID to the download URL
		if (!empty($dlid)) {
				$uri->setVar('dlid', $dlid);
				$url = $uri->toString();
		}

		return true;
	}
}