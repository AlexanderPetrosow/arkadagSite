/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if (!ExtStore) {
	var ExtStore = {};
}

ExtStore.AdvPortfolioPro = {
	live_site: 		'',

	/**
	 * Image.
	 */
	image: {
		count:			0,
		initialized:	false,

		/**
		 * Initialize.
		 */
		init: function() {
			if (ExtStore.AdvPortfolioPro.image.initialized) {
				return;
			}

			ExtStore.AdvPortfolioPro.image.initialized = true;

			jQuery('.image-clear').click(function() {
				ExtStore.AdvPortfolioPro.image.clear(this);
			});
		},

		/**
		 * Select image from modal.
		 */
		select: function(id, imageName, preview, keepModal) {
			if (!keepModal) {
				parent.jQuery.fancybox.close();
			}


			$el	= jQuery('#' + id)
			if ($el.val() != imageName) {
				if ($el.val() == '' && $el.parents('#jform_images_container').length) {
					ExtStore.AdvPortfolioPro.image.add();
				}

				$el.val(imageName);
				$el.siblings('.image-preview').hide().html('<img src="' + preview + '" alt="' + imageName + '" class="img-polaroid" />').show('slide');
			}
		},

		/**
		 * Select image of blank field.
		 */
		selectBlank: function(imageName, preview) {
			var id	= jQuery('#jform_images_container .image-input').filter(function() {
				return !this.value;
			}).first().attr('id');

			ExtStore.AdvPortfolioPro.image.select(id, imageName, preview, true);
		},

		initList: function() {
			ExtStore.AdvPortfolioPro.image.init();
			ExtStore.AdvPortfolioPro.image.add();

			jQuery('.images-container').sortable({
				'handle':	'.image-sortable'
			});
		},

		refresh: function() {
			jQuery('#jform_images_container .image-clear').unbind('click').click(function() {
				if (jQuery('#jform_images_container > li').length > 1 && jQuery(this).siblings('.image-input').val() != '') {
					jQuery(this).parents('li').hide('slide', function() {
						jQuery(this).remove();
					});
				} else {
					ExtStore.AdvPortfolioPro.image.clear(this);
				}
			});
		},

		/**
		 * Add an image.
		 */
		add: function() {
			var id = ExtStore.AdvPortfolioPro.image.count++;

			jQuery('<li><div class="image-sortable"></div><div class="image-container"><input type="hidden" id="jform_images_' + id + '_image" name="jform[images][image][]" class="image-input" /><a href="javascript:;" data-src="index.php?option=com_advportfoliopro&amp;view=imagehandler&amp;tmpl=component&amp;image_id=jform_images_' + id + '_image" data-fancybox data-type="iframe" class="exmodal image-select btn btn-secondary"><i class="icon-pictures"></i> Select</a> <a href="javascript:void(0);" class="btn btn-secondary image-clear"><i class="icon-remove"></i> Clear</a><div class="image-title"><input type="text" name="jform[images][title][]" placeholder="Image Title" /></div><div class="image-preview" style="display:none;"></div></div></li>').appendTo('#jform_images_container').hide().show('slide');
			ExtStore.AdvPortfolioPro.image.refresh();
		},

		/**
		 * Clear image.
		 */
		clear: function(id) {
			$id = jQuery(id);
			$id.siblings('.image-input').val('');
			$id.siblings('.image-title').children('input').val('');
			$id.siblings('.image-preview').hide('slide', function() {
				jQuery(this).html('');
			});
		}
	}
};