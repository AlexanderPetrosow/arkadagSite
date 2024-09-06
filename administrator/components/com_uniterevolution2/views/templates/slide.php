

	<!--  load good font -->

	<?php if($loadGoogleFont == "true"){
			$googleFont = $slider->getParam("google_font","");
			if(!empty($googleFont)){
				if(is_array($googleFont)){
					foreach($googleFont as $key => $font){
						echo RevOperations::getCleanFontImport($font);
					}
				}else{
					echo RevOperations::getCleanFontImport($googleFont);
				}
			}
		}
		if($slide->isStaticSlide()){ //insert sliderid for preview ?>
		<input type="hidden" id="sliderid" value="<?php echo $slider->getID(); ?>" />
		<?php } ?>
	<div class="wrap settings_wrap">
		<div class="clear_both"></div> 
		<div class="title_line structure-change">
			<div id="icon-options-general" class="icon32"></div>
			<div class="view_title"><a href="#"><span class="nm-slide-title">
				<?php _uge('', REVSLIDER_TEXTDOMAIN); echo ' '.$sliderTitle ?>
				<?php
				if($sliderTemplate == "true"){
					_uge("",REVSLIDER_TEXTDOMAIN);
				}else{
					_uge("",REVSLIDER_TEXTDOMAIN);
				}
				?> <?php //echo $slideOrder?> <?php _uge("",REVSLIDER_TEXTDOMAIN); ?> <?php //echo $slideTitle?>	
						</span>
                <i class="fa fa-angle-down" aria-hidden="true"></i>
                <div class="slider-name-box">
                	<form>
	                	<label><?php _uge("Name",REVSLIDER_TEXTDOMAIN);?></label>
	                    <input type="text" value="<?php echo $sliderTitle?>" id="slider_title">
	                    <button type="button" class="rename-slider"><?php _uge("Save",REVSLIDER_TEXTDOMAIN);?></button>
                    </form>
                </div>
                
                </a>
			</div>
				
            
		</div>
        
		<?php require self::getPathTemplate("edit_layers");?>        
        
	</div>
    
    
    
    
	<div class="vert_sap"></div>
	<?php require self::getPathTemplate("dialog_preview_slide");?>
	<?php if($slide->isStaticSlide())
		require self::getPathTemplate("dialog_preview_slider");
	?>
	<!-- FIXED POSITIONED TOOLBOX -->

		<div class="" style="position:fixed;right:-10px;top:100px;z-index:100;">
	</div>

	

	<?php if($slide->isStaticSlide()){ $slideID = $slide->getID(); }
	?>
	<script type="text/javascript">
		var g_messageDeleteSlide = "<?php _uge("Delete this slide?",REVSLIDER_TEXTDOMAIN)?>";
		jQuery(document).ready(function(){
	    	if(!g_revSliderAdmin)
	    		g_revSliderAdmin = new RevSliderAdmin();
		
			g_revSliderAdmin.initEditSlideView(<?php echo $slideID?>,<?php echo $sliderID?>);
		});
	jQuery('.rename-slider').click(function(){
	    var data = {};
	    data.main = {};
	    data.params = {};
        data.params.delay = '<?php echo $slider->getParam("delay"); ?>';
        data.params.shuffle = '<?php echo $slider->getParam("shuffle"); ?>';
        data.params.lazy_load = '<?php echo $slider->getParam("lazy_load"); ?>';
        data.params.enable_static_layers = '<?php echo $slider->getParam("enable_static_layers"); ?>';
        data.params.next_slide_on_window_focus = '<?php echo $slider->getParam("next_slide_on_window_focus"); ?>';
        data.params.stop_slider = '<?php echo $slider->getParam("stop_slider"); ?>';
        data.params.stop_after_loops = '<?php echo $slider->getParam("stop_after_loops"); ?>';
        data.params.stop_at_slide = '<?php echo $slider->getParam("stop_at_slide"); ?>';
        data.params.show_timerbar = '<?php echo $slider->getParam("show_timerbar"); ?>';
        data.params.loop_slide = '<?php echo $slider->getParam("loop_slide"); ?>';
        data.params.position = '<?php echo $slider->getParam("position"); ?>';
        data.params.margin_top = '<?php echo $slider->getParam("margin_top"); ?>';
        data.params.margin_bottom = '<?php echo $slider->getParam("margin_bottom"); ?>';
        data.params.margin_left = '<?php echo $slider->getParam("margin_left"); ?>';
        data.params.margin_right = '<?php echo $slider->getParam("margin_right"); ?>';
        data.params.position_behind = '<?php echo $slider->getParam("position_behind"); ?>';
        data.params.shadow_type = '<?php echo $slider->getParam("shadow_type"); ?>';
        data.params.background_color = '<?php echo $slider->getParam("background_color"); ?>';
        data.params.background_dotted_overlay = '<?php echo $slider->getParam("background_dotted_overlay"); ?>';
        data.params.show_background_image = '<?php echo $slider->getParam("show_background_image"); ?>';
        data.params.background_image = '<?php echo $slider->getParam("background_image"); ?>';
        data.params.bg_fit = '<?php echo $slider->getParam("bg_fit"); ?>';
        data.params.bg_repeat = '<?php echo $slider->getParam("bg_repeat"); ?>';
        data.params.bg_position = '<?php echo $slider->getParam("bg_position"); ?>';
        data.params.stop_on_hover = '<?php echo $slider->getParam("stop_on_hover"); ?>';
        data.params.keyboard_navigation = '<?php echo $slider->getParam("keyboard_navigation"); ?>';
        data.params.navigation_style = '<?php echo $slider->getParam("navigation_style"); ?>';
        data.params.navigaion_type = '<?php echo $slider->getParam("navigaion_type"); ?>';
        data.params.navigaion_always_on = '<?php echo $slider->getParam("navigaion_always_on"); ?>';
        data.params.hide_thumbs = '<?php echo $slider->getParam("hide_thumbs"); ?>';
        data.params.navigaion_align_hor = '<?php echo $slider->getParam("navigaion_align_hor"); ?>';
        data.params.navigaion_align_vert = '<?php echo $slider->getParam("navigaion_align_vert"); ?>';
        data.params.navigaion_offset_hor = '<?php echo $slider->getParam("navigaion_offset_hor"); ?>';
        data.params.navigaion_offset_vert = '<?php echo $slider->getParam("navigaion_offset_vert"); ?>';
        data.params.leftarrow_align_hor = '<?php echo $slider->getParam("leftarrow_align_hor"); ?>';
        data.params.leftarrow_align_vert = '<?php echo $slider->getParam("leftarrow_align_vert"); ?>';
        data.params.leftarrow_offset_hor = '<?php echo $slider->getParam("leftarrow_offset_hor"); ?>';
        data.params.leftarrow_offset_vert = '<?php echo $slider->getParam("leftarrow_offset_vert"); ?>';
        data.params.rightarrow_align_hor = '<?php echo $slider->getParam("rightarrow_align_hor"); ?>';
        data.params.rightarrow_align_vert = '<?php echo $slider->getParam("rightarrow_align_vert"); ?>';
        data.params.rightarrow_offset_hor = '<?php echo $slider->getParam("rightarrow_offset_hor"); ?>';
        data.params.rightarrow_offset_vert = '<?php echo $slider->getParam("rightarrow_offset_vert"); ?>';
        data.params.thumb_width = '<?php echo $slider->getParam("thumb_width"); ?>';
        data.params.thumb_height = '<?php echo $slider->getParam("thumb_height"); ?>';
        data.params.thumb_amount = '<?php echo $slider->getParam("thumb_amount"); ?>';
        data.params.use_spinner = '<?php echo $slider->getParam("use_spinner"); ?>';
        data.params.spinner_color = '<?php echo $slider->getParam("spinner_color"); ?>';
        data.params.use_parallax = '<?php echo $slider->getParam("use_parallax"); ?>';
        data.params.disable_parallax_mobile = '<?php echo $slider->getParam("disable_parallax_mobile"); ?>';
        data.params.parallax_type = '<?php echo $slider->getParam("parallax_type"); ?>';
        data.params.parallax_bg_freeze = '<?php echo $slider->getParam("parallax_bg_freeze"); ?>';
        data.params.parallax_level_1 = '<?php echo $slider->getParam("parallax_level_1"); ?>';
        data.params.parallax_level_2 = '<?php echo $slider->getParam("parallax_level_2"); ?>';
        data.params.parallax_level_3 = '<?php echo $slider->getParam("parallax_level_3"); ?>';
        data.params.parallax_level_4 = '<?php echo $slider->getParam("parallax_level_4"); ?>';
        data.params.parallax_level_5 = '<?php echo $slider->getParam("parallax_level_5"); ?>';
        data.params.parallax_level_6 = '<?php echo $slider->getParam("parallax_level_6"); ?>';
        data.params.parallax_level_7 = '<?php echo $slider->getParam("parallax_level_7"); ?>';
        data.params.parallax_level_8 = '<?php echo $slider->getParam("parallax_level_8"); ?>';
        data.params.parallax_level_9 = '<?php echo $slider->getParam("parallax_level_9"); ?>';
        data.params.touchenabled = '<?php echo $slider->getParam("touchenabled"); ?>';
        data.params.swipe_velocity = '<?php echo $slider->getParam("swipe_velocity"); ?>';
        data.params.swipe_min_touches = '<?php echo $slider->getParam("swipe_min_touches"); ?>';
        data.params.drag_block_vertical = '<?php echo $slider->getParam("drag_block_vertical"); ?>';
        data.params.disable_on_mobile = '<?php echo $slider->getParam("disable_on_mobile"); ?>';
        data.params.disable_kenburns_on_mobile = '<?php echo $slider->getParam("disable_kenburns_on_mobile"); ?>';
        data.params.hide_slider_under = '<?php echo $slider->getParam("hide_slider_under"); ?>';
        data.params.disable_kenburns_on_mobile = '<?php echo $slider->getParam("disable_kenburns_on_mobile"); ?>';
        data.params.hide_defined_layers_under = '<?php echo $slider->getParam("hide_defined_layers_under"); ?>';
        data.params.hide_all_layers_under = '<?php echo $slider->getParam("hide_all_layers_under"); ?>';
        data.params.hide_arrows_on_mobile = '<?php echo $slider->getParam("hide_arrows_on_mobile"); ?>';
        data.params.hide_thumbs_under_resolution = '<?php echo $slider->getParam("hide_thumbs_under_resolution"); ?>';
        data.params.hide_thumbs_delay_mobile = '<?php echo $slider->getParam("hide_thumbs_delay_mobile"); ?>';
        data.params.start_with_slide = '<?php echo $slider->getParam("start_with_slide"); ?>';
        data.params.first_transition_active = '<?php echo $slider->getParam("first_transition_active"); ?>';
        data.params.first_transition_type = '<?php echo $slider->getParam("first_transition_type"); ?>';
        data.params.first_transition_duration = '<?php echo $slider->getParam("first_transition_duration"); ?>';
        data.params.first_transition_slot_amount = '<?php echo $slider->getParam("first_transition_slot_amount"); ?>';
        data.params.simplify_ie8_ios4 = '<?php echo $slider->getParam("simplify_ie8_ios4"); ?>';
        data.params.show_alternative_type = '<?php echo $slider->getParam("show_alternative_type"); ?>';
        data.params.show_alternate_image = '<?php echo $slider->getParam("show_alternate_image"); ?>';
        data.params.reset_transitions = '<?php echo $slider->getParam("reset_transitions"); ?>';
        data.params.reset_transition_duration = '<?php echo $slider->getParam("reset_transition_duration"); ?>';
        data.params.load_googlefont = '<?php echo $slider->getParam("load_googlefont"); ?>';
        data.main.title = jQuery('#slider_title').val();
        data.sliderid = <?php echo $sliderID; ?>;
        data.main.alias = '<?php echo $slider->getParam("alias"); ?>';
        data.main.shortcode = '<?php echo $slider->getParam("shortcode"); ?>';
        data.main.source_type = '<?php echo $slider->getParam("source_type"); ?>';
        data.main.post_types = '<?php echo $slider->getParam("post_types"); ?>';
        data.main.post_category = '<?php echo $slider->getParam("post_category"); ?>';
        data.main.post_sortby = '<?php echo $slider->getParam("post_sortby"); ?>';
        data.main.posts_sort_direction = '<?php echo $slider->getParam("posts_sort_direction"); ?>';
        data.main.max_slider_posts = '<?php echo $slider->getParam("max_slider_posts"); ?>';
        data.main.excerpt_limit = '<?php echo $slider->getParam("excerpt_limit"); ?>';
        data.main.slider_template_id = '<?php echo $slider->getParam("slider_template_id"); ?>';
        data.main.posts_list = '<?php echo $slider->getParam("posts_list"); ?>';
        data.main.slider_type = '<?php echo $slider->getParam("slider_type"); ?>';
        data.main.fullscreen_offset_container = '<?php echo $slider->getParam("fullscreen_offset_container"); ?>';
        data.main.fullscreen_offset_size = '<?php echo $slider->getParam("fullscreen_offset_size"); ?>';
        data.main.fullscreen_min_height = '<?php echo $slider->getParam("fullscreen_min_height"); ?>';
        data.main.full_screen_align_force = '<?php echo $slider->getParam("full_screen_align_force"); ?>';
        data.main.auto_height = '<?php echo $slider->getParam("auto_height"); ?>';
        data.main.force_full_width = '<?php echo $slider->getParam("force_full_width"); ?>';
        data.main.min_height = '<?php echo $slider->getParam("min_height"); ?>';
        data.main.width = '<?php echo $slider->getParam("width"); ?>';
        data.main.height = '<?php echo $slider->getParam("height"); ?>';
        data.main.responsitive_w1 = '<?php echo $slider->getParam("responsitive_w1"); ?>';
        data.main.responsitive_sw1 = '<?php echo $slider->getParam("responsitive_sw1"); ?>';
        data.main.responsitive_w2 = '<?php echo $slider->getParam("responsitive_w2"); ?>';
        data.main.responsitive_sw2 = '<?php echo $slider->getParam("responsitive_sw2"); ?>';
        data.main.responsitive_w3 = '<?php echo $slider->getParam("responsitive_w3"); ?>';
        data.main.responsitive_sw3 = '<?php echo $slider->getParam("responsitive_sw3"); ?>';
        data.main.responsitive_w4 = '<?php echo $slider->getParam("responsitive_w4"); ?>';
        data.main.responsitive_sw4 = '<?php echo $slider->getParam("responsitive_sw4"); ?>';
        data.main.responsitive_w5 = '<?php echo $slider->getParam("responsitive_w5"); ?>';
        data.main.responsitive_sw5 = '<?php echo $slider->getParam("responsitive_sw5"); ?>';
        data.main.responsitive_w6 = '<?php echo $slider->getParam("responsitive_w6"); ?>';
        data.main.responsitive_sw6 = '<?php echo $slider->getParam("responsitive_sw6"); ?>';
        data.template = '<?php echo $slider->getParam("template"); ?>';
        UniteAdminRev.ajaxRequest("update_slider" , data);
		 jQuery('span.nm-slide-title').text(jQuery('#slider_title').val());
	});
	</script>





