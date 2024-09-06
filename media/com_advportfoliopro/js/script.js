/**
 * @copyright    Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if (!ExtStore) {
	var ExtStore = {};
}

ExtStore.AdvPortfolioPro = {
	live_site: null,

	init: function() {}
};

(function($, window, document) {
	'use strict';
	var EXTSTORE;
	EXTSTORE = window.EXTSTORE || {};
	window.EXTSTORE = EXTSTORE;

	EXTSTORE.isotopeInit = function() {
		$('.portfolio-wrapper').each(function() {
			var $iso, $isoItem, $this, page;
			$this = $(this);

			var gutterWidth 		= $this.data('gutter-width');
			var firstFilter			= $this.find('.first-ft').data('filter');
			var columns				= $this.data('columns');

			$iso = $this.find('.container-isotop');
			$isoItem = $this.find('.isotope-item');

			$iso.imagesLoaded().progress(function() {
				$iso.isotope({
					itemSelector: '.isotope-item',
					percentPosition: true,
					filter: firstFilter,
					masonry: {
						columnWidth: '.col-md-' + (columns)
					}
				});
				EXTSTORE.isotopeRebind($this);
			});

			page = 0;
			$this.find('.ext-load-more').on('click', function(e) {
				e.preventDefault();
				$this = jQuery(this);

				var limit = $this.data('limit');
				var numberload = $this.data('numberload');
				var orderby = $this.data('orderby');
				var catids = $this.data('catids');
				var columns = $this.data('columns');
				var show_filter = $this.data('show_filter');
				var click_thumbnail_to = $this.data('click_thumbnail_to');
				var link_of_project = $this.data('link_of_project');
				var show_info = $this.data('show_info');
				var show_info_project_details = $this.data('show_info_project_details');
				var show_info_project_link = $this.data('show_info_project_link');
				var show_info_project_gallery = $this.data('show_info_project_gallery');
				var show_info_title = $this.data('show_info_title');
				var show_info_category = $this.data('show_info_category');
				var show_title_list = $this.data('show_title_list');
				var show_category = $this.data('show_category');
				var show_short_description = $this.data('show_short_description');
				var str_target = $this.data('str_target');

				$this.addClass('loading');
				page++;
				var request = {
					'option' : 'com_ajax',
					'module' : 'advportfoliopro',
					'method':	'getProjects',
					'format' : 'raw',
					'limit'   : limit,
					'numberload' : numberload,
					'orderby' :	orderby,
					'catids' : catids,
					'columns' : columns,
					'show_filter': show_filter,
					'click_thumbnail_to': click_thumbnail_to,
					'link_of_project': link_of_project,
					'show_info': show_info,
					'show_info_project_details': show_info_project_details,
					'show_info_project_link': show_info_project_link,
					'show_info_project_gallery': show_info_project_gallery,
					'show_info_title': show_info_title,
					'show_info_category': show_info_category,
					'show_title_list': show_title_list,
					'show_category': show_category,
					'show_short_description': show_short_description,
					'str_target': str_target,
					'page': page
				};

				jQuery.ajax({
					type: 'POST',
					data: request,
					success: function(response) {
						var $content, data;
						$this.removeClass('loading');
						data = JSON.parse(response);
						$content = $(data.html);
						if (page >= data.max_page) {
							$this.hide();
						}
						setTimeout(function() {

							$iso.imagesLoaded(function() {
								$iso.append($content).isotope('appended', $content);
								EXTSTORE.hoverDir();
							});

						}, 600)
					}
				});
			});
		});
	};

	EXTSTORE.isotopeRebind = function($wrapper) {
		var $iso;
		$iso = $wrapper.find('.container-isotop');
		$wrapper.find('.projects-filter > a').on('click', function(event) {
			var selector;
			event.preventDefault();
			$(this).addClass('selected').siblings().removeClass('selected');
			selector = $(this).data('filter');
			$iso.isotope({
				filter: selector
			});
		});
	};

	EXTSTORE.openGallery = function() {
		// gallery button
		jQuery('a.gallery-icon, a.gallery-popup').click(function(event) {
			var $this = jQuery(this);

			event.preventDefault();

			jQuery.ajax({
				url: ExtStore.AdvPortfolioPro.live_site + '/?option=com_advportfoliopro&view=project&format=raw&id=' + $this.data('project-id')
			}).done(function (data) {
				jQuery.fancybox.open(jQuery.parseJSON(data));
			});
		});
	};

	EXTSTORE.hoverDir = function() {
		$('.portfolio-wrapper').each(function() {
			var $this;
			$this = $(this);

			// hover dir
			var overlayEffect 		= $this.data('overlay_effect');
			var hoverdirEasing 		= $this.data('hoverdir_easing');
			var hoverdirSpeed 		= $this.data('hoverdir_speed');
			var hoverdirHoverDelay 	= $this.data('hoverdir_hover_delay');
			var hoverdirInverse 	= $this.data('hoverdir_inverse');

			if (overlayEffect == 'hoverdir') {
				var projectImg = $this.find('.project-img');

				projectImg.each(function() {
					jQuery(this).hoverdir({
						speed : hoverdirSpeed,
						easing : hoverdirEasing,
						hoverDelay : hoverdirHoverDelay,
						inverse : hoverdirInverse,
						hoverElem : 'div'
					});
				});

				$(document).on('afterClose.fb', function( e, instance, slide ) {
					jQuery(".project-img-extra").css({"left": "-100%", "top": "0", "transition": "none"});
				});
			}
		});
	};


	EXTSTORE.projectCarousel = function() {
		$('.portfolio-carousel').each(function() {
			var $this 				= $(this);
			var animation1			= $this.data('animation1');
			var animation2			= $this.data('animation2');
			var gutterWidth 		= $this.data('gutter-width');
			var columns				= $this.data('columns');
			var animation			= '';

			if (columns == 1) {
				animation 	= animation2;
			} else {
				animation 	= animation1;
			}

			var mdWidth = $this.width();
			jQuery('#' + $this.attr('id')).css ({
				"width": mdWidth + "px"
			});

			var swiper = new Swiper('#' + $this.attr('id') + ' .swiper-container', {
				autoHeight: true,
				preloadImages: true,
				slidesPerView: columns,
				spaceBetween: gutterWidth,
				effect: animation,
				autoplay: {
					delay: 3500,
					disableOnInteraction: false
				},
				pagination: {
					el: '.swiper-pagination',
					clickable: true
				},
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev'
				}

			});
		});

	};

	$(document).ready(function() {
		EXTSTORE.isotopeInit();
		EXTSTORE.openGallery();
		EXTSTORE.projectCarousel();
		EXTSTORE.hoverDir();
	});

})(jQuery, window, document);

