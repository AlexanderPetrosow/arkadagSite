<?php
/**
 * @package Joomla template Framework
 * @author Ltheme https://www.ltheme.com
 * @copyright Copyright (c) Ltheme
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined('JPATH_BASE') or die;

$btnClass = $displayData['class'];
?>
<button
	type="button"
	class="btn btn-sm <?php echo $btnClass; ?> dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
	aria-haspopup="true"
	aria-expanded="false"></button>
<div class="dropdown-menu">
