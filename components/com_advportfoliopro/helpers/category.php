<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Category Tree
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */

class AdvPortfolioProCategories extends JCategories {

	public function __construct($options = array()) {
		$options['table'] = '#__advportfoliopro_projects';
		$options['extension'] = 'com_advportfoliopro';

		parent::__construct($options);
	}
}
