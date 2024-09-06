<?php
/**
 * @package Joomla template Framework
 * @author Ltheme https://www.ltheme.com
 * @copyright Copyright (c) Ltheme
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('JPATH_BASE') or die();

extract($displayData);
?>

<?php if(isset($attribs->helix_ultimate_audio) && $attribs->helix_ultimate_audio) : ?>
	<div class="article-featured-audio">
		<div class="embed-responsive embed-responsive-16by9">
			<?php echo $attribs->helix_ultimate_audio; ?>
		</div>
	</div>
<?php endif; ?>