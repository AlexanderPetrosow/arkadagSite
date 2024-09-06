<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

// include the syndicate functions only once
require_once(dirname(__FILE__) . '/helper.php');

$language = JFactory::getLanguage();
$language->load('com_advportfoliopro', JPATH_BASE, 'en-GB');

$id					= $module->id;
$items				= modAdvPortfolioProHelper::getList($params);
$moduleclass_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));

$displayType            = $params->get('display_type', 1);
$show_filter            = $params->get('show_filter', 1);
$show_filter_all	 	= $params->get('show_filter_all', 1);
$overlay_effect         = $params->get('overlay_effect', 'none');
$hover_easing 			= $params->get('hover_easing', 'ease');
$hoverdir_speed 		= $params->get('hoverdir_speed', 300);
$hover_delay 			= $params->get('hover_delay', 0);
$hover_delayCss	 		= $hover_delay . 's';
$hoverdir_inverse 		= $params->get('hoverdir_inverse', false);
$hover_duration			= $params->get('hover_duration', 0.45) . 's';
$bgInfoIcon		 		= $params->get('bg_icon', '#b1b1b1');
$bgInfoHoverIcon	 	= $params->get('bg_icon_hover', '');

$bgFilterSelected	= $params->get('bg_filter_active', '#2da0ce');
$bgFilterHover	 	= $params->get('bg_filter_hover', '#aaaaaa');

$scale				 = $params->get('scale', 0);
$translate			 = $params->get('translate', 0);
$rotatez			 = $params->get('rotatez', 0);
$rotatex			 = $params->get('rotatex', 0);
$rotatey			 = $params->get('rotatey', 0);
$skew				 = $params->get('skew', 0);

$scale_x			 = $params->get('scale_x', '');
$scale_y			 = $params->get('scale_y', '');
$translate_x		 = $params->get('translate_x', '');
$translate_y		 = $params->get('translate_y', '');
$rotate_angle_z		 = $params->get('rotate_angle_z', 20);
$rotate_angle_x		 = $params->get('rotate_angle_x', 20);
$rotate_angle_y		 = $params->get('rotate_angle_y', 20);
$skew_angle_x		 = $params->get('skew_angle_x', '');
$skew_angle_y		 = $params->get('skew_angle_y', '');

$projecturl	 		 = $params->get('link_of_project', 1);

$link_target_blank	 = $params->get('link_to_target_blank', 0);
$str_target			 = '';
if ($link_target_blank == 1) {
	$str_target	= 'target=_blank';
}

require(JModuleHelper::getLayoutPath('mod_advportfoliopro', 'default'));

