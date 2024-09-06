<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

class JHtmlAdvPortfolioPro {
	protected static $loaded;

	/**
	 * Method to load fancybox.
	 */
	public static function modal() {
		if (isset(self::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'com_advportfoliopro/jquery.fancybox.js', array('version' => 'auto', 'relative' => true));
		JHtml::_('stylesheet', 'com_advportfoliopro/jquery.fancybox.css', array('version' => 'auto', 'relative' => true));

		$document	= JFactory::getDocument();
		$document->addScriptDeclaration("
			(function($) {
				$('.exmodal').fancybox();
			})(jQuery);
		");

		self::$loaded[__METHOD__]	= true;
	}


	/**
	 * Method to get image.
	 */
	public static function image($image, $width = null, $height = null, $alt = '', $singleQuote = false, $attributes = '') {
		require_once JPATH_ADMINISTRATOR . '/components/com_advportfoliopro/helpers/imagelib.php';

		$config			= JComponentHelper::getParams('com_advportfoliopro');
		$cacheURL		= str_replace('{root}', JUri::root(true), $config->get('image_cache_url', '{root}/cache/advportfoliopro'));

		// build cache file.
		$cache	= AdvPortfolioProImageLib::process($image, $width, $height, $config);

		if (!$cache) {
			return false;
		}

		$cacheImage	= $cacheURL . '/' . $cache;

		if (!$alt) {
			return $cacheImage;
		}

		$img	= '<img src="' . $cacheImage . '" alt="' . $alt . '" ' . $attributes . ' />';

		if ($singleQuote) {
			str_replace('"', '\'', $img);
		}

		return $img;
	}

	/**
	 * @param   int $value	The state value
	 * @param   int $i
	 */
	public static function projectFeatured($value = 0, $i, $canChange = true) {
		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states	= array(
			0	=> array('star-empty',	'projects.featured',	'COM_ADVPORTFOLIOPRO_PROJECTS_UNFEATURED',	'COM_ADVPORTFOLIOPRO_PROJECTS_TOGGLE_TO_FEATURE'),
			1	=> array('star',		'projects.unfeatured',	'COM_ADVPORTFOLIOPRO_PROJECTS_FEATURED',	'COM_ADVPORTFOLIOPRO_PROJECTS_TOGGLE_TO_UNFEATURE'),
		);

		$state	= ArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];

		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JText::_($state[3]) . '"><i class="icon-' . $icon.'"></i></a>';
		} else {
			$html	= '';
		}

		return $html;
	}

}