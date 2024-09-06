<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('stylesheet', 'com_advportfoliopro/style.css', array('version' => 'auto', 'relative' => true));

$hasMedia	= ($this->item->type == 0 && count($this->item->images)) || ($this->item->type == 1 && $this->item->video_link);
$hasContent	= $this->item->description != '' && $this->item->params->def('show_description', 1);
$this->item->params->def('description_position', 'right');
$this->item->params->def('description_width', '4');

$btnlabel			= $this->item->params->get('label_btn_go_back', 'Go Back');
$btnlink			= $this->item->params->get('link_btn_go_back', '');
$btnposition		= $this->item->params->get('position_btn_go_back', 'bottom');
$btnalignment		= $this->item->params->get('alignment_btn_go_back', 'left');
$detail_css			= $this->item->params->get('custom_css');
$bg_goback			= $this->params->get('bg_btn_goback', '#177f8f');
$bg_goback_hover 	= $this->params->get('bg_btn_goback_hover', '');


if ($bg_goback_hover) {
	$bg_gobackCSs = <<<BGGOBACKCSS
.btn-wrapper .btn-go-back {
	background-color: $bg_goback;
	transition: all 0.3s ease-in-out 0s;
	-webkit-transition: all 0.3s ease-in-out 0s;
	-moz-transition: all 0.3s ease-in-out 0s;
	-ms-transition: all 0.3s ease-in-out 0s;
}

.btn-wrapper .btn-go-back:hover {
	background-color: $bg_goback_hover;
}
BGGOBACKCSS;

} else {
	$bg_gobackCSs	= "
.btn-wrapper .btn-go-back {
	background-color: $bg_goback;
}
";
}

$this->document->addStyleDeclaration($bg_gobackCSs);

$css = <<<CSS
.btn-wrapper {
	text-align: $btnalignment;
}
CSS;
if ($detail_css) {
	$this->document->addStyleDeclaration($detail_css);
}
$this->document->addStyleDeclaration($css);

$this->document->addScriptDeclaration("
jQuery(document).ready(function() {
	if (parseInt(jQuery('#project-wrapper [class*=\"col-sm-\"]').css('padding-left')) == 0) {
		jQuery('#project-wrapper').removeClass('row');
	}
});
");
?>

<div class="item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->item->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->item->params->get('page_heading')); ?>
			</h1>

			<?php if (!$this->item->params->get('show_title') && $this->item->params->get('show_project_nav') == 1) : ?>
				<div class="project-nav">
					<a class="prev-project<?php echo $this->prevItem ? '' : ' disable'; ?>" href="<?php echo $this->prevItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->prevItem->slug, $this->prevItem->catslug) : 'javascript:void(0);'; ?>"></a>
					<a class="next-project<?php echo $this->nextItem ? '' : ' disable'; ?>" href="<?php echo $this->nextItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->nextItem->slug, $this->nextItem->catslug) : 'javascript:void(0);'; ?>"></a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ($this->item->params->get('show_title', true) || $this->item->params->get('show_project_nav') == 1) : ?>
	<div class="page-header clearfix">
		<?php if ($this->item->params->get('show_title', true)) : ?>
			<h2 itemprop="name">
				<?php echo $this->escape($this->item->title); ?>
			</h2>
		<?php endif; ?>
		<?php if ($this->item->params->get('show_project_nav') == 1) : ?>
			<div class="project-nav">
				<a class="prev-project<?php echo $this->prevItem ? '' : ' disable'; ?>" href="<?php echo $this->prevItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->prevItem->slug, $this->prevItem->catslug) : 'javascript:void(0);'; ?>"></a>
				<a class="next-project<?php echo $this->nextItem ? '' : ' disable'; ?>" href="<?php echo $this->nextItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->nextItem->slug, $this->nextItem->catslug) : 'javascript:void(0);'; ?>"></a>
			</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if ($this->item->params->get('show_btn_go_back') && $btnposition == 'top') : ?>
		<div class="btn-wrapper">
			<?php if ($btnlink) : ?>
				<a class="btn-go-back" href="<?php echo $btnlink; ?>"><?php echo $btnlabel; ?></a>
			<?php else : ?>
				<button class="btn-go-back" onclick="window.history.back();">
					<?php echo $btnlabel; ?>
				</button>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div id="project-wrapper" class="project-wrapper clearfix">
		<?php if ($hasMedia && $hasContent) : ?>

			<?php if ($this->item->params->get('description_position') == 'top' || $this->item->params->get('description_position') == 'bottom') : ?>
				<?php if ($this->item->params->get('description_position') == 'top') : ?>
					<div>
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

				<div>
					<?php echo $this->loadTemplate('media'); ?>

				</div>

				<?php if ($this->item->params->get('description_position') == 'bottom') : ?>
					<div>
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

			<?php else : ?>

				<?php if ($this->item->params->get('description_position') == 'left') : ?>
					<div class="col<?php echo $this->item->params->get('description_width'); ?>">
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

				<div class="col<?php echo (12 - $this->item->params->get('description_width')); ?>">
					<?php echo $this->loadTemplate('media'); ?>

				</div>

				<?php if ($this->item->params->get('description_position') == 'right') : ?>
					<div class="col<?php echo $this->item->params->get('description_width'); ?>">
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

			<?php endif; ?>

		<?php else : ?>

			<?php if ($hasMedia) : ?>

				<div>
					<?php echo $this->loadTemplate('media'); ?>
				</div>
			<?php else : ?>

				<?php echo $this->loadTemplate('description'); ?>

			<?php endif; ?>

		<?php endif; ?>

	</div>
	<?php if ($this->item->params->get('show_btn_go_back') && $btnposition == 'bottom') : ?>
		<div class="btn-wrapper">
			<?php if ($btnlink) : ?>
				<a class="btn-go-back" href="<?php echo $btnlink; ?>"><?php echo $btnlabel; ?></a>
			<?php else : ?>
				<button class="btn-go-back" onclick="window.history.back();">
					<?php echo $btnlabel; ?>
				</button>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
