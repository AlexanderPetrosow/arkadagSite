<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

?>

<div class="well clearfix" style="padding-bottom: 10px; padding-top: 10px; margin-bottom: 10px;">
	<div class="span6">
		<fieldset title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_UPLOAD_SELECT_IMAGE'); ?>">
			<legend>
				<?php echo JText::_('COM_ADVPORTFOLIOPRO_UPLOAD_SELECT_IMAGE'); ?>
			</legend>

			<input class="inputbox" name="image" id="image" type="file" size="40" />
			<button class="btn btn-primary" type="button" onclick="Joomla.submitform('imagehandler.upload');">
				<i class="icon-upload icon-white"></i> <?php echo JText::_('COM_ADVPORTFOLIOPRO_UPLOAD_BTN_LABEL'); ?>
			</button>
			<p>
				<span class="muted small">
					<?php echo JText::_('COM_ADVPORTFOLIOPRO_UPLOAD_VALID_FILE_TYPE'); ?>
					<em><?php echo implode(', ', explode(',', str_replace(' ', '', 'gif, jpg, jpeg, png'))); ?></em>
				</span>
			</p>
		</fieldset>
	</div>

	<?php if ($this->require_ftp) : ?>
	<div class="span6">
		<fieldset title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_FTP_TITLE'); ?>">
			<legend>
				<?php echo JText::_('COM_ADVPORTFOLIOPRO_FTP_TITLE'); ?>
			</legend>
			<?php echo JText::_('COM_ADVPORTFOLIOPRO_FTP_DESC'); ?>

			<label for="username"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
			<input type="text" id="username" name="username" class="inputbox" size="40" value="" />
			<label for="password"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
			<input type="password" id="password" name="password" class="inputbox" size="40" value="" />
		</fieldset>
	</div>
	<?php endif; ?>
</div>
