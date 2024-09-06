<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', '#jform_catid', null, array('disable_search_threshold' => 0 ));
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task) {
		if (task == "project.cancel" || document.formvalidator.isValid(document.getElementById("project-form"))) {
			' . $this->form->getField('description')->save() . '
			Joomla.submitform(task, document.getElementById("project-form"));

			// @deprecated 4.0  The following js is not needed since 3.7.0.
			if (task !== "project.apply") {
				window.parent.jQuery("#projectEdit' . $this->item->id . 'Modal").modal("hide");
			}
		}
	};
');

// Fieldsets to not automatically render by /layouts/joomla/edit/params.php
$this->ignore_fieldsets = array('details', 'item_associations', 'jmetadata');

// In case of modal
$isModal = $input->get('layout') == 'modal' ? true : false;
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>

<form action="<?php echo JRoute::_('index.php?option=com_advportfoliopro&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="project-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_ADVPORTFOLIOPRO_PROJECT_NEW') : JText::sprintf('COM_ADVPORTFOLIOPRO_PROJECT_EDIT', $this->item->id)); ?>
		<div class="row-fluid row">
			<div class="span9 col-md-9">
				<?php echo $this->form->renderField('link'); ?>
				<?php echo $this->form->renderField('ordering'); ?>
				<?php echo $this->form->renderField('short_description'); ?>
				<?php echo $this->form->renderField('description'); ?>
			</div>
			<div class="span3 col-md-3">
				<div class="card card-light">
					<div class="card-body">
						<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_ADVPORTFOLIOPRO_PROJECT_FIELDSET_MEDIA', true)); ?>
		<div class="form-horizontal-desktop">
			<?php echo $this->form->renderField('thumbnail'); ?>
			<?php echo $this->form->renderField('type'); ?>
			<?php echo $this->form->renderField('video_link'); ?>
			<?php echo $this->form->renderField('images'); ?>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid row form-horizontal-desktop">
			<div class="span6 col-md-6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6 col-md-6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>

<?php
echo AdvPortfolioProFactory::getFooter();