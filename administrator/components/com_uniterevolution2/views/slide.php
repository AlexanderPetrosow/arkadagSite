<?php

	//get input
	$slideID = UniteFunctionsRev::getGetVar("id");
	
	$permission = RevOperations::getPermission(GlobalsRevSlider::PERMISSION_SLIDE_OPERATIONS);
	$permission_edit = RevOperations::getPermission(GlobalsRevSlider::PERMISSION_EDIT_SLIDE);
	$permission_setting = RevOperations::getPermission(GlobalsRevSlider::PERMISSION_SLIDER_SETTINGS);
	
	//validate permission
	if(!$permission_edit){
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	}
	
	//init slide object
	$slide = new RevSlide();
	$slide->initByID($slideID);
	$slideParams = $slide->getParams();
		
	//dmp($slideParams);exit();
	
	$operations = new RevOperations();
	
	//init slider object
	$sliderID = $slide->getSliderID();
	$slider = new RevSlider();
	$slider->initByID($sliderID);
	$sliderParams = $slider->getParams();
	$sliderType = $slider->getParam("slider_type");
	
	$sliderTitle = $slider->getParam("title");
	$sliderTitle = htmlspecialchars($sliderTitle);
	
	$arrSlideNames = $slider->getArrSlideNames();
	
	//$slide1 = $arrSlideNames[3];
		
	//check if slider is template
	$sliderTemplate = $slider->getParam("template","false");
		
	//set slide delay
	$sliderDelay = $slider->getParam("delay","9000");
	$slideDelay = $slide->getParam("delay","");
	if(empty($slideDelay))
		$slideDelay = $sliderDelay;
	
	require self::getSettingsFilePath("slide_settings");
	require self::getSettingsFilePath("layer_settings");
	
	//add tools.min.js 	
	$urlPluginsJS = self::$url_item_plugin."js/jquery.themepunch.tools.min.js";
	
	self::addScriptAbsoluteUrl($urlPluginsJS,"themepunch plugins");
	
	$settingsLayerOutput = new UniteSettingsProductSidebarRev();
	$settingsSlideOutput = new UniteSettingsRevProductRev();
	$settingsSlideAdvancedOutput = new UniteSettingsRevProductRev();
	$settingsSlideLinkOutput = new UniteSettingsRevProductRev();
	$settingsSlideTransitionOutput = new UniteSettingsRevProductRev();
		
	$arrLayers = $slide->getLayers();
	
	$loadGoogleFont = $slider->getParam("load_googlefont","false");
	
	//get settings objects
	$settingsLayer = self::getSettings("layer_settings");	
	$settingsSlide = self::getSettings("slide_settings");
	$settingsSlideAdvanced = self::getSettings("slide_settings_advanced");
	$settingsSlideLink = self::getSettings("slide_settings_link");
	$settingsSlideTransition = self::getSettings("slide_settings_transition");
	
	
	$cssContent = self::getSettings("css_captions_content");
	$arrCaptionClasses = $operations->getArrCaptionClasses($cssContent);
	$arrFontFamily = $operations->getArrFontFamilys($slider);
	$arrCSS = $operations->getCaptionsContentArray();
	
	$arrButtonClasses = $operations->getButtonClasses();
	$urlCaptionsCSS = GlobalsRevSlider::$urlCaptionsCSSAdmin;

	$arrAnim = $operations->getFullCustomAnimations();
	
	//set layer caption as first caption class
	$firstCaption = !empty($arrCaptionClasses)?$arrCaptionClasses[0]:"";
	$settingsLayer->updateSettingValue("layer_caption",$firstCaption);
	
	//set stored values from "slide params"
	$settingsSlide->setStoredValues($slideParams);
	$settingsSlideAdvanced->setStoredValues($slideParams);
	$settingsSlideLink->setStoredValues($slideParams);
	$settingsSlideTransition->setStoredValues($slideParams);
	
	
	//init the settings output object
	$settingsLayerOutput->init($settingsLayer);
	$settingsSlideOutput->init($settingsSlide);
	$settingsSlideAdvancedOutput->init($settingsSlideAdvanced);
	$settingsSlideLinkOutput->init($settingsSlideLink);
	$settingsSlideTransitionOutput->init($settingsSlideTransition);
	
	
	//set various parameters needed for the page
	$width = $sliderParams["width"];
	$height = $sliderParams["height"];
	$imageUrl = $slide->getImageUrl();
	$imageID = $slide->getImageID();
	
	$imageFilename = $slide->getImageFilename();
	
	$style = "height:".$height."px;"; //
	$divLayersWidth = "width:".$width."px;";
	$divbgminwidth = "min-width:".$width."px;";
		
	//set iframe parameters
	$iframeWidth = $width+60;
	$iframeHeight = $height+50;
	
	$iframeStyle = "width:".$iframeWidth."px;height:".$iframeHeight."px;";
	
	$closeUrl = self::getViewUrl(RevSliderAdmin::VIEW_SLIDES,"id=".$sliderID);
	
	
	$jsonLayers = UniteFunctionsRev::jsonEncodeForClientSide($arrLayers);
	$jsonCaptions = UniteFunctionsRev::jsonEncodeForClientSide($arrCaptionClasses);
	$jsonFontFamilys = UniteFunctionsRev::jsonEncodeForClientSide($arrFontFamily);
	$arrCssStyles = UniteFunctionsRev::jsonEncodeForClientSide($arrCSS);
		
	$arrCustomAnim = UniteFunctionsRev::jsonEncodeForClientSide($arrAnim);
		
	//bg type params
	$bgType = UniteFunctionsRev::getVal($slideParams, "background_type","image");
		
	$slideBGColor = UniteFunctionsRev::getVal($slideParams, "slide_bg_color","#E7E7E7");
	$divLayersClass = "slide_layers";
	$bgSolidPickerProps = 'class="inputColorPicker slide_bg_color disabled" disabled="disabled"';
	
	$bgFit = UniteFunctionsRev::getVal($slideParams, "bg_fit","cover");
	$bgFitX = UniteFunctionsRev::getVal($slideParams, "bg_fit_x","100");
	$bgFitY = UniteFunctionsRev::getVal($slideParams, "bg_fit_y","100");
	
	if($bgFitX != "auto")
		$bgFitX = intval($bgFitX);
	
	if($bgFitY != "auto")
		$bgFitY = intval($bgFitY);
	
	if(is_numeric($bgFitX))
		$bgFitX .= "%";
	
	if(is_numeric($bgFitY))
		$bgFitY .= "%";
	
	$bgPosition = UniteFunctionsRev::getVal($slideParams, "bg_position","center top");
	$bgPositionX = intval(UniteFunctionsRev::getVal($slideParams, "bg_position_x","0"));
	$bgPositionY = intval(UniteFunctionsRev::getVal($slideParams, "bg_position_y","0"));
	
	$bgEndPosition = UniteFunctionsRev::getVal($slideParams, "bg_end_position","center top");
	$bgEndPositionX = intval(UniteFunctionsRev::getVal($slideParams, "bg_end_position_x","0"));
	$bgEndPositionY = intval(UniteFunctionsRev::getVal($slideParams, "bg_end_position_y","0"));
	
	$kenburn_effect = UniteFunctionsRev::getVal($slideParams, "kenburn_effect","off");
	//$kb_rotation_start = UniteFunctionsRev::getVal($slideParams, "kb_rotation_start","0");
	//$kb_rotation_end = UniteFunctionsRev::getVal($slideParams, "kb_rotation_end","0");
	$kb_duration = UniteFunctionsRev::getVal($slideParams, "kb_duration", $sliderParams["delay"]);
	$kb_easing = UniteFunctionsRev::getVal($slideParams, "kb_easing","Linear.easeNone");
	$kb_start_fit = UniteFunctionsRev::getVal($slideParams, "kb_start_fit","100");
	$kb_end_fit = UniteFunctionsRev::getVal($slideParams, "kb_end_fit","100");
	
	$bgRepeat = UniteFunctionsRev::getVal($slideParams, "bg_repeat","no-repeat");
	
	$style_wrapper = '';
	$class_wrapper = '';
	
	$slideBGExternal = UniteFunctionsRev::getVal($slideParams, "slide_bg_external","");
	
	switch($bgType){
		case "trans":
			$divLayersClass = "slide_layers";
			$class_wrapper = "trans_bg";
		break;
		case "solid":
			$style_wrapper .= "background-color:".$slideBGColor.";";
			$bgSolidPickerProps = 'class="inputColorPicker slide_bg_color" style="background-color:'.$slideBGColor.'"';
		break;
		case "image":
			$style_wrapper .= "background-image:url('".$imageUrl."');";
			if($bgFit == 'percentage'){
				
				$style_wrapper .= "background-size: ".$bgFitX.' '.$bgFitY.';';
			}else{
				$style_wrapper .= "background-size: ".$bgFit.";";
			}
			if($bgPosition == 'percentage'){
				$style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
			}else{
				$style_wrapper .= "background-position: ".$bgPosition.";";
			}
			$style_wrapper .= "background-repeat: ".$bgRepeat.";";
		break;
		case "external":
			$style_wrapper .= "background-image:url('".$slideBGExternal."');";
			if($bgFit == 'percentage'){
				$style_wrapper .= "background-size: ".$bgFitX.' '.$bgFitY.';';
			}else{
				$style_wrapper .= "background-size: ".$bgFit.";";
			}
			if($bgPosition == 'percentage'){
				$style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
			}else{
				$style_wrapper .= "background-position: ".$bgPosition.";";
			}
			$style_wrapper .= "background-repeat: ".$bgRepeat.";";
		break;
	}
		
	$slideTitle = $slide->getParam("title","Slide");
	$slideOrder = $slide->getOrder();

	//treat multilanguage
	$isWpmlExists = UniteWpmlRev::isWpmlExists();	
	$useWpml = $slider->getParam("use_wpml","off");
	$wpmlActive = false;
	if($isWpmlExists && $useWpml == "on"){
		$wpmlActive = true;
		$parentSlide = $slide->getParentSlide();
		$arrChildLangs = $parentSlide->getArrChildrenLangs();
	}
	
	require self::getPathTemplate("slide");
?>
	
