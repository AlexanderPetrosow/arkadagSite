<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>

<style>
	body {
		padding-top: 0;
	}
</style>
<script type="text/javascript">

	jQuery(document).ready(function () {

		jQuery("#buttonInsert").click(function() {
			if (jQuery(".img-chk:checked").length) {

				jQuery(".img-chk:checked").each(function () {
					var imageName = jQuery(this).data('imagename');
					var preview = jQuery(this).data('preview');

					parent.ExtStore.AdvPortfolioPro.image.selectBlank(imageName, preview);
				});

				parent.jQuery.fancybox.close();
			} else {
				alert("<?php echo JText::_('COM_ADVPORTFOLIOPRO_ALERT_LEAST_ONE_OF_THE_CHECKBOX'); ?>");
			}

		});
	});

</script>
<?php echo $this->loadTemplate('dropzoneupload'); ?>

<form action="<?php echo JRoute::_("index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=$this->image_id&folder=$this->folder"); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="subhead position-static clearfix">
		<div class="container-fluid clearfix mediamanager-control">
			<button class="button-insert btn btn-sm btn-outline-success" value="insert images" id="buttonInsert">
				<span title="Insert" class="icon-save-new"></span>Insert
			</button>

			<button class="button-delete btn btn-sm btn-danger" onclick="this.form.task.value = 'imagehandler.delete'; this.form.submit();">
				<span title="Delete" class="icon-remove"></span> Delete
			</button>
			<div class="ml-2 btn-group">
				<div class="input-group">
					<input type="text" name="filter_search" id="filter_search" class="form-control" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_SEARCH_IN_TITLE'); ?>" />
					<span class="input-group-append">
					<button type="submit" class="btn btn-secondary hasTooltip" >
						<span class="icon-search fa fa-search"></span>
					</button>
					<button type="button" class="btn btn-secondary hasTooltip js-stools-btn-clear"  onclick="document.id('filter_search').value = ''; this.form.submit();">
						<?php echo JText::_('JCLEAR'); ?>
					</button>
				</span>

				</div>
			</div>
			<div class="pull-right">
				<div class="ml-2 btn-group">
					<div class="input-group">
						<input type="text" name="new_folder" id="new_folder" class="input-small form-control" value="" />
						<button type="button" class="btn btn-secondary" onclick="if (this.form.new_folder.value) { this.form.task.value = 'imagehandler.createFolder'; this.form.submit(); }">
							<?php echo JText::_('COM_ADVPORTFOLIOPRO_FOLDER_CREATE'); ?>
						</button>
					</div>
				</div>

				<div class="pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') ;?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>

			</div>
		</div>

	</div>

	<ul class="manager-image thumbnails list-unstyled clearfix" style=" margin-top: 10px">
		<?php if ($this->folder) : ?>
			<li class="width-110 intro-item">
				<div align="center" class="imageborder">
					<a href="<?php echo JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $this->image_id . '&folder=' . dirname($this->folder)); ?>">
						<?php echo JHtml::_('image', 'com_advportfoliopro/folder_up.png', 'folder', '', true); ?>
					</a>
				</div>
				<div class="imagecontrol">

				</div>
				<div class="imageinfo">
					..
				</div>
			</li>
		<?php endif; ?>

		<?php foreach ($this->folders as $folder) : ?>
			<li class="width-110 intro-item">
				<div align="center" class="imageborder">
					<a href="<?php echo JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $this->image_id . '&folder=' . ($this->folder ? $this->folder . '/' : '') . $folder->name); ?>" title="<?php echo $folder->name; ?>">
						<?php echo JHtml::_('image', 'com_advportfoliopro/folder.png', $folder->name, '', true); ?>
					</a>
				</div>
				<div class="imagecontrol">
					<a class="delete-button" title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_IMAGE_REMOVE'); ?>" href="<?php echo JRoute::_('index.php?option=com_advportfoliopro&task=imagehandler.deleteFolder&tmpl=component&image_id=' . $this->image_id . '&rm[]=' . ($this->folder ? $this->folder . '/' : '') . $folder->name); ?>">

					</a>
				</div>
				<div class="imageinfo">
					<?php echo $this->escape(strlen($folder->name) > 13 ? substr($folder->name, 0, 10) . '...' : $folder->name); ?>
				</div>
			</li>
		<?php endforeach; ?>

		<?php for ($i = 0, $n = count($this->items); $i < $n; $i++) : ?>
			<?php $this->setImage($i); ?>
			<?php echo $this->loadTemplate('image'); ?>
		<?php endfor; ?>
	</ul>

	<?php if ($this->pagination->total > $this->pagination->limit) : ?>
	<?php echo $this->pagination->getListFooter(); ?>
	<?php endif; ?>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php
echo AdvPortfolioProFactory::getFooter();