<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

class GDEnhancerBackground {

	public $format;
	public $resource;
	public $width;
	public $height;

	public function __construct($actions) {
		$this->runActions($actions);
	}

	public function __destruct() {
		@imagedestroy($this->resource);
	}

	protected function runActions($actions) {
		$this->runImage($actions['image']);
		foreach ($actions as $actionname => $action) {
			switch ($actionname) {
				case 'resize':
					$this->runResize($action);
					break;
			}
		}
	}

	protected function runImage($action) {
		$this->format = $action['format'];
		$this->resource = $action['resource'];
		$this->width = $action['width'];
		$this->height = $action['height'];
	}

	protected function runResize($action) {
		$args = GDEnhancerLibrary::getResizeArgs($this->width, $this->height, $action['width'], $action['height'], $action['option']);
		if ($args === false) {
			return;
		}

		$newimage = imagecreatetruecolor($args['dst_w'], $args['dst_h']);
		$doSharpen	= false;

		if ($this->format === 'png') {
			imagealphablending($newimage, false);
			imagesavealpha($newimage, true);
			$transparentindex = imagecolorallocatealpha($newimage, 255, 255, 255, 127);
			imagefill($newimage, 0, 0, $transparentindex);
		} else if ($this->format === 'gif') {
			$transparentindex = imagecolorallocatealpha($newimage, 255, 255, 255, 127);
			imagefill($newimage, 0, 0, $transparentindex);
		} else {
			$doSharpen	= true;
		}

		imagecopyresampled($newimage, $this->resource, $args['dst_x'], $args['dst_y'], $args['src_x'], $args['src_y'], $args['dst_w'], $args['dst_h'], $args['src_w'], $args['src_h']);

		// do sharpen
		if ($doSharpen) {
			// Sharpen the image based on two things:
			//	(1) The difference between the original size and the final size
			//	(2) The final size
			$sharpness = $this->findSharp($args['src_w'], $args['dst_w']);

			$sharpenMatrix = array(
				array(-1, -2, -1),
				array(-2, $sharpness + 12, -2),
				array(-1, -2, -1)
			);

			$divisor = $sharpness;
			$offset = 0;
			imageconvolution($newimage, $sharpenMatrix, $divisor, $offset);
		}

		imagedestroy($this->resource);
		$this->resource = $newimage;
		$this->width = $args['dst_w'];
		$this->height = $args['dst_h'];
	}

	protected function findSharp($orig, $final) {
		$final	= $final * (750.0 / $orig);
		$a		= 52;
		$b		= -0.27810650887573124;
		$c		= .00047337278106508946;

		$result = $a + $b * $final + $c * $final * $final;

		return max(round($result), 0);
	}
}