<?php
/**
 * @package Joomla template Framework
 * @author Ltheme https://www.ltheme.com
 * @copyright Copyright (c) Ltheme
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined('JPATH_BASE') or die;

JHtml::_('behavior.core');

if (preg_match('/Joomla.submitbutton/', $displayData['doTask']))
{
	$ctrls = str_replace("Joomla.submitbutton('", '', $displayData['doTask']);
	$ctrls = str_replace("')", '', $ctrls);
	$ctrls = str_replace(";", '', $ctrls);

	$options = array('task' => $ctrls);
	JFactory::getDocument()->addScriptOptions('keySave', $options);
}

$id       = isset($displayData['id']) ? $displayData['id'] : '';
$doTask   = $displayData['doTask'];
$class    = $displayData['class'];
$text     = $displayData['text'];
$btnClass = $displayData['btnClass'];
?>
<button<?php echo $id; ?> onclick="<?php echo $doTask; ?>" class="<?php echo $btnClass; ?>">
	<span class="<?php echo trim($class); ?>"></span>
	<?php echo $text; ?>
</button>
