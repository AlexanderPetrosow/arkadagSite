<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

// No direct access.
defined('_JEXEC') or die;

// add css & js
JHtml::_('stylesheet', 'com_advportfoliopro/dropzone.css', false, true, false, false, true);
JHtml::_('script', 'com_advportfoliopro/dropzone.js', false, true, false, false, true);

$allowed_exts	= array('gif', 'jpg', 'jpeg', 'png');

?>
<script>
(function ($) {
	var allowed_exts	= $.parseJSON('<?php echo json_encode($allowed_exts); ?>');
	var imageSelected	= false;

	Dropzone.options.uploadForm = {
		paramName: 'image',
		accept: function (file, done) {
			var typeFile = file.name.substr((file.name.lastIndexOf('.') + 1)).toLowerCase();
			if (jQuery.inArray(typeFile, allowed_exts) == -1) {
				this.removeFile(file);
				return;
			} else {
				isAllow = true;
				done();
			}
		},

		complete: function(file) {

			if (this.getUploadingFiles().length == 0 && this.getQueuedFiles().length == 0 && isAllow == true) {
				if ($('#upload_add').is(':checked')) {
					parent.jQuery.fancybox.close();
				} else {
					window.location = window.location.href;
				}
			}
		},

		success: function(file, data) {
			<?php if ($this->image_id != 'jform_thumbnail') : ?>

				if ($('#upload_add').is(':checked')) {
					if (data) {
						data	= $.parseJSON(data);

						if (!imageSelected) {
							imageSelected	= true;

							parent.ExtStore.AdvPortfolioPro.image.select('<?php echo $this->image_id; ?>', data.name, data.preview, true);
						} else {
							parent.ExtStore.AdvPortfolioPro.image.selectBlank(data.name, data.preview);
						}
					}
				}

			<?php endif; ?>
		}
	}
})(jQuery);
</script>

<form class="dropzone" action="<?php echo JRoute::_("index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=$this->image_id&folder=$this->folder"); ?>" method="post" name="uploadForm" id="uploadForm" enctype="multipart/form-data">
	<div class="fallback">
		<input class="inputbox" name="image" id="image" type="file" size="40" />
		<button class="btn btn-primary" type="button" onclick="Joomla.submitform('imagehandler.upload');">
			<i class="icon-upload icon-white"></i> <?php echo JText::_('COM_ADVPORTFOLIOPRO_UPLOAD_BTN_LABEL'); ?>
		</button>
		<p>
			<span class="muted small">
				<?php echo JText::_('COM_ADVPORTFOLIOPRO_UPLOAD_VALID_FILE_TYPE'); ?>
				<em><?php echo implode(', ', explode(',', str_replace(' ', '', 'gif, jpg, png'))); ?></em>
			</span>
		</p>
	</div>

	<div class="upload-options">
		<?php if ($this->image_id != 'jform_thumbnail') : ?>

			<label class="checkbox">
				<input type="checkbox" id="upload_add" checked="checked" />
				<?php echo JText::_('COM_ADVPORTFOLIOPRO_UPLOAD_AND_ADD'); ?>
			</label>

		<?php endif; ?>
	</div>

	<input type="hidden" name="task" value="imagehandler.ajaxUpload" />
	<?php echo JHtml::_('form.token'); ?>
</form>