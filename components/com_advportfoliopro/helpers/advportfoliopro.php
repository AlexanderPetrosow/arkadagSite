<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Advanced Portfolio Helper.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProHelper {

	/**
	 * Get model of component
	 */
	public static function getModel($type, $config = array()) {
		return JModelLegacy::getInstance($type, 'AdvPortfolioProModel', $config);
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

	public static function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
	}

	public static function processTags($params, $type) {
		if ($type == 0) {
			return array();
		}

		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_advportfoliopro/models');

		// Get an instance of the project model
		$model = JModelLegacy::getInstance('Projects', 'AdvPortfolioproModel', array('ignore_request' => true));

		// Set application parameters in model
		$app		= JFactory::getApplication();
		$appParams	= $app->getParams();
		$model->setState('params', $appParams);

		$model->setState('list.select', 'a.*');

		$model->setState('filter.state', 1);
		$model->setState('filter.access', 1);

		$catids	= $params->get('catids', array());

		$model->setState('filter.category_id', $catids);

		$model->setState('list.start', 0);
		$model->setState('list.limit', 0);


		// Filter by language
		$model->setState('filter.language',$app->getLanguageFilter());

		$data = $model->getItems();

		$displayType = $params->get('display_type', 1);

		if ($displayType == 0) {
			foreach ($data as &$item) {
				$item->tags	= array();
				$tags 		= new JHelperTags();
				$tags		= $tags->getItemTags('com_advportfoliopro.project', $item->id);

				foreach ($tags as $tag) {
					$tmp			= new stdClass();
					$tmp->title		= $tag->title;
					$tmp->alias		= $tag->alias;
					$tmp->ordering	= $tag->lft;
					$item->tags[]	= $tmp;
				}
			}
		}

		$items = $data;

		$tags		= array();
		$objects	= array();

		foreach ($items as $item) {
			if ($type == 1) {	// tag
				$objects		= array_merge($objects, $item->tags);
			} else {			// category
				$tmp			= new stdClass();
				$tmp->title		= $item->category_title;
				$tmp->alias		= $item->category_alias;
				$tmp->ordering	= $item->category_ordering;

				$objects[]		= $tmp;
			}
		}

		//
		$n		= count($objects);
		for ($i = 0; $i < $n - 1; $i++) {
			for ($j = $i + 1; $j < $n; $j++) {
				if ($objects[$i]->ordering > $objects[$j]->ordering) {
					$tmp			= $objects[$i];
					$objects[$i]	= $objects[$j];
					$objects[$j]	= $tmp;
				}
			}
		}

		foreach ($objects as $object) {
			$tags[$object->alias]	= $object->title;
		}

		return array_unique($tags);
	}
}