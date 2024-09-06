<?php
/**
 * @copyright	Copyright (c) 2018 JoomlaSoft Ltd (http://joomlasoft.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 * Content Component Query Helper
 *
 * @since  1.5
 */
class AdvPortfolioProHelperQuery {


	/**
	 * Translate an order code to a field for secondary category ordering.
	 *
	 * @param   string  $orderby    The ordering code.
	 * @param   string  $orderDate  The ordering code for the date.
	 *
	 * @return  string  The SQL field(s) to order by.
	 *
	 * @since   1.5
	 */
	public static function orderbyProject($orderby, $orderDate = 'created') {
		$queryDate = self::getQueryDate($orderDate);

		switch ($orderby) {
			case 'date' :
				$orderby = $queryDate;
				break;

			case 'rdate' :
				$orderby = $queryDate . ' DESC ';
				break;

			case 'alpha' :
				$orderby = 'a.title';
				break;

			case 'ralpha' :
				$orderby = 'a.title DESC';
				break;

			case 'order' :
				$orderby = 'a.ordering';
				break;

			case 'rorder' :
				$orderby = 'a.ordering DESC';
				break;

			default :
				$orderby = 'a.ordering';
				break;
		}

		return $orderby;
	}

	/**
	 * Translate an order code to a field for primary category ordering.
	 *
	 * @param   string  $orderDate  The ordering code.
	 *
	 * @return  string  The SQL field(s) to order by.
	 *
	 * @since   1.6
	 */
	public static function getQueryDate($orderDate) {
		$db = JFactory::getDbo();

		switch ($orderDate) {
			case 'modified' :
				$queryDate = ' CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END';
				break;

			// Use created if publish_up is not set
			case 'published' :
				$queryDate = ' CASE WHEN a.publish_up = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END ';
				break;

			case 'unpublished' :
				$queryDate = ' CASE WHEN a.publish_down = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.publish_down END ';
				break;
			case 'created' :
			default :
				$queryDate = ' a.created ';
				break;
		}

		return $queryDate;
	}

}
