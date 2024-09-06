<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

$this->document->addScriptDeclaration("
(function($) {
	$(document).ready(function() {
		var player	= $('#portfolio_player');
		player.attr('width', player.width());
		player.attr('height', Math.round(player.width() * 10 / 16));
	});
})(jQuery);
");

require JPATH_ADMINISTRATOR . '/components/com_advportfoliopro/helpers/video.php';

echo '<iframe id="portfolio_player" src="' . AdvPortfolioProVideoHelper::getEmbed($this->item->video_link) . '" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';