<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Advanced Portfolio Pro Component Route Helper.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
abstract class AdvPortfolioProHelperRoute {
	protected static $lookup = array();

	/**
	 * @param    int    The route of the project item
	 */
	public static function getProjectRoute($id, $catid = 0, $language = 0) {
		// Create the link
		$link = 'index.php?option=com_advportfoliopro&view=project&id=' . $id;

		if ((int) $catid > 1)
		{
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && JLanguageMultilang::isEnabled())
		{
			$link .= '&lang=' . $language;
		}


		return $link;
	}


	public static function getCategoryRoute($catid, $language = 0) {
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;
		}
		else
		{
			$id = (int) $catid;
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			$link = 'index.php?option=com_advportfoliopro&view=category&id=' . $id;

			if ($language && $language !== '*' && JLanguageMultilang::isEnabled())
			{
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}


	protected static function _findItem($needles = null) {
		$app = JFactory::getApplication();
		$menus = $app->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language])) {
			self::$lookup[$language] = array();

			$component = JComponentHelper::getComponent('com_advportfoliopro');

			$attributes = array('component_id');
			$values = array($component->id);

			if ($language != '*') {
				$attributes[] = 'language';
				$values[] = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item) {
				if (isset($item->query) && isset($item->query['view'])) {
					$view = $item->query['view'];
					if (!isset(self::$lookup[$language][$view])) {
						self::$lookup[$language][$view] = array();
					}
					if (isset($item->query['id'])) {

						// here it will become a bit tricky
						// language != * can override existing entries
						// language == * cannot override existing entries
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*') {
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles) {
			foreach ($needles as $view => $ids) {
				if (isset(self::$lookup[$language][$view])) {
					foreach ($ids as $id) {
						if (isset(self::$lookup[$language][$view][(int)$id])) {
							return self::$lookup[$language][$view][(int)$id];
						}
					}
				}
			}
		}

		// Check if the active menuitem matches the requested language
		$active = $menus->getActive();
		if ($active && $active->component == 'com_advportfoliopro' && ($active->language == '*' || !JLanguageMultilang::isEnabled())) {
			return $active->id;
		}

		$itemIds = $menus->getItems('component_id', array(JComponentHelper::getComponent('com_advportfoliopro')->id));

		return !empty($itemIds[0]->id) ? $itemIds[0]->id : null;
	}
}
