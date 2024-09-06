<?php
/**
 * @copyright	Copyright (c) 2012 Skyline Software (http://extstore.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

require_once JPATH_SITE . '/components/com_advportfoliopro/helpers/route.php';

/**
 * Search - Advanced Portfoliopro Pro Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	Skyline.Advportfoliopro
 */
class plgSearchAdvportfoliopro extends JPlugin {

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	/**
	 * @return array An array of search areas
	 */
	function onContentSearchAreas() {
		static $areas = array(
			'advportfoliopro' => 'PLG_SEARCH_ADVPORTFOLIOPRO_PROJECTS'
		);

		return $areas;
	}

	/**
	 * Extstore Advanced Portfolio Pro - Project Search method
	 *
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 *
	 * @return array
	 */
	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null) {
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());

		$searchText = $text;

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$sContent	= $this->params->get('search_content', 1);
		$sArchived	= $this->params->get('search_archived', 1);
		$limit		= $this->params->def('search_limit', 50);
		$state		= array();

		if ($sContent) {
			$state[] = 1;
		}

		if ($sArchived) {
			$state[] = 2;
		}

		$text = trim($text);

		if ($text == '') {
			return array();
		}

		$section = JText::_('PLG_SEARCH_ADVPORTFOLIOPRO');

		$wheres = array();

		switch ($phrase) {
			case 'exact':
				$text		= $db->quote('%' . $db->escape($text, true) . '%', false);
				$wheres2	= array();
				$wheres2[]	= 'a.description LIKE ' . $text;
				$wheres2[]	= 'a.short_description LIKE ' . $text;
				$wheres2[]	= 'a.title LIKE ' . $text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';

				break;
			case 'all':
			case 'any':
			default:
				$words	= explode(' ', $text);
				$wheres	= array();

				foreach ($words as $word) {
					$word		= $db->quote('%' . $db->escape($word, true) . '%', false);
					$wheres2	= array();
					$wheres2[]	= 'a.description LIKE ' . $word;
					$wheres2[]	= 'a.short_description LIKE ' . $word;
					$wheres2[]	= 'a.title LIKE ' . $word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering) {
			case 'oldest':
				$order = 'a.created ASC';
				break;

			case 'alpha':
				$order = 'a.title ASC';
				break;

			case 'category':
				$order = 'c.title ASC, a.title ASC';
				break;

			case 'newest':
			default:
				$order = 'a.created DESC';
		}

		$return = array();
		if (!empty($state)) {
			$query = $db->getQuery(true);
			//sqlsrv changes
			$case_when	= ' CASE WHEN ';
			$case_when	.= $query->charLength('a.alias');
			$case_when	.= ' THEN ';
			$a_id		= $query->castAsChar('a.id');
			$case_when	.= $query->concatenate(array($a_id, 'a.alias'), ':');
			$case_when	.= ' ELSE ';
			$case_when	.= $a_id . ' END as slug';

			$case_when1	= ' CASE WHEN ';
			$case_when1	.= $query->charLength('c.alias');
			$case_when1	.= ' THEN ';
			$c_id		= $query->castAsChar('c.id');
			$case_when1	.= $query->concatenate(array($c_id, 'c.alias'), ':');
			$case_when1	.= ' ELSE ';
			$case_when1	.= $c_id . ' END as catslug';

			$query->select('a.title AS title, a.short_description AS text, a.created AS created, ' . $case_when . ',' . $case_when1 . ', ' . $query->concatenate(array($db->Quote($section), "c.title"), " / ") . ' AS section, \'1\' AS browsernav');
			$query->from('#__advportfoliopro_projects AS a');
			$query->innerJoin('#__categories AS c ON c.id = a.catid');
			$query->where('(' . $where . ')' . ' AND a.state in (' . implode(',', $state) . ') AND  c.published=1 AND  c.access IN (' . $groups . ')');
			$query->order($order);

			// Filter by language
			if ($app->isSite() && $app->getLanguageFilter()) {
				$tag = JFactory::getLanguage()->getTag();
				$query->where('a.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
				$query->where('c.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
			}

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			$return = array();
			if ($rows) {
				foreach ($rows as $key => $row) {
					$rows[$key]->href = AdvPortfolioProHelperRoute::getProjectRoute($row->slug, $row->catslug);
				}

				foreach ($rows as $key => $document) {
					if (searchHelper::checkNoHTML($document, $searchText, array('text', 'title'))) {
						$return[] = $document;
					}
				}
			}
		}

		return $return;
	}
}