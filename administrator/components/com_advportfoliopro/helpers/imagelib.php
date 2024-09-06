<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Image Library.
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProImageLib {

	/**
	 * Get version of GD library.
	 * @access	public
	 * @since	1.0
	 */
	public static function getGDVersion($user_ver = 0) {
		if (!extension_loaded('gd')) {
			return;
		}

		static $gd_ver	= 0;

		// just accept the specified setting if it's 1.
		if ($user_ver == 1) {
			$gd_ver = 1;
			return 1;
		}

		// use static variable if function was cancelled previously.
		if ($user_ver != 2 && $gd_ver > 0) {
			return $gd_ver;
		}

		// use the gd_info() function if posible.
		if (function_exists('gd_info')) {
			$ver_info = gd_info();
			$match = null;
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];

			return $match[0];
		}

		// if phpinfo() is disabled use a specified / fail-safe choice...
		if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
			if ($user_ver == 2) {
				$gd_ver = 2;
				return 2;
			} else {
				$gd_ver = 1;
				return 1;
			}
		}
		// ...otherwise use phpinfo().
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		$info = stristr($info, 'gd version');
		$match = null;
		preg_match('/\d/', $info, $match);
		$gd_ver = $match[0];

		return $match[0];
	}

	/**
	 * Get real image width and height to resize.
	 * @access	public
	 * @since	1.0
	 */
	public static function getSize($image, $width, $height) {
		$info = @getimagesize($image);	// width = info[0], height = info[1]

		if ($info[0] < $width && $info[1] < $height) {
			return array($info[0], $info[1]);
		}

		if ($info[0] / $width > $info[1] / $height) {
			$percentage = $width / $info[0];
		} else {
			$percentage = $height / $info[1];
		}

		return array(round($info[0] * $percentage), round($info[1] * $percentage));
	}

	/**
	 * Get real size.
	 * @access	public
	 * @since	1.0
	 */
	public static function imageResize($width, $height, $max_width, $max_height) {
		if ($width < $max_width && $height < $max_height) {
			return array($width, $height);
		}

		if ($width / $max_width > $height / $max_height) {
			$percentage = $max_width / $width;
		} else {
			$percentage = $max_height / $height;
		}

		return array(round($width * $percentage), round($height * $percentage));
	}

	/**
	 * Get image filename to upload.
	 * @access	public
	 * @since	1.0
	 */
	public static function sanitize($base_dir, $filename) {
		jimport('joomla.filesystem.file');

		//check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
		$filename = preg_replace("/^[.]*/", '', $filename);
		$filename = preg_replace("/[.]*$/", '', $filename); //shouldn't be necessary, see above

		//we need to save the last dot position cause preg_replace will also replace dots
		$lastdotpos = strrpos($filename, '.');

		//replace invalid characters
		$chars = '[^0-9a-zA-Z()_-]';
		$filename 	= strtolower(preg_replace("/$chars/", '_', $filename));

		//get the parts before and after the dot (assuming we have an extension...check was done before)
		$beforedot	= substr($filename, 0, $lastdotpos);
		$afterdot 	= substr($filename, $lastdotpos + 1);

		//make a unique filename for the image and check it is not already taken
		//if it is already taken keep trying till success
		$now = time();

		while (JFile::exists($base_dir . $beforedot . '_' . $now . '.' . $afterdot)) {
			$now++;
		}

		//create out of the seperated parts the new filename
		$filename = $beforedot . '_' . $now . '.' . $afterdot;

		return $filename;
	}

	/**
	 * Add image subfix.
	 * @access	public
	 * @since	1.0
	 */
	public static function addSubfix($filename, $subfix) {
		//check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
		$filename = preg_replace("/^[.]*/", '', $filename);
		$filename = preg_replace("/[.]*$/", '', $filename); //shouldn't be necessary, see above

		//we need to save the last dot position cause preg_replace will also replace dots
		$lastdotpos = strrpos($filename, '.');

		//replace invalid characters
		$chars = '[^0-9a-zA-Z()_-]';
		$filename 	= strtolower(preg_replace("/$chars/", '_', $filename));

		//get the parts before and after the dot (assuming we have an extension...check was done before)
		$beforedot	= substr($filename, 0, $lastdotpos);
		$afterdot 	= substr($filename, $lastdotpos + 1);

		$filename = $beforedot . '_' . $subfix . '.' . $afterdot;

		return $filename;
	}

	/**
	 * Check image for uploading.
	 * @access	public
	 * @since	1.0
	 */
 	public static function check($file) {
 		jimport('joomla.filesystem.file');

		$allowable	= explode(',', str_replace(' ', '', 'jpg, jpeg, png, gif'));

 		// $sizelimit 	= $maxsize * 1024; //size limit in kb
		$imagesize 	= $file['size'];

		// check if the upload is an image...getimagesize will return false if not
		if (!getimagesize($file['tmp_name'])) {
			JError::raiseWarning(100, JText::_('ADVPORTFOLIOPRO_IMAGEHANDLER_FILE_ERROR') . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
			return false;
		}

		// check if the imagefiletype is valid
		$fileext 	= strtolower(JFile::getExt($file['name']));
		
		if (!in_array(strtolower($fileext), $allowable)) {
			$allowed_exts = implode(', ', $allowable);
			JError::raiseWarning(100, JText::printf('ADVPORTFOLIOPRO_EXTENSIONS_ALLOWS', $allowed_exts).': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
			return false;
		}

		/*
		// check filesize
		if ($imagesize > $sizelimit) {
			JError::raiseWarning(100, JText::_('File size is wrong').': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
			return false;
		}
		*/

		// XSS check
		$xss_check =  JFile::read($file['tmp_name'], false, 256);
		$html_tags = array('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if(stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				JError::raiseWarning(100, JText::_('COM_ADVPORTFOLIOPRO_IE_XSS_WARNING'));
				return false;
			}
		}

		return true;
 	}

	/**
	 * Process Image
	 */
	public static function process($image, $width, $height, $config) {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$quality		= $config->get('image_quality', 70);
		$folder			= str_replace('{root}', JPATH_ROOT, $config->get('image_upload_path', '{root}/images/advportfoliopro/images')) . '/';
		$destFolder		= str_replace('{root}', JPATH_ROOT, $config->get('image_cache_path', '{root}/cache/advportfoliopro'));
		$image			= $folder . ltrim($image, '/');

		if (!JFile::exists($image)) {
			return false;
		}

		if (!JFolder::exists($destFolder)) {
			JFolder::create($destFolder);
		}

		if (!is_readable($destFolder) || !is_writable($destFolder)) {
			return false;
		}

		// We don't want to run out of memory
		@ini_set('memory_limit', '9999M');

		// Get the size and MINE type of the requested image
		$size	= getimagesize($image);
		$mime	= $size['mime'];

		// Make sure that the requested file is actually an image
		if (substr($mime, 0, 6) != 'image/') {
			return false;
		}

		$iWidth		= $size[0];
		$iHeight	= $size[1];

		if ($width && $height && ($iWidth > $width || $iHeight > $height)) {
			$ratioComputed		= $iWidth / $iHeight;
			$cropRatioComputed	= $width / $height;

			if ($ratioComputed < $cropRatioComputed) {
				// Image is too tall so we will crop the top and bottom
				$oriHeight	= $iHeight;

				if ($iWidth < $width) {
					$iHeight	= $height;
				} else {
					$iHeight	= $iWidth / $cropRatioComputed;
				}

				$offsetY	= ($oriHeight - $iHeight) / 2;
			} else if ($ratioComputed > $cropRatioComputed) {
				// Image is too wide so we will crop off the left and right sides
				$oriWidth	= $iWidth;

				if ($iHeight < $height) {
					$iWidth	= $width;
				} else {
					$iWidth	= $iHeight * $cropRatioComputed;
				}

				$offsetX	= ($oriWidth - $iWidth) / 2;
			}
		}

		$width		= $width ? $width : $iWidth;
		$height		= $height ? $height : $iHeight;

		if ($iWidth < $width && $iHeight < $height) {
			$tnWidth	= $iWidth;
			$tnHeight	= $iHeight;
		} else {
			// Setting up the ratios needed for resizing. We will compare these below to determine how to
			// resize the image (based on width or based on height).
			$xRatio		= $width / $iWidth;
			$yRatio		= $height / $iHeight;

			if ($xRatio * $iHeight < $height) {
				// Resize the image based on width
				$tnHeight	= ceil($xRatio * $iHeight);
				$tnWidth	= $width;
			} else {
				$tnWidth	= ceil($yRatio * $iWidth);
				$tnHeight	= $height;
			}
		}

		// Prepare cache name
		$time	= filemtime($image);
		$name	= str_replace('.', '_', basename($image));
		$ext	= strtolower(JFile::getExt($image));

		$watermark				= $config->get('watermark', 0);
		$watermark_type			= $config->get('watermark_type', 'image');
		$watermark_image		= $config->get('watermark_image');
		$watermark_width		= (int) $config->get('watermark_width');
		$watermark_height		= (int) $config->get('watermark_height');
		$watermark_text			= $config->get('watermark_text');
		$watermark_font			= $config->get('watermark_font', 'arial.ttf');
		$watermark_font_size	= (int) $config->get('watermark_font_size', 10);
		$watermark_font_color	= $config->get('watermark_font_color', '#000000');
		$watermark_position		= $config->get('watermark_position', 'bottomright');
		$watermark_offset_x		= (int) $config->get('watermark_offset_x');
		$watermark_offset_y		= (int) $config->get('watermark_offset_y');

		$hash	= $name . '_' . md5($image . $time . $tnWidth . $tnHeight . $quality . $watermark . $watermark_type . $watermark_image . $watermark_width . $watermark_height
			. $watermark_text . $watermark_font .$watermark_font_size . $watermark_font_color . $watermark_position . $watermark_offset_x . $watermark_offset_y
		);
		$file	= $hash . '.' . $ext;
		$save	= $destFolder . '/' . $file;

		if (JFile::exists($save)) {
			return $file;
		}

		// resize
		if (!class_exists('GDEnhancer')) {
			require_once __DIR__ . '/gdenhancer/GDEnhancer.php';
		}

		$image	= new GDEnhancer($image);
		$image->backgroundResize($tnWidth, $tnHeight, 'fill');

		// watermark

		if (!JFactory::getApplication()->isAdmin() && $watermark) {
			$watermark_enable		= false;

			if ($watermark_type != 'text') {
				if ($watermark_image && JFile::exists(JPATH_ROOT . '/' . $watermark_image)) {
					$watermark_enable	= true;
					$image->layerImage(JPATH_ROOT . '/' . $watermark_image);

					if ($watermark_width && $watermark_height) {
						$image->layerImageResize(0, $watermark_width, $watermark_height);
					}
				}
			} else {
				if ($watermark_text) {
					$watermark_enable	= true;
					$image->layerText($watermark_text, JPATH_ROOT . '/media/com_advportfoliopro/fonts/' . $watermark_font, $watermark_font_size, $watermark_font_color);
				}
			}

			if ($watermark_enable) {
				$image->layerMove(0, $watermark_position, $watermark_offset_x, $watermark_offset_y);
			}
		}

		$image->saveTo($destFolder . '/' . $hash, 'default', true, $quality);

		return $file;
	}
}