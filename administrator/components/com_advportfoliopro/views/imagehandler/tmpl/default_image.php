<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

?>

<li class="width-110 intro-item">
	<div align="center" class="imageborder">
		<a title="<?php echo $this->_tmp_img->name; ?>" onclick="parent.ExtStore.AdvPortfolioPro.image.select('<?php echo $this->image_id; ?>', '<?php echo ($this->folder ? $this->folder . '/' : '') . $this->_tmp_img->name; ?>', '<?php echo JHtml::_('advportfoliopro.image', $this->folder . '/' . $this->_tmp_img->name, 200, 200); ?>');">
			<div class="image">
				<?php echo JHtml::_('advportfoliopro.image', $this->folder . '/' . $this->_tmp_img->name, 70, 70, $this->_tmp_img->name); ?>
			</div>
		</a>
	</div>

	<div class="imagecontrol">
		<?php echo $this->_tmp_img->size; ?>
		<a class="delete-button" title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_IMAGE_REMOVE'); ?>" href="<?php echo JRoute::_('index.php?option=com_advportfoliopro&task=imagehandler.delete&tmpl=component&image_id=' . $this->image_id . '&rm[]=' . ($this->folder ? $this->folder . '/' : '') . $this->_tmp_img->name); ?>">

		</a>
		<input class="pull-left img-chk" type="checkbox" name="rm[]" data-imagename="<?php echo ($this->folder ? $this->folder . '/' : '') . $this->_tmp_img->name; ?>" data-preview="<?php echo JHtml::_('advportfoliopro.image', $this->folder . '/' . $this->_tmp_img->name, 200, 200); ?>" value="<?php echo (($this->folder ? $this->folder . '/' : '') . $this->_tmp_img->name); ?>" />
	</div>
	<div class="imageinfo">
		<?php echo $this->escape(strlen($this->_tmp_img->name) > 13 ? substr($this->_tmp_img->name, 0, 10) . '...' : $this->_tmp_img->name); ?>
	</div>
</li>
