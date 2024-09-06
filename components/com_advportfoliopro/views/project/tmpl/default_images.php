<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;


JHtml::addIncludePath(JPATH_ROOT . '/administrator/components/com_advportfoliopro/helpers/html/');
JHtml::_('advportfoliopro.modal');
JHtml::_('stylesheet', 'com_advportfoliopro/swiper.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'com_advportfoliopro/font-awesome.css', array('version' => 'auto', 'relative' => true));


JHtml::_('script', 'com_advportfoliopro/swiper.min.js', array('version' => 'auto', 'relative' => true));

$animation	= $this->item->params->get('animation', 'slide');

$this->document->addScriptDeclaration("
(function($) {
	$(document).ready(function() {
		if (jQuery('#project-wrapper .swiper-container').length) {
			var mdWidth = jQuery('#project-wrapper .swiper-container').width();
			jQuery('#project-wrapper .swiper-container').css ({
				'width': mdWidth + 'px'
			});
			
			var swiper = new Swiper('#project-wrapper .swiper-container', {
				autoHeight: true,
				slidesPerView: 'auto',
				effect: '$animation',
				pagination: {
					el: '.swiper-pagination',
					type: 'fraction',
				},
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
			});
		}
	});
})(jQuery);
");
?>
<div class="swiper-container ext-gallery">
	<div class="swiper-wrapper">
		<?php foreach ($this->item->images as $image) : ?>
			<div class="swiper-slide">
				<?php if ($this->item->params->get('popup') == 1) : ?>
					<a rel="gallery" class="exmodal" data-fancybox="images" data-caption="<?php echo $image->title; ?>" title="<?php echo $this->escape($image->title); ?>" href="<?php echo JHtml::_('advportfoliopro.image', $image->image); ?>">
						<?php echo JHtml::_('advportfoliopro.image', $image->image, null, null, $this->escape($image->title ? $image->title : $this->item->title)); ?>
					</a>
				<?php else : ?>
					<?php echo JHtml::_('advportfoliopro.image', $image->image, null, null, $this->escape($image->title ? $image->title : $this->item->title)); ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ($this->item->params->get('show_pagination_slider')) : ?>
	<!-- Add Pagination -->
	<div class="swiper-pagination"></div>
	<?php endif; ?>

	<?php if ($this->item->params->get('show_navigation_slider')) : ?>
	<!-- Add Arrows -->
	<div class="swiper-button-next"></div>
	<div class="swiper-button-prev"></div>
	<?php endif; ?>
</div>


