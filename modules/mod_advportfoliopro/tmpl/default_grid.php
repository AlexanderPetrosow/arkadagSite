<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_advportfoliopro/helpers/route.php';
require_once JPATH_ROOT . '/components/com_advportfoliopro/helpers/advportfoliopro.php';

JHtml::addIncludePath(JPATH_ROOT . '/administrator/components/com_advportfoliopro/helpers/html/');
JHtml::_('advportfoliopro.modal');
JHtml::_('stylesheet', 'com_advportfoliopro/font-awesome.css', array('version' => 'auto', 'relative' => true));

JHtml::_('stylesheet', 'com_advportfoliopro/style.css', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'com_advportfoliopro/modernizr.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'com_advportfoliopro/imagesloaded.pkgd.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'com_advportfoliopro/isotope.pkgd.min.js', array('version' => 'auto', 'relative' => true));

if ($overlay_effect == 'hoverdir') {
	JHtml::_('script', 'com_advportfoliopro/jquery.hoverdir.js', array('version' => 'auto', 'relative' => true));
}

JHtml::_('script', 'com_advportfoliopro/script.js', array('version' => 'auto', 'relative' => true));

$document = JFactory::getDocument();
$document->addScriptDeclaration('ExtStore.AdvPortfolioPro.live_site = \'' . JUri::base(true) . '\';');

// get style
$numColumns			= (int) $params->get('num_columns', 1);

$item_class			= ' col-md-' . ($numColumns);

$image_width		= $params->get('image_width', 1200 / $numColumns);
$image_height		= (int) $params->get('image_height', 0);
$gutterWidth	 	= ((int) $params->get('gutter_width')) . 'px';
$gutterHalf			= ((int) $params->get('gutter_width') / 2) . 'px';
$limit				= $params->get('limit', 5);
$numberload		 	= (int) $params->get('number_load_each', 3);
$catids				= $params->get('catids', array());
$orderby			= $params->get('orderby', 'rdate');
$overlayColor1		= $params->get('overlay_color1', '#5aabd6');
$overlayColor2		= $params->get('overlay_color2', '');
$overlayOpacity 	= $params->get('overlay_opacity', 100) / 100;

if ($bgInfoHoverIcon) {
	$bg_iconCSs = <<<BGICONCSS
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra .project-icon {
	background-color: $bgInfoIcon;
	transition: all 0.3s ease-in-out 0s;
	-webkit-transition: all 0.3s ease-in-out 0s;
	-moz-transition: all 0.3s ease-in-out 0s;
	-ms-transition: all 0.3s ease-in-out 0s;
}

#portfolio-module-$id .projects-wrapper .project-img .project-img-extra .project-icon:hover,
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra .project-icon:active,
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra .project-icon:focus {
	background-color: $bgInfoHoverIcon;

}
BGICONCSS;

} else {
	$bg_iconCSs	= "
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra .project-icon {
	background-color: $bgInfoIcon;
}
";
}

$document->addStyleDeclaration($bg_iconCSs);

if ($overlayColor1 == '#5aabd6' && !$overlayColor2) {
	$overlayColor2	= '#90c9e8';
}

$rgbaColor1 = AdvPortfolioProHelper::hex2RGB($overlayColor1, true) . ',' . $overlayOpacity;
$rgbaColor2 = AdvPortfolioProHelper::hex2RGB($overlayColor2, true) . ',' . $overlayOpacity;

if ($overlayColor2) {
	$css	= <<<CSS
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra {
	background-image: -webkit-linear-gradient(top , rgba($rgbaColor2) 0%, rgba($rgbaColor1) 100%);
	background-image: -moz-linear-gradient(top , rgba($rgbaColor2) 0%, rgba($rgbaColor1) 100%);
	background-image: -o-linear-gradient(top , rgba($rgbaColor2) 0%, rgba($rgbaColor1) 100%);
	background-image: -ms-linear-gradient(top , rgba($rgbaColor2) 0%, rgba($rgbaColor1) 100%);
	background-image: linear-gradient(top , rgba($rgbaColor2) 0%, rgba($rgbaColor1) 100%);
}
CSS;

} else {
	$css	= "
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra {
	background-color: rgba($rgbaColor1);
	background-image: none;
}
";
}
$document->addStyleDeclaration($css);

$defaultCss = <<<DEFAULTCSS
#portfolio-module-$id .projects-filter a.selected,
#portfolio-module-$id .projects-filter a.selected:hover {
	background: $bgFilterSelected;
}

#portfolio-module-$id .projects-filter a:hover {
	background: $bgFilterHover;
}

#portfolio-module-$id .projects-wrapper .project-img img  {
	transition-property: all;
	transition-duration: $hover_duration;
	transition-timing-function: $hover_easing;
	transition-delay: $hover_delayCss;

	-moz-transition-property: all;
	-moz-transition-duration: $hover_duration;
	-moz-transition-timing-function: $hover_easing;
	-moz-transition-delay: $hover_delayCss;

	-webkit-transition-property: all;
	-webkit-transition-duration: $hover_duration;
	-webkit-transition-timing-function: $hover_easing;
	-webkit-transition-delay: $hover_delayCss;

	-ms-transition-property: all;
	-ms-transition-duration: $hover_duration;
	-ms-transition-timing-function: $hover_easing;
	-ms-transition-delay: $hover_delayCss;
}

#portfolio-module-$id .projects-wrapper .project-img .project-img-extra {
	transition-property: all;
	transition-duration: $hover_duration;
	transition-timing-function: $hover_easing;
	transition-delay: $hover_delayCss;

	-webkit-transition-property: all;
	-webkit-transition-duration: $hover_duration;
	-webkit-transition-timing-function: $hover_easing;
	-webkit-transition-delay: $hover_delayCss;

	-moz-transition-property: all;
	-moz-transition-duration: $hover_duration;
	-moz-transition-timing-function: $hover_easing;
	-moz-transition-delay: $hover_delayCss;

	-ms-transition-property: all;
	-ms-transition-duration: $hover_duration;
	-ms-transition-timing-function: $hover_easing;
	-ms-transition-delay: $hover_delayCss;

}
DEFAULTCSS;
$document->addStyleDeclaration($defaultCss);

if ($overlay_effect == 'none') {
	$noneCss = <<<NONE
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra {
	 transform: translateX(0%);
	-webkit-transform: translateX(0%);
	-moz-transform: translateX(0%);
	-ms-transform: translateX(0%);
}
NONE;
	$document->addStyleDeclaration($noneCss);
}

if ($overlay_effect == 'hoverdir') {
	$hoverdirCss = <<<HOVERDIR
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra {
	display: block;
	left: -100%;
	top: 0;
	position: absolute;
	text-align: center;
	width: 100%;
	height: 100%;
	transform: none;
	transition: initial;
}

#portfolio-module-$id .projects-wrapper .project-img:hover .project-img-extra {
	opacity: 1;
	transform: none;
}
HOVERDIR;

} else {
	$hoverdirCss	= "
#portfolio-module-$id .projects-wrapper .project-img .project-img-extra {
	left: 0;
}
";
}
$document->addStyleDeclaration($hoverdirCss);

$str_transform = '';

if ($scale) {
	if ($scale_x && !$scale_y) {
		$str_transform .= 'scaleX(' . $scale_x . ') ';
	}
	if (!$scale_x && $scale_y) {
		$str_transform .= 'scaleY(' . $scale_y . ') ';
	}
	if ($scale_x && $scale_y) {
		$str_transform .= 'scale(' . $scale_x . ',' . $scale_y . ') ';
	}
}

if ($translate) {
	if ($translate_x && !$translate_y) {
		$str_transform .= 'translateX(' . $translate_x . 'px' . ') ';
	}
	if (!$translate_x && $translate_y) {
		$str_transform .= 'translateY(' . $translate_y . 'px' . ') ';
	}
	if ($translate_x && $translate_y) {
		$str_transform .= 'translate(' . $translate_x . 'px' . ',' . $translate_y . 'px' . ') ';
	}
}

if ($rotatex) {
	$str_transform .= 'rotateX('. $rotate_angle_x . 'deg' . ') ';
}
if ($rotatey) {
	$str_transform .= 'rotateY('. $rotate_angle_y . 'deg' . ') ';
}
if ($rotatez) {
	$str_transform .= 'rotateZ('. $rotate_angle_z . 'deg' . ') ';
}

if ($skew) {
	if ($skew_angle_x && !$skew_angle_y) {
		$str_transform .= 'skewX('. $skew_angle_x . 'deg' . ') ';
	}
	if (!$skew_angle_x && $skew_angle_y) {
		$str_transform .= 'skewY('. $skew_angle_y . 'deg' . ') ';
	}
	if ($skew_angle_x && $skew_angle_y) {
		$str_transform .= 'skew('. $skew_angle_x . 'deg' . ',' . $skew_angle_y . 'deg' . ') ';
	}
}

if ($str_transform != '') {
	$thumbTransCss = <<<THUMBTRANS
#portfolio-module-$id .projects-wrapper .project-img:hover img {
	transform: $str_transform;
	transition-property: all;
	transition-duration: $hover_duration;
	transition-timing-function: $hover_easing;
	transition-delay: $hover_delayCss;

	-webkit-transform: $str_transform;
	-webkit-transition-property: all;
	-webkit-transition-duration: $hover_duration;
	-webkit-transition-timing-function: $hover_easing;
	-webkit-transition-delay: $hover_delayCss;

	-moz-transform: $str_transform;
	-moz-transition-property: all;
	-moz-transition-duration: $hover_duration;
	-moz-transition-timing-function: $hover_easing;
	-moz-transition-delay: $hover_delayCss;

	-ms-transform: $str_transform;
	-ms-transition-property: all;
	-ms-transition-duration: $hover_duration;
	-ms-transition-timing-function: $hover_easing;
	-ms-transition-delay: $hover_delayCss;
}
THUMBTRANS;

} else {
	$thumbTransCss	= "
#portfolio-module-$id .projects-wrapper .project-img:hover img {
	transform: none;
}
";
}
$document->addStyleDeclaration($thumbTransCss);

if ($gutterWidth) {
	$gutterWidthCss	= <<<CSS
#portfolio-module-$id .container-isotop {
	margin-left: -$gutterHalf;
	margin-right: -$gutterHalf;
}
#portfolio-module-$id .container-isotop .isotope-item {
	padding-left: $gutterHalf;
	padding-right: $gutterHalf;
	margin-bottom: $gutterWidth;
}
CSS;
$document->addStyleDeclaration($gutterWidthCss);
}

?>

<div id="portfolio-module-<?php echo $id; ?>"
	class="portfolio-wrapper portfolio-module portfolio-module<?php echo $moduleclass_sfx; ?>"
	data-speed="<?php echo $params->get('speed', 5000); ?>"
	data-max-items="<?php echo $params->get('num_columns', 1); ?>"
	data-overlay_effect="<?php echo $overlay_effect; ?>"
	data-hoverdir_easing="<?php echo $hover_easing; ?>"
	data-hoverdir_speed="<?php echo $hoverdir_speed ?>"
	data-hoverdir_hover_delay="<?php echo $hover_delay ?>"
	data-hoverdir_inverse="<?php echo $hoverdir_inverse; ?>"
	data-columns="<?php echo $numColumns; ?>"
	data-gutter-width="<?php echo $gutterWidth; ?>"
	data-display_type="<?php echo $displayType; ?>"
>

	<?php if ($show_filter) : ?>
		<div class="projects-filter">

			<?php if ($show_filter_all) : ?>
				<a href="#" class="selected first-ft" data-filter="*">
					<?php echo JText::_('COM_ADVPORTFOLIOPRO_FILTER_ALL'); ?>
				</a>
				<?php foreach ($tags as $alias => $tag) : ?>
					<a href="#" data-filter=".<?php echo $alias; ?>"><?php echo $tag; ?></a>

				<?php endforeach; ?>
			<?php else: ?>

				<?php $f_i = 0; ?>
				<?php foreach ($tags as $alias => $tag) : ?>
					<?php if ($f_i == 0) : ?>
						<a href="#" class="selected first-ft" data-filter=".<?php echo $alias; ?>"><?php echo $tag; ?></a>
					<?php else: ?>
						<a href="#" data-filter=".<?php echo $alias; ?>"><?php echo $tag; ?></a>
					<?php endif; ?>
					<?php $f_i++; ?>

				<?php endforeach; ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class=" projects-wrapper module-projects-wrapper container-isotop row">
		<?php foreach ($items as $item) :
			$link			= AdvPortfolioProHelperRoute::getProjectRoute($item->slug, $item->catslug);
			$cat_link		= AdvPortfolioProHelperRoute::getCategoryRoute($item->catslug);
			$class			= '';

			if ($item->featured) {
				$item_class			 = ' col-md-' . (($numColumns)*2);
			}

			if ($show_filter == 1) {
				foreach ($item->tags as $tag) {
					//$class .= ' ' . JApplicationHelper::stringURLSafe($tag);
					$class .= ' ' . str_replace(' ', '-', $tag->alias);
				}
			} elseif ($show_filter != 0) {
				//$class	.= ' ' . JApplicationHelper::stringURLSafe($item->category_title);
				$class	.= ' ' . str_replace(' ', '-', $item->category_alias);
			}

			$registry = new JRegistry();
			$registry->loadString($item->params);
			$thumb_width = $registry->get('image_width');
			$thumb_height = $registry->get('image_height');

			if ($thumb_width != '' && $thumb_height == '') {
				$img_html = JHtml::_('advportfoliopro.image', $item->thumbnail, $thumb_width, $image_height, $item->thumbnail, false);
			} elseif ($thumb_width == '' && $thumb_height != '') {
				$img_html = JHtml::_('advportfoliopro.image', $item->thumbnail, $image_width, $thumb_height, $item->thumbnail, false);
			} elseif ($thumb_width != '' && $thumb_height != '') {
				$img_html = JHtml::_('advportfoliopro.image', $item->thumbnail, $thumb_width, $thumb_height, $item->thumbnail, false);
			} else {
				$img_html = JHtml::_('advportfoliopro.image', $item->thumbnail, $item->featured ? $image_width * 2 : $image_width, $item->featured ? $image_height * 2 : $image_height, $item->thumbnail, false);
			}

			?>

			<div class="col-12 col-sm-6 isotope-item project-<?php echo $item->id . $class . $item_class; ?>">
				<div class="project-inner">
				<?php if ($item->thumbnail) : ?>
					<div class="project-img">
						<?php if ($params->get('click_thumbnail_to') == 1) : ?>
							<?php if (($params->get('link_of_project')) == 0 && $item->link) : ?>
								<a class="link-detail" href="<?php echo $item->link; ?>" <?php echo $str_target; ?> >
									<?php echo $img_html; ?>
								</a>

							<?php else: ?>
								<a class="link-detail" href="<?php echo $link; ?>" <?php echo $str_target; ?> >
									<?php echo $img_html; ?>
								</a>

							<?php endif; ?>
						<?php else: ?>
							<a class="link-detail gallery-popup" data-project-id="<?php echo $item->id; ?>" href="<?php echo $link; ?>">
								<?php echo $img_html; ?>
							</a>
						<?php endif; ?>


						<?php if ($params->get('show_info', 1)) : ?>

							<div class="project-img-extra">
								<?php if ($params->get('click_thumbnail_to') == 1) : ?>
									<?php if (($params->get('link_of_project')) == 0 && $item->link) : ?>
										<a class="link-detail" href="<?php echo $item->link; ?>" <?php echo $str_target; ?> ></a>

									<?php else: ?>
										<a class="link-detail" href="<?php echo $link; ?>" <?php echo $str_target; ?> ></a>

									<?php endif; ?>
								<?php else: ?>
									<a class="link-detail gallery-popup" data-project-id="<?php echo $item->id; ?>" href="<?php echo $link; ?>"></a>
								<?php endif; ?>
								<div class="project-img-extra-content">
									<?php if ($params->get('show_info_project_details', 1)) : ?>
										<a class="project-icon" href="<?php echo $link; ?>" <?php echo $str_target; ?> title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_DETAILS'); ?>">
											<span class="fa fa-link"></span>
										</a>
									<?php endif; ?>

									<?php if ($item->link && $params->get('show_info_project_link', 1)) : ?>
										<a class="project-icon link-icon" href="<?php echo $item->link; ?>" <?php echo $str_target; ?> title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_LINK'); ?>">
											<span class="fa fa-external-link"></span>
										</a>
									<?php endif; ?>

									<?php if ($params->get('show_info_project_gallery', 1)) : ?>
										<a class="project-icon gallery-icon" data-project-id="<?php echo $item->id; ?>" href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_GALLERY'); ?>">
											<span class="fa fa-picture-o"></span>
										</a>
									<?php endif; ?>

									<?php if ($params->get('show_info_title', 1)) : ?>
										<h4><?php echo $item->title; ?></h4>
									<?php endif; ?>

									<?php if ($params->get('show_info_category', 1)) : ?>
										<h5><a href="<?php echo $cat_link ?>" <?php echo $str_target; ?>><?php echo $item->category_title; ?></a></h5>
									<?php endif; ?>
								</div>
							</div>

						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ($params->get('show_title_list', 1) || $params->get('show_category') || $params->get('show_short_description', 1)) : ?>

					<div class="project-item-meta">
						<?php if ($params->get('show_title_list', 1)) : ?>
							<h4>
								<?php if (($params->get('link_of_project')) == 0 && $item->link) : ?>

									<a rel="bookmark" <?php echo $str_target; ?> title="<?php echo $item->title; ?>" href="<?php echo $item->link; ?>">
										<?php echo $item->title; ?>
									</a>
								<?php else: ?>
									<a rel="bookmark" <?php echo $str_target; ?> title="<?php echo $item->title; ?>" href="<?php echo $link; ?>">
										<?php echo $item->title; ?>
									</a>

								<?php endif; ?>
							</h4>
						<?php endif; ?>

						<?php if ($params->get('show_category')) : ?>
							<h5>
								<a href="<?php echo $cat_link ?>" <?php echo $str_target; ?>>
									<?php echo $item->category_title; ?>
								</a>
							</h5>
						<?php endif; ?>

						<?php if ($params->get('show_short_description', 1)) : ?>
							<?php echo $item->short_description; ?>
						<?php endif; ?>
					</div>

				<?php endif; ?>
				</div>
			</div>

		<?php endforeach; ?>
	</div>

	<?php if (ModAdvPortfolioProHelper::gettotalProjects($catids) > $limit && $params->get('loadmore', 1)) : ?>
		<div class="text-center mt-10 load-more-wrapper">
			<a href="#" class="ext-load-more"
			   data-limit="<?php echo $limit; ?>"
			   data-numberload="<?php echo $numberload; ?>"
			   data-catids="<?php echo(implode(', ', $catids)); ?>"
			   data-orderby="<?php echo $orderby; ?>"
			   data-columns="<?php echo $numColumns; ?>"
			   data-show_filter="<?php echo $show_filter; ?>"
			   data-click_thumbnail_to="<?php echo $params->get('click_thumbnail_to'); ?>"
			   data-link_of_project="<?php echo $params->get('link_of_project'); ?>"
			   data-show_info="<?php echo $params->get('show_info'); ?>"
			   data-show_info_project_details="<?php echo $params->get('show_info_project_details'); ?>"
			   data-show_info_project_link="<?php echo $params->get('show_info_project_link'); ?>"
			   data-show_info_project_gallery="<?php echo $params->get('show_info_project_gallery'); ?>"
			   data-show_info_title="<?php echo $params->get('show_info_title'); ?>"
			   data-show_info_category="<?php echo $params->get('show_info_category'); ?>"
			   data-show_title_list="<?php echo $params->get('show_title_list'); ?>"
			   data-show_category="<?php echo $params->get('show_category'); ?>"
			   data-show_short_description="<?php echo $params->get('show_short_description'); ?>"
			   data-str_target="<?php echo $str_target; ?>"
			   >
				<i class="fa fa-spinner fa-spin"></i>
				<span><?php echo JText::_('Load More'); ?></span>
			</a>
		</div>
	<?php endif; ?>


</div>