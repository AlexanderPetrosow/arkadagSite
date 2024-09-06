<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

$images	= array();

foreach ($this->item->images as $image) {
	$obj			= new stdClass();
	$obj->src		= JHtml::_('advportfoliopro.image', $image->image);
	$obj->caption	= $image->title;

	$images[]		= $obj;
}

echo json_encode($images);