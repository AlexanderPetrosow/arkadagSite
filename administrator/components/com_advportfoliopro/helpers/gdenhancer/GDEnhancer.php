<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

include_once 'models' . DIRECTORY_SEPARATOR . 'Actions.php';
include_once 'models' . DIRECTORY_SEPARATOR . 'Run.php';

class GDEnhancer {

	protected $actions;

	public function __construct($images) {
		$this->actions = new GDEnhancerActions($images);
	}

	public function backgroundResize($width, $height, $option = 'shrink') {
		$this->actions->backgroundResize($width, $height, $option);
	}

	public function backgroundFill($color) {
		$this->actions->backgroundFill($color);
	}

	public function layerText($text, $fontfile, $fontsize, $fontcolor, $angle = 0, $linespacing = 1) {
		$this->actions->layerText($text, $fontfile, $fontsize, $fontcolor, $angle, $linespacing);
	}

	public function layerImage($image) {
		$this->actions->layerImage($image);
	}

	public function layerMove($key, $alignment, $x = 0, $y = 0) {
		$this->actions->layerMove($key, $alignment, $x, $y);
	}

	public function layerTextBlock($key, $blockpadding, $blockcolor) {
		$this->actions->layerTextBlock($key, $blockpadding, $blockcolor);
	}

	public function layerImageResize($key, $width, $height, $option = 'shrink') {
		$this->actions->layerImageResize($key, $width, $height, $option);
	}

	public function save($format = 'default', $flag = true, $quality = 100) {
		$this->actions->saveFormat($format);
		$this->actions->GIFFlag($flag);
		$run = new GDEnhancerRun($this->actions, $quality);

		return $run->save;
	}

	/**
	 * Save file to disk
	 * Use one of the save() or saveTo() method
	 * @param $basename
	 * @param string $format
	 * @param bool $flag
	 * @param int $quality
	 */
	public function saveTo($basename, $format = 'default', $flag = true, $quality = 100) {
		$save = $this->save($format, $flag, $quality);

		// Writing file
		file_put_contents($basename . '.' . $save['extension'], $save['contents']);

		// Return save for direct use
		return $save;
	}

}
