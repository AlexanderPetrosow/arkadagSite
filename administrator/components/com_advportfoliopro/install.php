<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * ExtStore Advanced Portfolio Pro Installer Script.
 *
 * @package		Joomla.Install
 * @subpackage	ExtStore.AdvPortfolioPro
 */
class Com_AdvPortfolioProInstallerScript {

	protected function _install($parent, $update = false) {
		// add tag type
		/** @var JTableContentType $table */
		$table	= JTable::getInstance('contenttype');

		if ($table) {
			$table->load(array('type_alias' => 'com_advportfoliopro.project'));

			if (!$table->type_id) {
				$data	= array(
					'type_title'		=> 'ExtStore Advanced Portfolio Pro Project',
					'type_alias'		=> 'com_advportfoliopro.project',
					'table'				=> '{"special":{"dbtable":"#__advportfoliopro_projects","key":"id","type":"Project","prefix":"AdvPortfolioProTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}',
					'rules'				=> '',
					'field_mappings'	=> '{"common":[{"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"null","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"attribs", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"link", "core_version":"null", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"null", "asset_id":"null"}], "special": {}}',
					'router'			=> 'AdvPortfolioProHelperRoute::getProjectRoute',
				);

				$table->bind($data);

				if ($table->check()) {
					$table->store();
				}
			}
		}

		// install module & plugin
		$manifest		= $parent->getManifest();
		$parent			= $parent->getParent();
		$source			= $parent->getPath("source");
		$module_attr	= $manifest->modules->attributes();
		$module_path	= isset($module_attr['folder']) ? '/' . $module_attr['folder'] : '';
		$plugin_attr	= $manifest->plugins->attributes();
		$plugin_path	= isset($plugin_attr['folder']) ? '/' . $plugin_attr['folder'] : '';

		$installer	= new JInstaller();

		// Install modules.
		foreach ($manifest->modules->module as $module) {
			$attributes	= $module->attributes();
			$path		= $source . $module_path . '/' . $attributes['name'];
			$installer->install($path);
		}

		// Install plugins.
		$plugins	= array();
		$db			= JFactory::getDbo();

		foreach ($manifest->plugins->plugin as $plugin) {
			$attributes	= $plugin->attributes();
			$path		= $source . $plugin_path . '/' . $attributes['group'] . '/' . $attributes['name'];

			if ($attributes['enable']) {
				$plugins[]	= $db->quote($attributes['name']);
			}

			$installer->install($path);
		}

		// Public plugins.
		if (!$update && count($plugins)) {
			$query	= 'UPDATE #__extensions'
					. ' SET enabled = 1'
					. ' WHERE element IN (' . implode(', ', $plugins) . ') AND type = \'plugin\' AND enabled = 0'
			;
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Install.
	 *
	 * @param	$parent
	 */
	public function install($parent) {
		$this->_install($parent);
	}

	/**
	 * Update.
	 *
	 * @param	$parent
	 */
	public function update($parent) {
		$this->_install($parent, true);
	}
}