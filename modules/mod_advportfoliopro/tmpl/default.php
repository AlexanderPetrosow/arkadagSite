<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_advportfoliopro/helpers/advportfoliopro.php';

if ($displayType == 1) {
	require(JModuleHelper::getLayoutPath('mod_advportfoliopro', 'default_slider'));
} else {
	$tags	= AdvPortfolioProHelper::processTags($params, $show_filter);

	if ($params->get('filter_order')) {
		ksort($tags);
	}

	require(JModuleHelper::getLayoutPath('mod_advportfoliopro', 'default_grid'));
}