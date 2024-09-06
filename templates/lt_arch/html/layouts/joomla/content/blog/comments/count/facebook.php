<?php
/**
 * @package Joomla template Framework
 * @author Ltheme https://www.ltheme.com
 * @copyright Copyright (c) Ltheme
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('JPATH_BASE') or die();

if( $displayData['params']->get('comment_facebook_app_id') != '' )
{

	$doc = \JFactory::getDocument();

	if(!defined('HELIX_ULTIMATE_COMMENTS_FACEBOOK_COUNT'))
	{
		$doc->addScript( 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11&appId=' . $displayData['params']->get('comment_facebook_app_id') . '&autoLogAppEvents=1' );
		define('HELIX_ULTIMATE_COMMENTS_FACEBOOK_COUNT', 1);
	}
	?>

	<a href="<?php echo $displayData['url']; ?>#comments">
		<?php echo JText::_('HELIX_ULTIMATE_COMMENTS'); ?> (<span class="fb-comments-count" data-href="<?php echo $displayData['url']; ?>"></span>)
	</a>

	<?php
}