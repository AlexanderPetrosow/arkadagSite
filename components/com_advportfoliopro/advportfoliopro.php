<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

// Include dependancies
require_once __DIR__ . '/helpers/advportfoliopro.php';
require_once __DIR__ . '/helpers/query.php';
JLoader::register('AdvPortfolioProHelperRoute', JPATH_COMPONENT . '/helpers/route.php');


JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/html');

JPluginHelper::importPlugin('advportfoliopro');
$controller	= JControllerLegacy::getInstance('AdvPortfolioPro');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();