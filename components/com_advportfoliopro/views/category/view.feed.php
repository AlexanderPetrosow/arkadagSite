<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die();

/**
 * View class for a list of products in category.
 *
 * @package		Joomla.Site
 * @subpakage	Skyline.MediaStore
 */

class AdvPortfolioProViewCategory extends JViewCategoryfeed {
	protected $viewName = 'project';
	/**
	 * Method to reconcile non standard names from components to usage in this class.
	 * Typically overriden in the component feed view class.
	 *
	 * @param   object  $item  The item for a feed, an element of the $items array.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function reconcileNames($item){
		parent::reconcileNames($item);

		$item->description = '';
	}
}

