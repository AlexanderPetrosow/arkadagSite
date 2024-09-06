<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.tabstate');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_advportfoliopro')) {
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Include CSS and JS
JHtml::_('script', 'com_advportfoliopro/admin.script.js', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'com_advportfoliopro/admin.style.css', array('version' => 'auto', 'relative' => true));


// Include dependancies
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JLoader::register('AdvPortfolioProHelper', __DIR__ . '/helpers/advportfoliopro.php');
JLoader::register('AdvPortfolioProFactory', __DIR__ . '/helpers/factory.php');
JLoader::register('AdvPortfolioProImageLib', __DIR__ . '/helpers/imagelib.php');



$controller	= JControllerLegacy::getInstance('AdvPortfolioPro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();