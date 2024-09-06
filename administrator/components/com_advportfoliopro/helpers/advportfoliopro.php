<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * ExtStore Advanced Portfolio Pro Helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProHelper {
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 */
	public static function addSubmenu($vName = '') {
		JHtmlSidebar::addEntry(
			JText::_('COM_ADVPORTFOLIOPRO_SUBMENU_DASHBOARD'),
			'index.php?option=com_advportfoliopro&view=dashboard',
			$vName == 'dashboard'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_ADVPORTFOLIOPRO_SUBMENU_PROJECTS'),
			'index.php?option=com_advportfoliopro&view=projects',
			$vName == 'projects'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_ADVPORTFOLIOPRO_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_advportfoliopro',
			$vName == 'categories'
		);

		if ($vName == 'categories') {
			JToolBarHelper::title(
				JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('COM_ADVPORTFOLIOPRO')),
				'advportfoliopro-categories'
			);
		}
	}

	/**
	 * Returns valid contexts
	 *
	 * @return  array
	 *
	 * @since   3.7.0
	 */
	public static function getContexts() {
		JFactory::getLanguage()->load('com_advportfoliopro', JPATH_ADMINISTRATOR);

		$contexts = array(
			'com_advportfoliopro.project'    => JText::_('COM_ADVPORTFOLIPRO'),
			'com_advportfoliopro.categories' => JText::_('JCATEGORY')
		);

		return $contexts;
	}
	/**
	 * Format file size to display
	 * @param	int	$size
	 * @return	string
	 */
	public static function fileSize($size) {
		if ($size < 1024) {
			return JText::sprintf('%d bytes', $size);
		} else {
			if ($size > 1024 && $size < 1048576) {
				return JText::sprintf('%01.2f KB', $size / 1024.0);
			} else {
				return JText::sprintf('%01.2f MB', $size / 1048576.0);
			}
		}
	}

	/**
	 * Method to get value of images field.
	 *
	 * @params	string	$value	Raw data string.
	 * @return	array	Array of images.
	 */
	public static function getImages($value) {
		$items	= array();

		if (is_array($value)) {
			if (isset($value['image']) && count($value['image'])) {
				for ($i = 0, $n = count($value['image']); $i < $n; $i++) {
					$item			= new stdClass();
					$item->image	= $value['image'][$i];
					$item->title	= $value['title'][$i];

					if ($item->image) {
						$items[]		= $item;
					}
				}
			}
		} else if (is_object($value)) {
			if (isset($value->image) && count($value->image)) {
				for ($i = 0, $n = count($value->image); $i < $n; $i++) {
					$item			= new stdClass();
					$item->image	= $value->image[$i];
					$item->title	= $value->title[$i];

					if ($item->image) {
						$items[]		= $item;
					}
				}
			}
		}

		return $items;
	}

	public static function checkFreeProjectsTable() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('*')->from('INFORMATION_SCHEMA.TABLES')
			->where('TABLE_NAME = ' . $db->quote($db->getPrefix() . 'advportfolio_projects'))
		;

		$db->setQuery($query);

		return $db->loadRow();
	}

}