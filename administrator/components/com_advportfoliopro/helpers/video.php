<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * ExtStore AdvPortfolioPro Video Helper Class.
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProVideoHelper {
	const	YOUTUBE_REGEX	= '/(youtube\.com|youtu\.be|youtube-nocookie\.com)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i';
	const	VIMEO_REGEX		= '/(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/';
	const	METACAFE_REGEX	= '/metacafe.com\/(?:watch|fplayer)\/([\w\-]{1,10})/';
	const	DAILY_MOTION	= '/dailymotion.com\/video\/(.*)\/?(.*)/';
	const	TWITVID			= '/twitvid\.com\/([a-zA-Z0-9_\-\?\=]+)/i';

	public static function check($link) {
		return preg_match(self::YOUTUBE_REGEX, $link)
			|| preg_match(self::VIMEO_REGEX, $link)
			|| preg_match(self::METACAFE_REGEX, $link)
			|| preg_match(self::DAILY_MOTION, $link)
			|| preg_match(self::TWITVID, $link)
		;
	}

	public static function getEmbed($link) {
		if (preg_match(self::YOUTUBE_REGEX, $link, $match)) {
			return 'https://www.youtube.com/embed/' . $match[3];
		} else if (preg_match(self::VIMEO_REGEX, $link, $match)) {
			return 'https://player.vimeo.com/video/' . $match[1];
		} else if (preg_match(self::METACAFE_REGEX, $link, $match)) {
			return 'http://www.metacafe.com/fplayer/' . $match[1] . '.swf';
		} else if (preg_match(self::DAILY_MOTION, $link, $match)) {
			return 'https://www.dailymotion.com/swf/video/' . $match[1];
		} else if (preg_match(self::TWITVID, $link, $match)) {
			return 'http://www.twitvid.com/embed.php?guid=' . $match[1];
		}

		return '';
	}
}