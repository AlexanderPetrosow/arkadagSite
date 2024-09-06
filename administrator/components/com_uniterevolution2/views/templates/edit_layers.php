<div class="edit_slide_wrapper<?php echo ($slide->isStaticSlide()) ? ' rev_static_layers' : ''; ?>">
    <div class="slider-main-outer">
        <div class="slider-main-left-top">
            <div class="editor_buttons_wrapper  postbox unite-postbox" style="max-width:100% !important;">
                <div style="width:100%; border-top:1px solid #f1f1f1; margin-top:0px;"></div>
                <div class="toggled-content">
                    <div class="inner_wrapper p10 pb0 pt0 boxsized">
                        <?php
                        //show/hide layers of specific slides
                        $add_static = 'false';
                        if ($slide->isStaticSlide()) {
                            $add_static = 'true';
                        }
                        ?>
                        <div class="editor_buttons_wrapper_bottom">
                            <div style="float:left" class="add-layer-btn">
                                <a href="javascript:void(0)" data-isstatic="<?php echo $add_static; ?>"
                                   class="button-primary revblue">
                                    <em class="icon-pic"><i class="fa fa-plus-square-o"></i> <i
                                            class="fa fa-caret-down"></i></em>
                                    <?php _uge("Add Layer", REVSLIDER_TEXTDOMAIN) ?>
                                </a>
                                <div class="add-layer-inner">
                                    <a href="javascript:void(0)" id="button_add_layer"
                                       data-isstatic="<?php echo $add_static; ?>" class="button-primary revblue"><i
                                            class="fa fa-font"></i>
                                        <?php _uge("Text Layer", REVSLIDER_TEXTDOMAIN) ?>
                                    </a>
                                    <a href="javascript:void(0)" id="button_add_layer_video"
                                       data-isstatic="<?php echo $add_static; ?>" class="button-primary revblue"><i
                                            class="fa fa-video-camera"></i>
                                        <?php _uge("Video layer", REVSLIDER_TEXTDOMAIN) ?>
                                    </a>
                                    <a href="javascript:void(0)" id="button_add_layer_image"
                                       data-isstatic="<?php echo $add_static; ?>" class="button-primary revblue"><i
                                            class="fa fa-camera"></i>
                                        <?php _uge("Photo layer", REVSLIDER_TEXTDOMAIN) ?>
                                    </a>
                                </div>
                            </div>
                            <div style="float:left" class="add-layer-btn">
                                <a id="button_close_slide" href="<?php echo $closeUrl ?>"
                                   class="button-primary revblue">
                                    <em class="icon-pic-two"><i class="fa fa-bars" aria-hidden="true"></i></em>
                                    <?php _uge("Slides List", REVSLIDER_TEXTDOMAIN) ?></a>
                            </div>
                            <div style="float:left" class="add-layer-btn">
                                <a id="gride_option_slider" href="javascript:void(0)" class="button-primary revblue">
                                    <em class="icon-pic-two"><i class="fa fa-hashtag"></i> <i
                                            class="fa fa-caret-down"></i></em><?php _uge("Grid options", REVSLIDER_TEXTDOMAIN) ?>
                                </a>
                                <div class="add-layer-inner">
                                    <div class="layer-editor-toolbar"> <span class="setting_text_3">
					  <?php _uge("Helper Grid:", REVSLIDER_TEXTDOMAIN) ?>
					  </span>
                                        <select name="rs-grid-sizes" id="rs-grid-sizes">
                                            <option value="1">
                                                <?php _uge("Disabled", REVSLIDER_TEXTDOMAIN) ?>
                                            </option>
                                            <option value="5">5 x 5</option>
                                            <option value="10">10 x 10</option>
                                            <option value="25">25 x 25</option>
                                            <option value="50">50 x 50</option>
                                        </select>
					  <span class="setting_text_3">
					  <?php _uge("Snap to:", REVSLIDER_TEXTDOMAIN) ?>
					  </span>
                                        <select name="rs-grid-snapto" id="rs-grid-snapto">
                                            <option value="">
                                                <?php _uge("None", REVSLIDER_TEXTDOMAIN) ?>
                                            </option>
                                            <option value=".helplines">
                                                <?php _uge("Help Lines", REVSLIDER_TEXTDOMAIN) ?>
                                            </option>
                                            <option value=".slide_layer">
                                                <?php _uge("Layers", REVSLIDER_TEXTDOMAIN) ?>
                                            </option>
                                        </select>
                                        <?php
                                        //show/hide layers of specific slides
                                        if ($slide->isStaticSlide()) {
                                            $all_slides = $slider->getSlides(true);
                                            ?>
                                            <span class="setting_text_3">
					  <?php _uge("Show Layers from Slide:", REVSLIDER_TEXTDOMAIN) ?>
					  </span>
                                            <select name="rev_show_the_slides">
                                                <option value="none">---</option>
                                                <?php
                                                foreach ($all_slides as $c_slide) {

                                                    $c_params = $c_slide->getParams();
                                                    ?>
                                                    <option
                                                        value="<?php echo $c_slide->getID(); ?>"><?php echo UniteFunctionsRev::getVal($c_params, "title", 'Slide') . ' (ID: ' . $c_slide->getID() . ')'; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="editor_buttons_right">

                                <div class="add-layer-btn right-btn">
                                    <a href="<?php echo GlobalsRevSlider::LINK_HELP_SLIDE ?>"
                                       class="button-primary revblue" target="_blank">
                                        <em class="icon-pic-two"><i class="fa fa-question-circle-o"></i></em>
                                        <?php _uge("Help", REVSLIDER_TEXTDOMAIN) ?></a>
                                </div>

                                <div class="add-layer-btn right-btn">
                                    <a href="<?php echo self::getViewUrl(RevSliderAdmin::VIEW_SLIDER, "id=$sliderID"); ?>"
                                       class="button-primary revblue">
                                        <em class="icon-pic-two"><i class="fa fa-cog"></i></em>
                                        <?php _uge("Settings", REVSLIDER_TEXTDOMAIN) ?></a>
                                </div>
                                <div class="add-layer-btn right-btn">
                                    <?php if (!$slide->isStaticSlide()) { ?>
                                        <a href="javascript:void(0)" id="button_preview_slide-tb"
                                           class="button-primary revblue"><em class="icon-pic-two"><i
                                                    class="fa fa-eye"></i></em>
                                            <?php _uge("Preview", REVSLIDER_TEXTDOMAIN) ?>
                                        </a>
                                    <?php } else { ?>
                                    	<a href="javascript:void(0)" id="button_preview_slide-tb"
                                           class="button-primary revblue"><em class="icon-pic-two"><i
                                                    class="fa fa-eye"></i></em>
                                            <?php _uge("Preview", REVSLIDER_TEXTDOMAIN) ?>
                                        </a>
                                        <!--<a href="javascript:void(0)" id="button_preview_slider-tb"
                                           class="button-primary button-fixed revbluedark">
                                            <div style="font-size:16px; padding:10px 5px;"
                                                 class="revicon-search-1"></div>
                                        </a>-->
                                    <?php } ?>

                                    <?php if (!$slide->isStaticSlide()) { ?>
                                        <!--<a href="javascript:void(0)" id="button_save_slide-tb" class="revgreen button-primary button-fixed"><div style="font-size:16px; padding:10px 5px;" class="revicon-arrows-ccw"></div></a>-->
                                    <?php } else { ?>
                                        <!--<a href="javascript:void(0)" id="button_save_static_slide-tb" class="revgreen button-primary button-fixed"><div style="font-size:16px; padding:10px 5px;" class="revicon-arrows-ccw"></div></a>-->
                                    <?php } ?>
                                </div>

                                <div class="add-layer-btn right-btn">
                                    <?php if (!$slide->isStaticSlide()) { ?>
                                        <a href="javascript:void(0)" id="button_save_slide"
                                           class="revblue button-primary">
                                            <em class="icon-pic-two"><em class="icon-pic-green"><i
                                                        class="fa fa-check-square"></i></em></em>
                                            <?php _uge("Save", REVSLIDER_TEXTDOMAIN) ?></a>
                                    <?php } else { ?>
                                    	<a href="javascript:void(0)" id="button_save_slide"
                                           class="revblue button-primary">
                                            <em class="icon-pic-two"><em class="icon-pic-green"><i
                                                        class="fa fa-check-square"></i></em></em>
                                            <?php _uge("Save", REVSLIDER_TEXTDOMAIN) ?></a>
                                        <!--<a href="javascript:void(0)" id="button_save_static_slide"
                                           class="revgreen button-primary">
                                            <div
                                                class="updateicon"></div><?php //_uge("Update Static Layers", REVSLIDER_TEXTDOMAIN) ?>
                                        </a>-->
                                    <?php } ?>
                                    <span id="loader_update" class="loader_round"
                                          style="display:none;"><?php _uge("updating", REVSLIDER_TEXTDOMAIN) ?>
                                        ...</span>
                                    <span id="update_slide_success" class="success_message"
                                          class="display:none;"></span>
                                    <?php if ($permission_setting): // permission   ?>
                                </div>

                                <?php endif; // end permission ?>

                                <?php /*?> <?php if($permission): // permission ?>
                    <?php if(!$slide->isStaticSlide()){ ?>
                    <a href="javascript:void(0)" id="button_delete_slide" class="button-primary revred" original-title=""><i class="revicon-trash"></i><?php _uge("",REVSLIDER_TEXTDOMAIN)?></a>
                    <?php } ?>
                    <?php endif; // end permission ?>  <?php */ ?>
                                <?php /*?><a href="javascript:void(0)" id="button_delete_layer" class="button-primary  revred button-disabled"><i class="revicon-trash"></i><?php _uge("Delete Layer",REVSLIDER_TEXTDOMAIN)?> </a><?php */ ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div style="float:right;">
                            <?php /*?><a href="javascript:void(0)" id="button_delete_all" class="button-primary  revred button-disabled"><i class="revicon-trash"></i> <?php _uge("Delete All Layers",REVSLIDER_TEXTDOMAIN)?> </a><?php */ ?>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div class="slider-main-left">
            <div id="slide_selector" class="slide_selector">
                <?php
                $sID = $slider->getID();
                $useStaticLayers = $slider->getParam("enable_static_layers", "off");
                if ($useStaticLayers == 'on') { ?>
                    <ul class="list_static_slide_links">
                        <li class="revgray nowrap">
                            <a href="<?php echo self::getViewUrl(RevSliderAdmin::VIEW_SLIDE, "id=static_$sID"); ?>"
                               class="add_slide"><i
                                    class="eg-icon-dribbble"></i><span><?php _uge("Static / Global Layers", REVSLIDER_TEXTDOMAIN) ?></span></a>
                        </li>
                    </ul>
                <?php } ?>
                <ul class="list_slide_links">
                    <?php
                    foreach ($arrSlideNames as $slidelistID => $c_slide):
                        $slideName = $c_slide["name"];
                        $title = $c_slide["title"];
                        $arrChildrenIDs = $c_slide["arrChildrenIDs"];
                        $class = "tipsy_enabled_top";
                        $titleclass = "";
                        $urlEditSlide = self::getViewUrl(RevSliderAdmin::VIEW_SLIDE, "id=$slidelistID");
                        if ($slideID == $slidelistID || in_array($slideID, $arrChildrenIDs)) {
                            $class .= " selected";
                            $titleclass = " slide_title";
                            $urlEditSlide = "javascript:void(0)";
                        }
                        $addParams = "class='" . $class . "'";
                        $slideName = str_replace("'", "", $slideName);
                        ?>
                        <li id="slidelist_item_<?php echo $slidelistID ?>">
                            <a href="<?php echo $urlEditSlide ?>"
                               title='<?php echo $slideName ?>' <?php echo $addParams ?>>
                                <span class="nowrap<?php echo $titleclass ?> delete-btn"><?php echo $title ?></span>
                                <span id="button_delete_slide" class="delete-slide-this"><i class="fa fa-trash-o"
                                                                                            aria-hidden="true"></i></span>
                            </a>

                        </li>
						
                    <?php endforeach; ?>
                    <?php if ($permission): // permission ?>
                        <li>
                            <a id="link_add_slide" href="javascript:void(0);" class="add_slide"><span
                                    class="nowrap"><?php _uge("+ Add Slide", REVSLIDER_TEXTDOMAIN) ?></span></a>
                        </li>
                    <?php endif; //end permission ?>

                    <div id="loader_add_slide" class="loader_round" style="display:none"></div>

                </ul>
            </div>


            <div class="editor_buttons_wrapper  postbox unite-postbox"
                 style="max-width:100% !important;">
                <h3 class="box-closed tp-accordion"
                    style="background:#fff !important; display: none !important;"> <span>
          <?php if ($slide->isStaticSlide()) {
              _uge("Static / Global Layers", REVSLIDER_TEXTDOMAIN);
          } else { ?>
              <?php _uge("Slide", REVSLIDER_TEXTDOMAIN);
          } ?>
          </span></h3>

                <div class="layer-editor-toolbar" style="display: none;"> <span class="setting_text_3">
          <?php _uge("Helper Grid:", REVSLIDER_TEXTDOMAIN) ?>
          </span>
                    <select name="rs-grid-sizes" id="rs-grid-sizes">
                        <option value="1">
                            <?php _uge("Disabled", REVSLIDER_TEXTDOMAIN) ?>
                        </option>
                        <option value="5">5 x 5</option>
                        <option value="10">10 x 10</option>
                        <option value="25">25 x 25</option>
                        <option value="50">50 x 50</option>
                    </select>
          <span class="setting_text_3">
          <?php _uge("Snap to:", REVSLIDER_TEXTDOMAIN) ?>
          </span>
                    <select name="rs-grid-snapto" id="rs-grid-snapto">
                        <option value="">
                            <?php _uge("None", REVSLIDER_TEXTDOMAIN) ?>
                        </option>
                        <option value=".helplines">
                            <?php _uge("Help Lines", REVSLIDER_TEXTDOMAIN) ?>
                        </option>
                        <option value=".slide_layer">
                            <?php _uge("Layers", REVSLIDER_TEXTDOMAIN) ?>
                        </option>
                    </select>
                    <?php
                    //show/hide layers of specific slides
                    if ($slide->isStaticSlide()) {
                        $all_slides = $slider->getSlides(true);
                        ?>
                        <span class="setting_text_3">
          <?php _uge("Show Layers from Slide:", REVSLIDER_TEXTDOMAIN) ?>
          </span>
                        <select name="rev_show_the_slides">
                            <option value="none">---</option>
                            <?php
                            foreach ($all_slides as $c_slide) {

                                $c_params = $c_slide->getParams();
                                ?>
                                <option
                                    value="<?php echo $c_slide->getID(); ?>"><?php echo UniteFunctionsRev::getVal($c_params, "title", 'Slide') . ' (ID: ' . $c_slide->getID() . ')'; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <?php
                    }
                    ?>
                </div>


                <div class="browser_scene"></div>
                <div id="divLayers-wrapper" class="slide_wrap_layers <?php echo $sliderType ?>">
                    <div id="divbgholder" style="<?php echo $style_wrapper . $divbgminwidth; ?>"
                         class="<?php echo $class_wrapper; ?>">
                        <div id="divLayers" class="<?php echo $divLayersClass ?>"
                             style="<?php echo $style . $divLayersWidth; ?>"></div>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="layer_props_wrapper openeed">
                <div class="edit_layers_right" style="position:relative;height:100%">
                    <div class="postbox unite-postbox layer_sortbox" style="height:100%;">

                        <h3 class="no-accordion"> <span>
              <?php _uge("Layers Timing & Sorting", REVSLIDER_TEXTDOMAIN) ?>
              </span></h3>

                        <div class="left-last-div" style="display:table; line-height:30px">
                            <div style="display:table-cell; padding:0px 10px; min-width:111px; color:#919191;">
                                <?php _uge("z-Index", REVSLIDER_TEXTDOMAIN) ?>
                            </div>
                            <div style="display:none; min-width:78px">
                                <div id="button_sort_visibility" class="tipsy_enabled_top"
                                     title="<?php _uge('Hide All Layers', REVSLIDER_TEXTDOMAIN) ?>"><i
                                        class="eg-icon-eye"></i><i class="eg-icon-eye-off"></i></div>
                                <div id="button_sort_lock" class="tipsy_enabled_top"
                                     title="<?php _uge('Lock All Layers', REVSLIDER_TEXTDOMAIN) ?>"><i
                                        class="eg-icon-lock-open"></i><i class="eg-icon-lock"></i></div>
                                <div id="button_sort_tillend" class="tipsy_enabled_top"
                                     title="<?php _uge('Snap to Slide End / Custom End', REVSLIDER_TEXTDOMAIN) ?>"><i
                                        class="eg-icon-back-in-time"></i></div>
                            </div>
                            <div style="display:table-cell;padding:0px 10px;width:100%; color:#919191;">
                                <?php _uge("Timing", REVSLIDER_TEXTDOMAIN) ?>
                                <div id="button_sort_timing" class="button-primary revblue"
                                     style="margin-left:15px !important"
                                     title="<?php _uge('Show / Hide Timer Settings', REVSLIDER_TEXTDOMAIN) ?>"><i
                                        class="eg-icon-equalizer"></i> <span class="onoff"> - on</span></div>
                            </div>
                            <div class="start"
                                 style="display:table-cell;padding:0px; text-align: center; min-width:72px;"><?php _uge("Start", REVSLIDER_TEXTDOMAIN) ?></div>
                            <div class="end"
                                 style="display:table-cell;padding:0px; text-align: center; min-width:72px;"><?php _uge("Stop", REVSLIDER_TEXTDOMAIN) ?></div>
                            <div
                                style="display:table-cell;padding:0px; text-align: center; min-width:76px; background-color: #cacaca; color: #fff;">
                                <a href="javascript:void(0)" id="button_delete_all"
                                   style="color: #fff;text-decoration: none !important;"><?php _uge("Clear All", REVSLIDER_TEXTDOMAIN) ?> </a>
                            </div>
                        </div>
                        <div class="inside">
                            <ul id="sortlist" class='sortlist'>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <!----- End Left Layers Form ------>
                <div class="clear"></div>
            </div>
            <div id="dialog_insert_button" class="dialog_insert_button" title="Insert Button" style="display:none;">
                <p>
                <ul class="list-buttons">
                    <?php foreach ($arrButtonClasses as $class => $text): ?>
                        <li>
                            <a href="javascript:g_objLayers.insertButton('<?php echo $class ?>','<?php echo $text ?>')"
                               class="tp-button <?php echo $class ?> small"><?php echo $text ?></a></li>
                    <?php endforeach; ?>
                </ul>
                </p>
            </div>
            <div id="dialog_template_insert" class="dialog_template_help"
                 title="<?php _uge("Template Insertions", REVSLIDER_TEXTDOMAIN) ?>" style="display:none;"><b>
                    <?php _uge("Post Replace Placeholders:", REVSLIDER_TEXTDOMAIN) ?>
                </b>
                <table class="table_template_help">
                    <tr>
                        <td>
                            <a href="javascript:g_objLayers.insertTemplate('meta:somemegatag')">%meta:somemegatag%</a>
                        </td>
                        <td><?php _uge("Any custom meta tag", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('title')">%title%</a></td>
                        <td><?php _uge("Post Title", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('excerpt')">%excerpt%</a></td>
                        <td><?php _uge("Post Excerpt", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('alias')">%alias%</a></td>
                        <td><?php _uge("Post Alias", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('content')">%content%</a></td>
                        <td><?php _uge("Post content", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('link')">%link%</a></td>
                        <td><?php _uge("The link to the post", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('date')">%date%</a></td>
                        <td><?php _uge("Date created", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('date_modified')">%date_modified%</a></td>
                        <td><?php _uge("Date modified", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('author_name')">%author_name%</a></td>
                        <td><?php _uge("Author name", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('num_comments')">%num_comments%</a></td>
                        <td><?php _uge("Number of comments", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('catlist')">%catlist%</a></td>
                        <td><?php _uge("List of categories with links", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:g_objLayers.insertTemplate('taglist')">%taglist%</a></td>
                        <td><?php _uge("List of tags with links", REVSLIDER_TEXTDOMAIN) ?></td>
                    </tr>
                </table>
                <?php if (UniteEmRev::isEventsExists()): ?>
                    <br>
                    <br>
                    <b>
                        <?php _uge("Events Placeholders:", REVSLIDER_TEXTDOMAIN) ?>
                    </b>
                    <table class="table_template_help">
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_start_date')">%event_start_date%</a>
                            </td>
                            <td><?php _uge("Event start date", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:g_objLayers.insertTemplate('event_end_date)">'%event_end_date%</a>
                            </td>
                            <td><?php _uge("Event end date", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_start_time')">%event_start_time%</a>
                            </td>
                            <td><?php _uge("Event start time", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:g_objLayers.insertTemplate('event_end_time)">'%event_end_time%</a>
                            </td>
                            <td><?php _uge("Event end time", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td>
                                <a href="javascript:g_objLayers.insertTemplate('event_event_id')">%event_event_id%</a>
                            </td>
                            <td><?php _uge("Event ID", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_name')">%event_location_name%</a>
                            </td>
                            <td><?php _uge("Event location name", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_slug%')">%event_location_slug%</a>
                            </td>
                            <td><?php _uge("Event location slug", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_address)">%event_location_address%</a>
                            </td>
                            <td><?php _uge("Event location address", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_town')">%event_location_town%</a>
                            </td>
                            <td><?php _uge("Event location town", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_state')">%event_location_state%</a>
                            </td>
                            <td><?php _uge("Event location state", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_postcode')">%event_location_postcode%</a>
                            </td>
                            <td><?php _uge("Event location postcode", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_region')">%event_location_region%</a>
                            </td>
                            <td><?php _uge("Event location region", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:g_objLayers.insertTemplate('event_location_country')">%event_location_country%</a>
                            </td>
                            <td><?php _uge("Event location country", REVSLIDER_TEXTDOMAIN) ?></td>
                        </tr>
                    </table>
                <?php endif ?>
            </div>
        </div>
        <div class="slider-main-right">
            <div class="clear"></div>
            <!--<hr class="tabdivider">-->
            <?php if ($wpmlActive == true && count($arrChildLangs) > 1): ?>
                <div class="clear"></div>
                <div class="divide220"></div>
                <div class="slide_langs_selector">
                    <span class="float_left ptop_15"> <?php _uge("Choose slide language", REVSLIDER_TEXTDOMAIN) ?>
                        : </span>
                    <ul class="list_slide_view_icons float_left">
                        <?php foreach ($arrChildLangs as $arrLang):
                            $childSlideID = $arrLang["slideid"];
                            $lang = $arrLang["lang"];
                            $urlFlag = UniteWpmlRev::getFlagUrl($lang);
                            $langTitle = UniteWpmlRev::getLangTitle($lang);
                            $class = "";
                            $urlEditSlide = self::getViewUrl(RevSliderAdmin::VIEW_SLIDE, "id=$childSlideID");
                            if ($childSlideID == $slideID) {
                                $class = "lang-selected";
                                $urlEditSlide = "javascript:void(0)";
                            }
                            ?>
                            <li>
                                <a href="<?php echo $urlEditSlide ?>" class="tipsy_enabled_top <?php echo $class ?>"
                                   title="<?php echo $langTitle ?>">
                                    <img class="icon_slide_lang" src="<?php echo $urlFlag ?>">
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                    <span
                        class="float_left ptop_15 pleft_20"> <?php _uge("All the language related operations are from", REVSLIDER_TEXTDOMAIN) ?>
                        <a href="<?php echo $closeUrl ?>"><?php _uge("slides view", REVSLIDER_TEXTDOMAIN) ?></a>. </span>
                </div>
                <div class="clear"></div>
            <?php else: ?>
                <div class="divide220"></div>
            <?php endif ?>
            <div id="general_accordion" class="slide_params_holder">
            <?php if (!$slide->isStaticSlide()) { ?>
                <div id="slide_params_holder" class="postbox unite-postbox blue-bg">
                    <h3 class="box-closed tp-accordion"><span class="postbox-arrow2"><i
                                class="fa fa-caret-down"
                                aria-hidden="true"></i></span><span><?php _uge("Slide Settings", REVSLIDER_TEXTDOMAIN) ?></span>
                    </h3>
                    <div class="toggled-content tp-closeifotheropen">
                        <form name="form_slide_params" id="form_slide_params">
                            <?php $settingsSlideOutput->draw("form_slide_params", false); ?>
                            <input type="hidden" id="image_url" name="image_url" value="<?php echo $imageUrl ?>"/>
                            <input type="hidden" id="image_id" name="image_id" value="<?php echo $imageID ?>"/>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div id="jqueryui_error_message" class="unite_error_message" style="display:none;">

                <b>Warning!!! </b>The jquery ui javascript include that is loaded by some of the plugins are custom made
                and not contain needed components like 'autocomplete' or 'draggable' function.

                Without those functions the editor may not work correctly. Please remove those custom jquery ui includes
                in order the editor will work correctly.

            </div>
            

            <?php if (!$slide->isStaticSlide()) { ?>
                <div id="slide_main_img_bg" class="slide_params_holdereditor_buttons_wrapper postbox unite-postbox blue-bg">
                    <h3 class="box-closed tp-accordion tpa-closed"><span class="postbox-arrow2"><i
                                class="fa fa-caret-down" aria-hidden="true"></i></span><span>
			          <?php _uge("Slide Background", REVSLIDER_TEXTDOMAIN) ?>
			          </span></h3>
                    <div class="toggled-content tp-closedatstart tp-closeifotheropen">
                        <div class="inner_wrapper p10 pb0 pt0 boxsized">
                            <div class="editor_buttons_wrapper_top">
                                
                                <div class="form-table">
                                	<div class="bg-title">
                                		<div class="bg-title-inner">
                                    	<?php _uge("Background Source:", REVSLIDER_TEXTDOMAIN) ?>                                		
                                		</div>											
                                        	<select id="select_bg_source">
                                                <!-- IMAGE FROM MEDIAGALLERY -->
                                                <option class="bgsrcchanger" data-callid="tp-bgimagewpsrc" data-imgsettings="on"
                                                        data-bgtype="image"
                                                        id="radio_back_image" <?php if ($bgType == "image") echo 'selected' ?>>                
                                                        <?php _uge("Image BG", REVSLIDER_TEXTDOMAIN) ?>
                                                </option>
                                                <!-- IMAGE FROM EXTERNAL -->
                                                <option data-callid="tp-bgimageextsrc" data-imgsettings="on" class="bgsrcchanger"
                                                        data-bgtype="external"
                                                        id="radio_back_external" <?php if ($bgType == "external") echo 'selected' ?>>                      
                                                        <?php _uge("External URL", REVSLIDER_TEXTDOMAIN) ?>
                                                </option>
                                                <!-- TRANSPARENT BACKGROUND -->
                                                <option data-callid="" class="bgsrcchanger" data-bgtype="trans"
                                                        id="radio_back_trans" <?php if ($bgType == "trans") echo 'selected' ?>>                              
                                                        <?php _uge("Transparent", REVSLIDER_TEXTDOMAIN) ?>
            
                                                </option>
                                                <!-- COLORED BACKGROUND -->
                                                <option data-callid="tp-bgcolorsrc" class="bgsrcchanger" data-bgtype="solid"
                                                        id="radio_back_solid" <?php if ($bgType == "solid") echo 'selected' ?>>                              
                                                        <?php _uge("Solid Colored", REVSLIDER_TEXTDOMAIN) ?>
            
                                                </option>
                                            </select>
                                		<div>
                                	</div>
                                </div>
                            </div>
                                
                                <!-- THE BG IMAGE CHANGED DIV -->
                                <div id="tp-bgimagewpsrc" class="bgsrcchanger-div">
                                	<div class="choose-image">Choose Image</div>
                                	<div class="image-change">
                                        <a href="javascript:void(0)"
                                             id="button_change_image"
                                             class="button-primary revblue <?php if ($bgType != "image") echo "button-disabled" ?>"
                                             style="margin-bottom:5px">
                                            <?php _uge("Change Image", REVSLIDER_TEXTDOMAIN) ?>
                                        </a>
                                    </div>
                                <div id="tp-bgimagesettings" class="bgsrcchanger-div"
                                     style="margin-top:10px;display:none">
                                     
                                 <div id="bg-setting-wrap" class="settings_wrapper">
                                     
                                     <!--<h3 style="cursor:default !important; background:none !important;border:none;box-shadow:none !important;font-size:12px;padding:0px;margin:17px 0px 0px;">
                                            <?php /*?><?php _uge("Background Settings:", REVSLIDER_TEXTDOMAIN) ?><?php */?>
                                        </h3>-->
                                    
                                <table class="form-table">
                                	<tr>
                                		<th>
                                            <?php _uge("Background Fit:", REVSLIDER_TEXTDOMAIN) ?>
                                		</th>
                                		<td>
                                        <select name="bg_fit" id="slide_bg_fit" style="margin-right:20px">
                                            <option
                                                value="cover"<?php if ($bgFit == 'cover') echo ' selected="selected"'; ?>>
                                                cover
                                            </option>
                                            <option
                                                value="contain"<?php if ($bgFit == 'contain') echo ' selected="selected"'; ?>>
                                                contain
                                            </option>
                                            <option
                                                value="percentage"<?php if ($bgFit == 'percentage') echo ' selected="selected"'; ?>>
                                                (%, %)
                                            </option>
                                            <option
                                                value="normal"<?php if ($bgFit == 'normal') echo ' selected="selected"'; ?>>
                                                normal
                                            </option>
                                        </select>
                                        
                                        
                                		</td>                                		
                                	</tr>
                                	<tr id="bg_fit_procent_row" style="<?php if ($bgFit != 'percentage') echo 'display: none; '; ?>" >
                                		
                                		<td colspan="2">
                                		                                		
	                                        <input type="text" name="bg_fit_x"
	                                               style="<?php if ($bgFit != 'percentage') echo 'display: none; '; ?> width:60px;margin-right:10px"
	                                               value="<?php echo intval($bgFitX); ?>" class="small-text" >
	                                               
	                                        <input type="text" name="bg_fit_y"
	                                               style="<?php if ($bgFit != 'percentage') echo 'display: none; '; ?> width:60px;margin-right:10px"
	                                               value="<?php echo intval($bgFitY); ?>" class="small-text" >
                                		
                                		</td>
                                	</tr>
                                	<tr>
                                		<th>
                                            <?php _uge("Background Repeat:", REVSLIDER_TEXTDOMAIN) ?>
                                		</th>
                                		<td>
                                		
                                        <select name="bg_repeat" id="slide_bg_repeat" style="margin-right:20px">
                                            <option
                                                value="no-repeat"<?php if ($bgRepeat == 'no-repeat') echo ' selected="selected"'; ?>>
                                                no-repeat
                                            </option>
                                            <option
                                                value="repeat"<?php if ($bgRepeat == 'repeat') echo ' selected="selected"'; ?>>
                                                repeat
                                            </option>
                                            <option
                                                value="repeat-x"<?php if ($bgRepeat == 'repeat-x') echo ' selected="selected"'; ?>>
                                                repeat-x
                                            </option>
                                            <option
                                                value="repeat-y"<?php if ($bgRepeat == 'repeat-y') echo ' selected="selected"'; ?>>
                                                repeat-y
                                            </option>
                                        </select>
                                		
                                		</td>                                		
                                	</tr>
                                	<tr>
                                		<th>
                                            <?php _uge("Background Position:", REVSLIDER_TEXTDOMAIN) ?>
                                		</th>
                                		<td>
                                		
                                        <div id="bg-start-position-wrapper">
                                            <select name="bg_position" id="slide_bg_position">
                                                <option
                                                    value="center top"<?php if ($bgPosition == 'center top') echo ' selected="selected"'; ?>>
                                                    center top
                                                </option>
                                                <option
                                                    value="center right"<?php if ($bgPosition == 'center right') echo ' selected="selected"'; ?>>
                                                    center right
                                                </option>
                                                <option
                                                    value="center bottom"<?php if ($bgPosition == 'center bottom') echo ' selected="selected"'; ?>>
                                                    center bottom
                                                </option>
                                                <option
                                                    value="center center"<?php if ($bgPosition == 'center center') echo ' selected="selected"'; ?>>
                                                    center center
                                                </option>
                                                <option
                                                    value="left top"<?php if ($bgPosition == 'left top') echo ' selected="selected"'; ?>>
                                                    left top
                                                </option>
                                                <option
                                                    value="left center"<?php if ($bgPosition == 'left center') echo ' selected="selected"'; ?>>
                                                    left center
                                                </option>
                                                <option
                                                    value="left bottom"<?php if ($bgPosition == 'left bottom') echo ' selected="selected"'; ?>>
                                                    left bottom
                                                </option>
                                                <option
                                                    value="right top"<?php if ($bgPosition == 'right top') echo ' selected="selected"'; ?>>
                                                    right top
                                                </option>
                                                <option
                                                    value="right center"<?php if ($bgPosition == 'right center') echo ' selected="selected"'; ?>>
                                                    right center
                                                </option>
                                                <option
                                                    value="right bottom"<?php if ($bgPosition == 'right bottom') echo ' selected="selected"'; ?>>
                                                    right bottom
                                                </option>
                                                <option
                                                    value="percentage"<?php if ($bgPosition == 'percentage') echo ' selected="selected"'; ?>>
                                                    (x%, y%)
                                                </option>
                                            </select>
                                            <input type="text"
                                                   name="bg_position_x" <?php if ($bgPosition != 'percentage') echo ' style="display: none;"'; ?>
                                                   value="<?php echo $bgPositionX; ?>"/>
                                            <input type="text"
                                                   name="bg_position_y" <?php if ($bgPosition != 'percentage') echo ' style="display: none;"'; ?>
                                                   value="<?php echo $bgPositionY; ?>"/>
                                        </div>
                                		
                                		</td>                                		
                                	</tr>
                                	
								</table>
								
                               </div><!-- bg settings wrap -->
                                
                                    <div style="clear:both"></div>
                                    
                                    <h3 style="cursor:default !important; background:none !important;border:none !important;box-shadow:none !important;font-size:11px; margin:0 5px; color:#a9a9a9;padding: 0;height: 22px !important;">
                                        <?php _uge("Ken Burns Effect", REVSLIDER_TEXTDOMAIN) ?>
                                    </h3>
                                    <div class="on-off-btn">
                                    	<input id="on2" type="radio" name="kenburn_effect"  value="off" <?php echo ($kenburn_effect == 'off') ? 'checked="checked"' : ''; ?> />
                                        <label for="on2"><?php _uge("Off", REVSLIDER_TEXTDOMAIN) ?></label>
                                        <input id="on1" type="radio" name="kenburn_effect"  value="on" <?php echo ($kenburn_effect == 'on') ? 'checked="checked"' : ''; ?> />
                                        <label for="on1"><?php _uge("On", REVSLIDER_TEXTDOMAIN) ?></label>                                        
                                        
                                    </div>
                                    <div class="clear"></div>
                                    
                                    <table class="on-off-pop" id="kenburn_wrapper" <?php echo ($kenburn_effect == 'off') ? 'style="display: none;"' : ''; ?>>
                                        <!--<tr>
                                            <td><?php _uge("Background", REVSLIDER_TEXTDOMAIN) ?></td>
                                            <td></td>
                                            <td></td>
                                            <td style="width: 15px;">&nbsp;</td>
                                            <td></td>
                                            <td>&nbsp;
                                                <?php //_uge("Start",REVSLIDER_TEXTDOMAIN)?></td>
                                            <td><?php //_uge("End",REVSLIDER_TEXTDOMAIN)?></td>
                                            <td style="width: 15px;">&nbsp;</td>
                                            <td colspan="2"></td>
                                        </tr>-->
                                        <tr>
                                            <td><?php _uge("Start Position:", REVSLIDER_TEXTDOMAIN) ?></td>
                                            <td colspan="2" id="bg-start-position-wrapper-kb" class="gap-box"></td>
                                            <td></td>
                                            <td><?php _uge("Start Fit: (in %)", REVSLIDER_TEXTDOMAIN) ?></td>
                                            <td colspan="2"  class="gap-box">
                                            	<input type="text" name="kb_start_fit" value="<?php echo intval($kb_start_fit); ?>"/>
                                            </td>
                                            <td></td>
                                            <td><?php _uge("Easing:", REVSLIDER_TEXTDOMAIN) ?></td>
                                            <td class="gap-box"><select name="kb_easing">
                                                    <option <?php if ($kb_easing == 'Linear.easeNone') echo ' selected="selected"'; ?>
                                                        value="Linear.easeNone">Linear.easeNone
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power0.easeIn') echo ' selected="selected"'; ?>
                                                        value="Power0.easeIn">Power0.easeIn (linear)
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power0.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Power0.easeInOut">Power0.easeInOut (linear)
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power0.easeOut') echo ' selected="selected"'; ?>
                                                        value="Power0.easeOut">Power0.easeOut (linear)
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power1.easeIn') echo ' selected="selected"'; ?>
                                                        value="Power1.easeIn">Power1.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power1.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Power1.easeInOut">Power1.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power1.easeOut') echo ' selected="selected"'; ?>
                                                        value="Power1.easeOut">Power1.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power2.easeIn') echo ' selected="selected"'; ?>
                                                        value="Power2.easeIn">Power2.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power2.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Power2.easeInOut">Power2.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power2.easeOut') echo ' selected="selected"'; ?>
                                                        value="Power2.easeOut">Power2.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power3.easeIn') echo ' selected="selected"'; ?>
                                                        value="Power3.easeIn">Power3.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power3.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Power3.easeInOut">Power3.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power3.easeOut') echo ' selected="selected"'; ?>
                                                        value="Power3.easeOut">Power3.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power4.easeIn') echo ' selected="selected"'; ?>
                                                        value="Power4.easeIn">Power4.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power4.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Power4.easeInOut">Power4.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Power4.easeOut') echo ' selected="selected"'; ?>
                                                        value="Power4.easeOut">Power4.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Back.easeIn') echo ' selected="selected"'; ?>
                                                        value="Back.easeIn">Back.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Back.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Back.easeInOut">Back.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Back.easeOut') echo ' selected="selected"'; ?>
                                                        value="Back.easeOut">Back.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Bounce.easeIn') echo ' selected="selected"'; ?>
                                                        value="Bounce.easeIn">Bounce.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Bounce.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Bounce.easeInOut">Bounce.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Bounce.easeOut') echo ' selected="selected"'; ?>
                                                        value="Bounce.easeOut">Bounce.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Circ.easeIn') echo ' selected="selected"'; ?>
                                                        value="Circ.easeIn">Circ.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Circ.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Circ.easeInOut">Circ.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Circ.easeOut') echo ' selected="selected"'; ?>
                                                        value="Circ.easeOut">Circ.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Elastic.easeIn') echo ' selected="selected"'; ?>
                                                        value="Elastic.easeIn">Elastic.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Elastic.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Elastic.easeInOut">Elastic.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Elastic.easeOut') echo ' selected="selected"'; ?>
                                                        value="Elastic.easeOut">Elastic.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Expo.easeIn') echo ' selected="selected"'; ?>
                                                        value="Expo.easeIn">Expo.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Expo.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Expo.easeInOut">Expo.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Expo.easeOut') echo ' selected="selected"'; ?>
                                                        value="Expo.easeOut">Expo.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Sine.easeIn') echo ' selected="selected"'; ?>
                                                        value="Sine.easeIn">Sine.easeIn
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Sine.easeInOut') echo ' selected="selected"'; ?>
                                                        value="Sine.easeInOut">Sine.easeInOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'Sine.easeOut') echo ' selected="selected"'; ?>
                                                        value="Sine.easeOut">Sine.easeOut
                                                    </option>
                                                    <option <?php if ($kb_easing == 'SlowMo.ease') echo ' selected="selected"'; ?>
                                                        value="SlowMo.ease">SlowMo.ease
                                                    </option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td><?php _uge("End Position:", REVSLIDER_TEXTDOMAIN) ?></td>
                                            <td colspan="2"  class="gap-box"><select name="bg_end_position" id="slide_bg_end_position">
                                                    <option
                                                        value="center top"<?php if ($bgEndPosition == 'center top') echo ' selected="selected"'; ?>>
                                                        center top
                                                    </option>
                                                    <option
                                                        value="center right"<?php if ($bgEndPosition == 'center right') echo ' selected="selected"'; ?>>
                                                        center right
                                                    </option>
                                                    <option
                                                        value="center bottom"<?php if ($bgEndPosition == 'center bottom') echo ' selected="selected"'; ?>>
                                                        center bottom
                                                    </option>
                                                    <option
                                                        value="center center"<?php if ($bgEndPosition == 'center center') echo ' selected="selected"'; ?>>
                                                        center center
                                                    </option>
                                                    <option
                                                        value="left top"<?php if ($bgEndPosition == 'left top') echo ' selected="selected"'; ?>>
                                                        left top
                                                    </option>
                                                    <option
                                                        value="left center"<?php if ($bgEndPosition == 'left center') echo ' selected="selected"'; ?>>
                                                        left center
                                                    </option>
                                                    <option
                                                        value="left bottom"<?php if ($bgEndPosition == 'left bottom') echo ' selected="selected"'; ?>>
                                                        left bottom
                                                    </option>
                                                    <option
                                                        value="right top"<?php if ($bgEndPosition == 'right top') echo ' selected="selected"'; ?>>
                                                        right top
                                                    </option>
                                                    <option
                                                        value="right center"<?php if ($bgEndPosition == 'right center') echo ' selected="selected"'; ?>>
                                                        right center
                                                    </option>
                                                    <option
                                                        value="right bottom"<?php if ($bgEndPosition == 'right bottom') echo ' selected="selected"'; ?>>
                                                        right bottom
                                                    </option>
                                                    <option
                                                        value="percentage"<?php if ($bgEndPosition == 'percentage') echo ' selected="selected"'; ?>>
                                                        (x%, y%)
                                                    </option>
                                                </select>
                                                <input type="text"
                                                       name="bg_end_position_x" <?php if ($bgEndPosition != 'percentage') echo ' style="display: none;"'; ?>
                                                       value="<?php echo $bgEndPositionX; ?>"/>
                                                <input type="text"
                                                       name="bg_end_position_y" <?php if ($bgEndPosition != 'percentage') echo ' style="display: none;"'; ?>
                                                       value="<?php echo $bgEndPositionY; ?>"/></td>
                                            <td></td>
                                            <td><?php _uge("End Fit: (in %)", REVSLIDER_TEXTDOMAIN) ?></td>
                                            <td colspan="2" class="gap-box"><input type="text" name="kb_end_fit"
                                                                   value="<?php echo intval($kb_end_fit); ?>"/></td>
                                            <?php /*<td>_uge("Rotation (in °):",REVSLIDER_TEXTDOMAIN) ?></td>
                                                    <td><input type="text" name="kb_rotation_start" value="<?php echo intval($kb_rotation_start); ?>" /></td>
                                                    <td><input type="text" name="kb_rotation_end" value="<?php echo intval($kb_rotation_end); ?>" /></td>*/ ?>
                                            <td></td>
                                            <td><?php _uge("Duration (in ms):", REVSLIDER_TEXTDOMAIN) ?></td>
                                            <td class="gap-box"><input type="text" name="kb_duration"
                                                       value="<?php echo intval($kb_duration); ?>"/></td>
                                        </tr>
                                    </table>
                                </div>
                                </div>

                                <!-- THE BG IMAGE FROM EXTERNAL SOURCE -->
                                <div id="tp-bgimageextsrc" class="bgsrcchanger-div">
                                    <input type="text" name="bg_external" id="slide_bg_external"
                                           value="<?php echo $slideBGExternal ?>" <?php echo ($bgType != 'external') ? ' class="disabled"' : ''; ?>>
                                    <a href="javascript:void(0)" id="button_change_external"
                                       class="button-primary revblue <?php if ($bgType != "external") echo "button-disabled" ?>"
                                       style="margin-bottom:5px">
                                        <?php _uge("Get External", REVSLIDER_TEXTDOMAIN) ?>
                                    </a></div>

                                <!-- THE COLOR SELECTOR -->
                                <div id="tp-bgcolorsrc" class="bgsrcchanger-div">
                                    <input type="text" name="bg_color"
                                           id="slide_bg_color" <?php echo $bgSolidPickerProps ?>
                                           value="<?php echo $slideBGColor ?>">
                                </div>
                                <div style="clear:both"></div>

                            </div>
                        </div>
                        
                        
                        <div class="clear"></div>
                    </div>
                </div>

                <!-- slide main - transition params -->
                
	            <div id="slide_main_transition" class="editor_buttons_wrapper postbox unite-postbox blue-bg">
	                <h3 class="box-closed tp-accordion tpa-closed"><span class="postbox-arrow2"><i
	                            class="fa fa-caret-down" aria-hidden="true"></i></span><span>
			          <?php _uge("Slide Transition", REVSLIDER_TEXTDOMAIN) ?>
			          </span></h3>
	                <div class="toggled-content tp-closedatstart tp-closeifotheropen">
	               
	               	<form id="slide_main_transition_form">
	              		 <?php $settingsSlideTransitionOutput->draw("form_slide_params", false); ?>
	                </form>
	               
	               </div>
	                
	           </div>
                
                
                <!-- slide main - link params -->
                
	            <div id="slide_main_link" class="editor_buttons_wrapper postbox unite-postbox blue-bg">
	                <h3 class="box-closed tp-accordion tpa-closed"><span class="postbox-arrow2"><i
	                            class="fa fa-caret-down" aria-hidden="true"></i></span><span>
			          <?php _uge("Slide Link", REVSLIDER_TEXTDOMAIN) ?>
			          </span></h3>
	                <div class="toggled-content tp-closedatstart tp-closeifotheropen">
	               
	               	<form id="slide_main_link_form">
	              		 <?php $settingsSlideLinkOutput->draw("form_slide_params", false); ?>
	                </form>
	               
	               </div>
	                
	           </div>
                
                
                <!-- slide main - advanced params -->
			            
		            <div id="slide_main_advanced" class="editor_buttons_wrapper postbox unite-postbox blue-bg">
		                <h3 class="box-closed tp-accordion tpa-closed"><span class="postbox-arrow2"><i
		                            class="fa fa-caret-down" aria-hidden="true"></i></span><span>
				          <?php _uge("Advanced", REVSLIDER_TEXTDOMAIN) ?>
				          </span></h3>
		                <div class="toggled-content tp-closedatstart tp-closeifotheropen">
		               	
		               	 <form id="slide_main_advanced_form">
		              	 	<?php $settingsSlideAdvancedOutput->draw("form_slide_params", false); ?>
						</form>		               
						
		               </div>
		                
		           </div>
                
		           
                </div>
                                
                <div class="clear"></div>
            <?php } ?>
            
            
            <!-----  Left Layers Form ------>
            <div class="edit_layers_left hidden">
                <form name="form_layers" id="form_layers">
                    <script type='text/javascript'>
                        g_settingsObj['form_layers'] = {}
                    </script>
                    <?php
                    $show_static = 'display: none;';

                    if ($slide->isStaticSlide())
                        $show_static = '';
                    $isTemplate = $slider->getParam("template", "false");
                    ?>
                    <!-- THE GENERAL LAYER PARAMETERS -->
                    <div class='settings_wrapper' style="<?php echo $show_static; ?>">
                        
                    	<div id="left-layer-title-line" class="left-layer-title-line">
	                    	<span>
	                    		This is a layer
	                    	</span>
	                    	<a href="javascript:void(0)" class="unselect_layer rev-button-hide">
	                    		<i class="fa fa-times" aria-hidden="true"></i>
	                    	</a>                    	
                    	</div>
                    
                        <div class="postbox unite-postbox">
                            <h3 class='no-accordion tp-accordion'><span class="postbox-arrow2"><i
                                        class="fa fa-caret-down" aria-hidden="true"></i></span> <span>
                <?php _uge("Static Options", REVSLIDER_TEXTDOMAIN) ?>
                </span></h3>
                            <div class="toggled-content tp-closeifotheropen">
                                <ul class="list_settings">
                                    <?php
                                    if ($isTemplate == "true") {
                                        ?>
                                        <li>
                                            <?php _uge('Static Layers will be shown on every slide in template sliders', REVSLIDER_TEXTDOMAIN); ?>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <div
                                        id="layer_static_wrapper"<?php echo ($isTemplate == "true") ? ' style="display: none;"' : ''; ?>>
                                        <li>
                                            <div id="static_start_on_wrap" class="setting_text">
                                                <?php _uge('Start on Slide', REVSLIDER_TEXTDOMAIN); ?>
                                            </div>
                                            <select id="layer_static_start" name="static_start">
                                                <?php
                                                if (!empty($arrSlideNames)) {
                                                    $si = 1;
                                                    end($arrSlideNames);
                                                    //fetch key of the last element of the array.
                                                    $lastElementKey = key($arrSlideNames);
                                                    foreach ($arrSlideNames as $sid => $sparams) {
                                                        if ($lastElementKey == $sid) break; //break on the last element
                                                        ?>
                                                        <option value="<?php echo $si; ?>"><?php echo $si; ?></option>
                                                        <?php
                                                        $si++;
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="-1">-1</option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </li>
                                        <div class="clear"></div>
                                        <li>
                                            <div id="static_end_on_wrap" class="setting_text">
                                                <?php _uge('End on Slide', REVSLIDER_TEXTDOMAIN); ?>
                                            </div>
                                            <select id="layer_static_end" name="static_end">
                                                <?php
                                                if (!empty($arrSlideNames)) {
                                                    $si = 1;
                                                    foreach ($arrSlideNames as $sid => $sparams) {
                                                        ?>
                                                        <option value="<?php echo $si; ?>"><?php echo $si; ?></option>
                                                        <?php
                                                        $si++;
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="-1">-1</option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </li>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- THE GENERAL LAYER PARAMETERS -->
                    <div class='settings_wrapper'>
                    	
                    	<?php if($slide->isStaticSlide() == false):?>
                    	<div id="left-layer-title-line" class="left-layer-title-line">
	                    	<span>
	                    		This is a layer
	                    	</span>
	                    	<a href="javascript:void(0)" class="unselect_layer rev-button-hide">
	                    		<i class="fa fa-times" aria-hidden="true"></i>
	                    	</a>                    	
                    	</div>
                    	<?php endif?>
                    	
                        <div class="postbox unite-postbox">
                            <h3 class="box-closed tp-accordion"><span class="postbox-arrow2"><i
                                        class="fa fa-caret-down" aria-hidden="true"></i></span><span>
                <?php _uge("Layer General Parameters", REVSLIDER_TEXTDOMAIN) ?>
                </span></h3>
                            <div class="toggled-content tp-closeifotheropen">
                                <ul class="list_settings">
                                    <!--<li id="end_layer_sap" class="attribute_title" style="margin-top:-10px;"> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php //_uge("Layer Content", REVSLIDER_TEXTDOMAIN) ?>
                    </span></li>-->
                                    <?php
                                    $s = $settingsLayerOutput;
                                    $s->drawSettingsByNames("layer_caption");

                                    //add css editor
                                    $s->drawCssEditor();

                                    //add global styles editor
                                    $s->drawGlobalCssEditor();

                                    $s->drawSettingsByNames("layer_text,button_edit_video,button_change_image_source,layer_alt");
                                    ?>
                                    <!--<li style="clear:both"> <span class="setting_text_2 text-disabled"
                                                                  original-title="">
                    <?php //_uge("Align, Position & Styling", REVSLIDER_TEXTDOMAIN) ?>
                    </span></li>-->
                                    <li class="align_table_wrapper">
                                        <table id="align_table" class="align_table table_disabled">
                                            <tr>
                                                <td><a href="javascript:void(0)" id="linkalign_left_top" data-hor="left"
                                                       data-vert="top"></a></td>
                                                <td><a href="javascript:void(0)" id="linkalign_center_top"
                                                       data-hor="center" data-vert="top"></a></td>
                                                <td><a href="javascript:void(0)" id="linkalign_right_top"
                                                       data-hor="right" data-vert="top"></a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="javascript:void(0)" id="linkalign_left_middle"
                                                       data-hor="left" data-vert="middle"></a></td>
                                                <td><a href="javascript:void(0)" id="linkalign_center_middle"
                                                       data-hor="center" data-vert="middle"></a></td>
                                                <td><a href="javascript:void(0)" id="linkalign_right_middle"
                                                       data-hor="right" data-vert="middle"></a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="javascript:void(0)" id="linkalign_left_bottom"
                                                       data-hor="left" data-vert="bottom"></a></td>
                                                <td><a href="javascript:void(0)" id="linkalign_center_bottom"
                                                       data-hor="center" data-vert="bottom"></a></td>
                                                <td><a href="javascript:void(0)" id="linkalign_right_bottom"
                                                       data-hor="right" data-vert="bottom"></a></td>
                                            </tr>
                                        </table>
                                    </li>
                                    <div style="float:left;width:auto;">
                                        <div style="clear:both" class="xy-box">
                                            <?php
                                            $s->drawSettingsByNames("layer_left,layer_top");
                                            ?>
                                        </div>
                                        <div style="clear:both">
                                            <?php
                                            $s->drawSettingsByNames("layer_whitespace");
                                            ?>
                                        </div>
                                        <div style="clear:both;display:none" >
                                            <?php
                                            $s->drawSettingsByNames("layer_align_hor,layer_align_vert");
                                            ?>
                                        </div>
                                        <div style="clear:both"></div>
                                        <div class="xy-box">
                                            <?php
                                            $s->drawSettingsByNames("layer_max_width,layer_max_height");
                                            ?>
                                        </div>
                                    </div>
                                    <!--<li id="layer_scale_title_row" style="clear:both;"> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php //_uge("Image Scale (dimensions in pixel)", REVSLIDER_TEXTDOMAIN) ?>
                    </span></li>-->
                                    <a id="reset-scale" class="revred button-primary"
                                       href="javascript:void(0);"><?php echo _uge("Reset Size", REVSLIDER_TEXTDOMAIN) ?></a>
                                    <?php
                                    $s->drawSettingsByNames("layer_scaleX,layer_scaleY");
                                    $s->drawSettingsByNames("layer_proportional_scale");

                                    $show_parallax = 'display: none;';

                                    if (UniteFunctionsRev::getVal($sliderParams, "use_parallax", "off") == 'on')
                                        $show_parallax = '';

                                    ?>
                                    <!--<li id="layer_2d_title_row" style="clear:both;"> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php //_uge("Final Rotation", REVSLIDER_TEXTDOMAIN) ?>
                    </span></li>-->
                                    <div style="clear: both; overflow:hidden"  class="xy-box2">
                                        <?php
                                        $s->drawSettingsByNames("layer_2d_rotation");
                                        $s->drawSettingsByNames("layer_2d_origin_x");
                                        $s->drawSettingsByNames("layer_2d_origin_y");
                                        ?>
                                    </div>
                                    <div id="parallax_wrapper_div" style="<?php echo $show_parallax; ?>">
                                        <li id="parallax_title_row" style="clear:both;"> <span
                                                class="setting_text_2 text-disabled" original-title="">
                      <?php _uge("Parallax Setting", REVSLIDER_TEXTDOMAIN) ?>
                      </span></li>
                                        <?php
                                        $s->drawSettingsByNames("parallax_level");
                                        ?>
                                    </div>
                                </ul>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

                    <!-- THE ANIMATION PARAMETERS -->
                    <div class='settings_wrapper single_anim_parameter'>
                        <div class="postbox unite-postbox">
                            <h3 class='no-accordion tp-accordion tpa-closed'><span class="postbox-arrow2"><i
                                        class="fa fa-caret-down" aria-hidden="true"></i></span> <span>
                <?php _uge("Layer Animation", REVSLIDER_TEXTDOMAIN) ?>
                </span></h3>
                            <div class="toggled-content tp-closedatstart tp-closeifotheropen">
                                <ul class="list_settings">

                                    <!--LAYER START ANIMATION -->
                                    <!--<li id="end_layer_sap" class="attribute_title" style="margin-top:-10px;"> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php //_uge("Preview Transition (Star end Endtime is Ignored during Demo)", REVSLIDER_TEXTDOMAIN) ?>
                    </span></li>-->
                                    <div id="preview_caption_transition">
                                        <div class="preview_caption_wrapper">
                                            <div id="preview_caption_animateme">LAYER EXAMPLE</div>
                                        </div>
                                        <div id="preview_looper"><i class="revicon-arrows-ccw"></i><span
                                                class="replyinfo">ON</span></div>
                                    </div>
                                    <div class="divide10"></div>
                                    
                                    

                                   <!--LAYER START ANIMATION -->
                                    <ul id="tabnav">
                                    <li id="end_layer_sap" class="attribute_title active" style=""> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php _uge("Start", REVSLIDER_TEXTDOMAIN) ?>
                    </span></li>
                    				<li id="end_layer_sap" class="attribute_title" style=""> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php _uge("End", REVSLIDER_TEXTDOMAIN) ?>
                    </span></li>
                    				<li id="loop_layer_sap" class="attribute_title" style=""> 
                                    	<span class="setting_text_2 text-disabled" original-title="">
											<?php _uge("Loop", REVSLIDER_TEXTDOMAIN) ?>
                                    	</span>
                                    </li>
                                    </ul>
                                    
                    				<div class="tab-pane" style="display:block;">
                                        <div class="layer-animations">
                                            <div>
                                                <?php $s->drawSettingsByNames("layer_animation"); ?>
                                            </div>
                                            <a href="javascript:void(0)" id="add_customanimation_in"
                                               class="button-primary revblue blue-bg1"><i class="revicon-equalizer"></i>
                                                <?php _uge("Custom Animation", REVSLIDER_TEXTDOMAIN) ?>
                                            </a>
                                            <div class="clear"></div>
                                            <?php $s->drawSettingsByNames("layer_easing,layer_speed,layer_split,layer_splitdelay"); ?>
                                        </div>
                                    </div>
                                    <!--LAYER END ANIMATION -->
                                    
                    				<div class="tab-pane">
                                    	<div class="layer-animations">
                                        <div>
                                            <?php $s->drawSettingsByNames("layer_endanimation"); ?>
                                        </div>
                                        <a href="javascript:void(0)" id="add_customanimation_out"
                                           class="button-primary revblue  blue-bg1"><i class="revicon-equalizer"></i>
                                            <?php _uge("Custom Animation", REVSLIDER_TEXTDOMAIN) ?>
                                        </a>
                                        <div class="clear"></div>
                                        <?php $s->drawSettingsByNames("layer_endeasing,layer_endspeed,layer_endtime,layer_endsplit,layer_endsplitdelay"); ?>
                                    </div>
                                    </div>

                                    <!--LAYER LOOP ANIMATION -->
                                    
                                    <div class="tab-pane">
										<?php $s->drawSettingsByNames("layer_loop_animation"); ?>
                                        <div id="layer_speed_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_speed"); ?>
                                        </div>
                                        <div id="layer_easing_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_easing"); ?>
                                        </div>
                                        <div id="layer_degree_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_startdeg,layer_loop_enddeg"); ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div id="layer_origin_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_xorigin,layer_loop_yorigin"); ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div id="layer_x_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_xstart,layer_loop_xend"); ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div id="layer_y_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_ystart,layer_loop_yend"); ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div id="layer_zoom_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_zoomstart,layer_loop_zoomend"); ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div id="layer_angle_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_angle"); ?>
                                        </div>
                                        <div id="layer_radius_wrapper" style="display: none;">
                                            <?php $s->drawSettingsByNames("layer_loop_radius"); ?>
                                        </div>
                                    </div>
                                </ul>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF ANIMATION PARAMETERS -->

                    <!-- LAYER ANIMATION EDITOR  (DISPLAY NONE !!)-->
                    <div id="layeranimeditor_wrap" title="Layer Animation Editor"
                         style="display:none;margin-bottom:0px; padding-bottom:0px;">
                        <div class="tp-present-wrapper-parent">
                            <div class="tp-present-wrapper">
                                <div class="tp-present-caption">
                                    <div id="caption_custon_anim_preview" class="">LAYER EXAMPLE</div>
                                </div>
                            </div>
                        </div>
                        <div class="divide20"></div>
                        <div class="settings_wrapper">
                            <div class="postbox unite-postbox">
                                <h3 class="no-accordion tp-accordion"> <span style="font-size:13px;padding-left:4px;"><i
                                            class="revicon-equalizer"></i>
                                        <?php _uge("Layer Animation Settings Panel", REVSLIDER_TEXTDOMAIN) ?>
                  </span> <span style="float: right;"><a href="javascript:void(0);"
                                                         style="margin-top:-7px;border: none;font-weight: 400;box-shadow: none;-webkit-box-shadow: none;-moz-box-shadow: none;"
                                                         id="set-random-animation" class="button-primary revgreen"><i
                                                class="eg-icon-shuffle"></i>
                                            <?php _uge("Randomize", REVSLIDER_TEXTDOMAIN) ?>
                  </a></span></h3>
                                <table style="border-spacing:0px">

                                    <!-- TRANSITION -->

                                    <tr class="css-edit-title graybasicbg">
                                        <td colspan="6"
                                            style="padding:10px"><?php _uge("Transition", REVSLIDER_TEXTDOMAIN) ?></td>
                                    </tr>
                                    <tr class="graybasicbg">
                                        <td><?php _uge("X:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="padding-bottom:15px">
                                            <div id='caption-movex-slider' class="rotationsliders"></div>
                                            <input class="css_edit_novice tpshortinput" name="movex" type="text"
                                                   value="0"/>
                                            px
                                        </td>
                                        <td><?php _uge("Y:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="padding-bottom:15px">
                                            <div id='caption-movey-slider' class="rotationsliders"></div>
                                            <input class="css_edit_novice tpshortinput" name="movey" type="text"
                                                   value="0"/>
                                            px
                                        </td>
                                        <td><?php _uge("Z:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="padding-bottom:15px">
                                            <div id='caption-movez-slider' class="rotationsliders"></div>
                                            <input class="css_edit_novice tpshortinput" name="movez" type="text"
                                                   value="0"/>
                                            px
                                        </td>
                                    </tr>

                                    <!-- ROTATION -->

                                    <tr class="css-edit-title">
                                        <td colspan="6"
                                            style="padding:10px"><?php _uge("Rotation", REVSLIDER_TEXTDOMAIN) ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php _uge("X:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="padding-bottom:15px">
                                            <div id='caption-rotationx-slider' class="rotationsliders"></div>
                                            <input class="css_edit_novice tpshortinput" name="rotationx" type="text"
                                                   value="0"/>
                                            °
                                        </td>
                                        <td><?php _uge("Y:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="padding-bottom:15px">
                                            <div id='caption-rotationy-slider' class="rotationsliders"></div>
                                            <input class="css_edit_novice tpshortinput" name="rotationy" type="text"
                                                   value="0"/>
                                            °
                                        </td>
                                        <td><?php _uge("Z:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="padding-bottom:15px">
                                            <div id='caption-rotationz-slider' class="rotationsliders"></div>
                                            <input class="css_edit_novice tpshortinput" name="rotationz" type="text"
                                                   value="0"/>
                                            °
                                        </td>
                                    </tr>
                                </table>
                                <table style="border-spacing:0px">
                                    <!-- SCALE && SKEW-->

                                    <tr class="css-edit-title graybasicbg">
                                        <td colspan="4"
                                            style="padding:10px"><?php _uge("Scale", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td colspan="4"
                                            style="padding:10px;padding-left:20px"><?php _uge("Skew", REVSLIDER_TEXTDOMAIN) ?></td>
                                    </tr>
                                    <tr class="graybasicbg">
                                        <!-- SCALE X -->
                                        <td style="width:30px"><?php _uge("X:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-scalex-slider' class="rotationsliders"
                                                 style="width:100px !important;"></div>
                                            <input class="css_edit_novice tpshortinput" name="scalex" type="text"
                                                   value="0"/>
                                            %
                                        </td>
                                        <!-- SCALE Y -->
                                        <td style="width:30px;"><?php _uge("Y:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-scaley-slider' class="rotationsliders"
                                                 style="width:100px;"></div>
                                            <input class="css_edit_novice tpshortinput" name="scaley" type="text"
                                                   value="0"/>
                                            %
                                        </td>
                                        <td style="width:30px;"><?php _uge("X:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <!-- SKEW X -->
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-skewx-slider' class="rotationsliders"
                                                 style="width:100px;"></div>
                                            <input class="css_edit_novice tpshortinput" name="skewx" type="text"
                                                   value="0"/>
                                            °
                                        </td>
                                        <td style="width:30px"><?php _uge("Y:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <!-- SKEW Y -->
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-skewy-slider' class="rotationsliders"
                                                 style="width:100px;"></div>
                                            <input class="css_edit_novice tpshortinput" name="skewy" type="text"
                                                   value="0"/>
                                            °
                                        </td>
                                    </tr>
                                </table>
                                <table style="border-spacing:0px padding-bottom:10px">
                                    <!-- OPACITY -->

                                    <tr class="css-edit-title">
                                        <td colspan="2"
                                            style="padding:10px"><?php _uge("Opacity", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td colspan="2"
                                            style="padding:10px;padding-left:20px"><?php _uge("Perspective", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td colspan="2"
                                            style="padding:10px;padding-left:20px"><?php _uge("Origin", REVSLIDER_TEXTDOMAIN) ?></td>
                                    </tr>
                                    <tr class="">
                                        <!-- OPACITY -->
                                        <td style="width:30px"></td>
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-opacity-slider' class="rotationsliders"
                                                 style="width:100px !important;"></div>
                                            <input class="css_edit_novice tpshortinput" name="captionopacity"
                                                   type="text" value="0"/>
                                            %
                                        </td>
                                        <!-- PERSPECTIVE -->
                                        <td style="width:30px;"></td>
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-perspective-slider' class="rotationsliders"
                                                 style="width:100px;"></div>
                                            <input class="css_edit_novice tpshortinput" name="captionperspective"
                                                   type="text" value="0"/>
                                            px
                                        </td>
                                        <!-- ORIGINX -->
                                        <td style="width:30px;"><?php _uge("X:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-originx-slider' class="rotationsliders"
                                                 style="width:100px;"></div>
                                            <input class="css_edit_novice tpshortinput" name="originx" type="text"
                                                   value="0"/>
                                            %
                                        </td>
                                        <!-- ORIGINY -->
                                        <td style="width:30px"><?php _uge("Y:", REVSLIDER_TEXTDOMAIN) ?></td>
                                        <td style="width:100px; padding-bottom:15px; ">
                                            <div id='caption-originy-slider' class="rotationsliders"
                                                 style="width:100px;"></div>
                                            <input class="css_edit_novice tpshortinput" name="originy" type="text"
                                                   value="0"/>
                                            %
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="settings_wrapper">
                            <div class="postbox unite-postbox">
                                <h3 class="no-accordion tp-accordion"> <span style="font-size:13px;padding-left:4px;"><i
                                            class="eg-icon-tools"></i>
                                        <?php _uge("Test Parameters", REVSLIDER_TEXTDOMAIN) ?>
                  </span> <span style="font-size: 9px;text-align: right;font-weight: 300;font-style: italic;white-space: nowrap;">
                  <?php _uge("*These Settings are only for Customizer. Settings can be set per Start and End Animation.", REVSLIDER_TEXTDOMAIN) ?>
                  </span></h3>
                                <table>
                                    <tr>
                                        <td>Speed:</td>
                                        <td style="vertical-align: middle; line-height: 25px;"><input
                                                class="css_edit_novice tpshortinput"
                                                style="margin-top:3px;margin-right:5px;" name="captionspeed" type="text"
                                                value="600"/>
                                            ms
                                        </td>
                                        <td>Easing:</td>
                                        <td><select id="caption-easing-demo" name="caption-easing-demo" class="">
                                                <option value="Linear.easeNone">Linear.easeNone</option>
                                                <option value="Power0.easeIn">Power0.easeIn (linear)</option>
                                                <option value="Power0.easeInOut">Power0.easeInOut (linear)</option>
                                                <option value="Power0.easeOut">Power0.easeOut (linear)</option>
                                                <option value="Power1.easeIn">Power1.easeIn</option>
                                                <option value="Power1.easeInOut">Power1.easeInOut</option>
                                                <option value="Power1.easeOut">Power1.easeOut</option>
                                                <option value="Power2.easeIn">Power2.easeIn</option>
                                                <option value="Power2.easeInOut">Power2.easeInOut</option>
                                                <option value="Power2.easeOut">Power2.easeOut</option>
                                                <option value="Power3.easeIn">Power3.easeIn</option>
                                                <option value="Power3.easeInOut">Power3.easeInOut</option>
                                                <option value="Power3.easeOut">Power3.easeOut</option>
                                                <option value="Power4.easeIn">Power4.easeIn</option>
                                                <option value="Power4.easeInOut">Power4.easeInOut</option>
                                                <option value="Power4.easeOut">Power4.easeOut</option>
                                                <option value="Back.easeIn">Back.easeIn</option>
                                                <option value="Back.easeInOut">Back.easeInOut</option>
                                                <option value="Back.easeOut">Back.easeOut</option>
                                                <option value="Bounce.easeIn">Bounce.easeIn</option>
                                                <option value="Bounce.easeInOut">Bounce.easeInOut</option>
                                                <option value="Bounce.easeOut">Bounce.easeOut</option>
                                                <option value="Circ.easeIn">Circ.easeIn</option>
                                                <option value="Circ.easeInOut">Circ.easeInOut</option>
                                                <option value="Circ.easeOut">Circ.easeOut</option>
                                                <option value="Elastic.easeIn">Elastic.easeIn</option>
                                                <option value="Elastic.easeInOut">Elastic.easeInOut</option>
                                                <option value="Elastic.easeOut">Elastic.easeOut</option>
                                                <option value="Expo.easeIn">Expo.easeIn</option>
                                                <option value="Expo.easeInOut">Expo.easeInOut</option>
                                                <option value="Expo.easeOut">Expo.easeOut</option>
                                                <option value="Sine.easeIn">Sine.easeIn</option>
                                                <option value="Sine.easeInOut">Sine.easeInOut</option>
                                                <option value="Sine.easeOut">Sine.easeOut</option>
                                                <option value="SlowMo.ease">SlowMo.ease</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Animate per:</td>
                                        <td><select id="caption-split-demo" name="caption-split-demo" class="">
                                                <option value="full">None</option>
                                                <option value="chars">Chars</option>
                                                <option value="words">Words</option>
                                                <option value="lines">Lines</option>
                                            </select></td>
                                        <!-- DELAYS -->
                                        <td>Delays:</td>
                                        <td style="vertical-align: middle; line-height: 25px;"><input
                                                class="css_edit_novice tpshortinput"
                                                style="margin-top:3px;margin-right:5px;" name="captionsplitdelay"
                                                type="text" value="10"/>
                                            ms
                                        </td>
                                    </tr>
                                </table>
                                <div id="caption-inout-controll" class="caption-inout-controll"><i
                                        id="revshowmetheinanim" class="revicon-login reviconinaction"></i><i
                                        id="revshowmetheoutanim" class="revicon-logout"></i>
                                    <?php _uge("Transition Direction", REVSLIDER_TEXTDOMAIN) ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    <div id="dialog-change-layeranimation" title="Save Layer Animation" style="display:none;">
                        <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 50px 0;"></span>
                            <?php
                            _uge('Overwrite the current selected Animation ', REVSLIDER_TEXTDOMAIN);
                            echo '"<span id="current-layer-handle"></span>"';
                            _uge(' or save as a new Animation?', REVSLIDER_TEXTDOMAIN) ?>
                        </p>
                    </div>
                    <div id="dialog-change-layeranimation-save-as" title="Save as" style="display:none;">
                        <p>
                            <?php _uge('Save as Animation:', REVSLIDER_TEXTDOMAIN) ?>
                            <br/>
                            <input type="text" name="layeranimation_save_as" value=""/>
                        </p>
                    </div>
                    <!-- END OF CUSTOM ANIMATION LAYER EDITOR -->

                    <!-- THE ADVANCED LAYER PARAMETERS -->
                    
                    <!-- LINKS ACCORDION ITEM START -->
                    
                    <div class='settings_wrapper single_anim_parameter'>
                        <div class="postbox unite-postbox">
                            <h3 class='no-accordion tp-accordion tpa-closed'><span class="postbox-arrow2"><i
                                        class="fa fa-caret-down" aria-hidden="true"></i></span> 
                                        <span>
               				 				<?php _uge("Layer Links", REVSLIDER_TEXTDOMAIN) ?>
               				 			</span>
                			</h3>
                			
                            <div class="toggled-content tp-closedatstart tp-closeifotheropen">
                               
                                <ul class="list_settings">
                                
                                    <div class="layer-links">
                                        <?php
                                        $s = $settingsLayerOutput;
                                        $s->drawSettingsByNames("layer_image_link,layer_link_open_in,layer_slide_link,layer_scrolloffset,layer_link_id,layer_link_class,layer_link_title,layer_link_rel");
                                        ?>
                                    </div>
                                    <div style="clear: both;"></div>
                                    <!--<li id="" class="custom attributes_title" style=""> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php //_uge("Caption Sharp Corners (optional only with BG color)", REVSLIDER_TEXTDOMAIN) ?>
                    </span>
                                       
                                    </li>-->
                                    <div class="layer-links">
                                        <?php
                                        $s = $settingsLayerOutput;
                                        $s->drawSettingsByNames("layer_cornerleft,layer_cornerright");
                                        ?>
                                    </div>
                                    <!--<li id="" class="custom attributes_title" style=""> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php //_uge("Advanced Responsive Settings", REVSLIDER_TEXTDOMAIN) ?>
                    </span>
                                    </li>-->
                                    
                                    <div class="layer-links select-box">
                                        <?php
                                        $s = $settingsLayerOutput;
                                        $s->drawSettingsByNames("layer_resizeme,layer_hidden");
                                        ?>
                                    </div>
                                    <!--<li id="custom_attributes" class="custom attributes_title" style=""> <span
                                            class="setting_text_2 text-disabled" original-title="">
                    <?php //_uge("Attributes (optional)", REVSLIDER_TEXTDOMAIN) ?>
                    </span>
                                    </li>-->
                                </ul>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- LAYER ADVANCED PARAMS ACCORDION -->
                    
                    <div class='settings_wrapper single_anim_parameter'>
                        <div class="postbox unite-postbox">
                            <h3 class='no-accordion tp-accordion tpa-closed'><span class="postbox-arrow2"><i
                                        class="fa fa-caret-down" aria-hidden="true"></i></span> 
                                        <span>
               				 				<?php _uge("Layer Advanced Params", REVSLIDER_TEXTDOMAIN) ?>
               				 			</span>
                			</h3>
                			
                            <div class="toggled-content tp-closedatstart tp-closeifotheropen">
                    			<ul class="list_settings">
                    		<!-- put accodrion items here -->
                               <?php
                                  $s = $settingsLayerOutput;
                                  $s->drawSettingsByNames("layer_id,layer_classes,layer_title,layer_rel");
                                ?>
                                 </ul>              		
                    		</div>
                    	</div>
                    </div>
                    
                    
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<script type="text/javascript">
	
    jQuery(document).ready(function () {

		if(!g_objLayers)
    		g_objLayers = new UniteLayersRev();
    	
    	if(!g_revSliderAdmin)
    		g_revSliderAdmin = new RevSliderAdmin();
		
        <?php if(!empty($jsonLayers)):?>
        //set init layers object
        g_objLayers.setInitLayersJson(<?php echo $jsonLayers?>);
        <?php endif?>

        <?php if($slide->isStaticSlide()){
        $arrayDemoLayers = array();
        $arrayDemoSettings = array();
        if (!empty($all_slides)) {
            foreach ($all_slides as $cSlide) {
                $arrayDemoLayers[$cSlide->getID()] = $cSlide->getLayers();
                $arrayDemoSettings[$cSlide->getID()] = $cSlide->getParams();
            }
        }
        $jsonDemoLayers = UniteFunctionsRev::jsonEncodeForClientSide($arrayDemoLayers);
        $jsonDemoSettings = UniteFunctionsRev::jsonEncodeForClientSide($arrayDemoSettings);
        ?>
        //set init demo layers object
        g_objLayers.setInitDemoLayersJson(<?php echo $jsonDemoLayers?>);
        g_objLayers.setInitDemoSettingsJson(<?php echo $jsonDemoSettings?>);
        <?php } ?>

        <?php if(!empty($jsonCaptions)):?>
        g_objLayers.setInitCaptionClasses(<?php echo $jsonCaptions?>);
        <?php endif?>

        <?php if(!empty($arrCustomAnim)):?>
        g_objLayers.setInitLayerAnim(<?php echo $arrCustomAnim?>);
        <?php endif?>

        <?php if(!empty($jsonFontFamilys)):?>
        g_objLayers.setInitFontTypes(<?php echo $jsonFontFamilys?>);
        <?php endif?>

        <?php if(!empty($arrCssStyles)):?>
        UniteCssEditorRev.setInitCssStyles(<?php echo $arrCssStyles?>);
        <?php endif?>

        UniteCssEditorRev.setCssCaptionsUrl('<?php echo $urlCaptionsCSS?>');

        g_objLayers.init("<?php echo $slideDelay?>");
        UniteCssEditorRev.init();

        g_revSliderAdmin.initGlobalStyles();

        g_revSliderAdmin.initLayerPreview();

        g_revSliderAdmin.setStaticCssCaptionsUrl('<?php echo GlobalsRevSlider::$urlStaticCaptionsCSS; ?>');

        /*			var reproduce;
         jQuery(window).resize(function() {
         clearTimeout(reproduce);
         reproduce = setTimeout(function() {
         g_objLayers.refreshGridSize();
         },100);
         });*/

        <?php if($kenburn_effect == 'on'){ ?>
        jQuery('input[name="kenburn_effect"]:checked').change();
        <?php } ?>
    });

</script>
<script>
	jQuery('#tabnav li').click(function(){
		var index = jQuery(this).index();
		jQuery('#tabnav li').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.tab-pane').hide();
		jQuery('.tab-pane').eq(index).show();
		return false
	});
</script>
