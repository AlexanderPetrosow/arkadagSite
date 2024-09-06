<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

class GDEnhancerBackgroundGIF extends GDEnhancerBackground {

	public $frames;
	protected $actions;

	public function __destruct() {
		foreach ($this->frames as &$frame) {
			@imagedestroy($frame['resource']);
		}
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
		$this->width = $action['width'];
		$this->height = $action['height'];
		$fileobject = GDEnhancerLibrary::getFileObjectFromContents($action['contents']);
		include_once 'GIFSplit.php';
		$gif = new GDEnhancerGIFSplit($fileobject);
		$this->frames = $gif->frames;
	}

	protected function RunResize($action) {
		$args = GDEnhancerLibrary::getResizeArgs($this->width, $this->height, $action['width'], $action['height'], $action['option']);
		if ($args === false) {
			return;
		}
		foreach ($this->frames as &$frame) {
			$newimage = imagecreatetruecolor($args['dst_w'], $args['dst_h']);
			$transparentindex = imagecolorallocatealpha($newimage, 255, 255, 255, 127);
			imagefill($newimage, 0, 0, $transparentindex);
			imagecopyresized($newimage, $frame['resource'], $args['dst_x'], $args['dst_y'], $args['src_x'], $args['src_y'], $args['dst_w'], $args['dst_h'], $args['src_w'], $args['src_h']);
			imagedestroy($frame['resource']);
			$frame['resource'] = $newimage;
		}
		$this->width = $args['dst_w'];
		$this->height = $args['dst_h'];
	}

}