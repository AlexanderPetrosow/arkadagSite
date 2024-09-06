<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Category Tree.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioCategories extends JCategories {

	public function __construct($options = array()) {
		$options['table']		= '#__advportfolio_projects';
		$options['extension']	= 'com_advportfolio';

		parent::__construct($options);
	}

}