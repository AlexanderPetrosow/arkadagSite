<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

if ($this->item->video_link) {
	require JPATH_ADMINISTRATOR . '/components/com_advportfoliopro/helpers/video.php';

	$href			= AdvPortfolioProVideoHelper::getEmbed($this->item->video_link);

	$video			= new stdClass();
	$video->src	= $href;
	$video->type	= 'iframe';

	echo json_encode($video);
}