<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Methods supporting a list of Project records.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProModelProjects extends JModelList {
	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 * @see        JControllerLegacy
	 */
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'language', 'a.language',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null) {
		// Initialize variables.
		$app = JFactory::getApplication('site');

		// Load the parameters. Merge Global and Menu Item params into new object
		$params = $app->getParams();
		$menuParams = new JRegistry();

		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);

		$this->setState('params', $mergedParams);

		// List state information
		$value = $app->input->get('limit', $params->get('limit', $app->getCfg('list_limit', 0)), 'uint');
		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);

		$orderCol = $this->_buildContentOrderBy();
		$this->setState('list.ordering', $orderCol);
		$this->setState('list.direction', '');

		//
		$this->setState('filter.state', 1);

		$catid	= $app->input->get('catid', 0, 'uint');

		if ($catid) {
			$this->setState('filter.category_id', $catid);
		} else {
			$this->setState('filter.category_id', $params->get('catids'));
		}

		// process show_noauth parameter
		if (!$params->get('show_noauth')) {
			$this->setState('filter.access', true);
		} else {
			$this->setState('filter.access', false);
		}

		// Optional filter text
		$this->setState('list.filter', $app->input->getString('filter-search'));

		$this->setState('filter.language', JLanguageMultilang::isEnabled());

		$this->setState('layout', $app->input->get('layout'));
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and different modules
	 * that might need different sets of data or different ordering requirements.
	 *
	 * @param    string $id    A prefix for the store id.
	 * @return    string        A store id.
	 */
	protected function getStoreId($id = '') {
		// compile the store id.
		$id .= ':' . $this->getState('list.filter');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . serialize($this->getState('filter.project_id'));
		$id .= ':' . serialize($this->getState('filter.category_id'));
		$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 */
	protected function getListQuery() {
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.alias, a.checked_out, a.checked_out_time, a.catid,
				 a.state, a.access, a.ordering, a.params, a.images,
				 a.language, a.created,
				 a.short_description, a.thumbnail, a.link, a.featured'
			)
		);

		$query->from('#__advportfoliopro_projects AS a');

		$query->select('CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END AS slug');

		// Join over the Categories.
		$query->select('c.title AS category_title, c.alias AS category_alias, c.lft AS category_ordering');
		$query->select('CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END AS catslug');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$user = JFactory::getUser();
			$groups = implode(', ', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published)) {
			$query->where('a.state = ' . $published);
		} else if (is_array($published)) {
			ArrayHelper::toInteger($published);
			$published = implode(', ', $published);
			$query->where('a.state IN (' . $published . ')');
		}

		// Filter by single or group of projects.
		$projectId = $this->getState('filter.project_id');

		if (is_numeric($projectId)) {
			$type = $this->getState('filter.project_id.include', true) ? '= ' : '<> ';
			$query->where('a.id ' . $type . (int)$projectId);
		} else if (is_array($projectId)) {
			ArrayHelper::toInteger($projectId);
			$projectId = implode(', ', $projectId);
			$type = $this->getState('filter.project_id.include', true) ? 'IN ' : 'NOT IN';
			$query->where('a.id ' . $type . '(' . $projectId . ')');
		}

		// Filter by signle or group of categories
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId)) {
			$type = $this->getState('filter.category_id.include', true) ? '= ' : '<> ';

			// Add subcategory check
			$includeSubcategories = $this->getState('filter.subcategories', false);
			$categoryEquals = 'a.catid ' . $type . (int)$categoryId;

			if ($includeSubcategories) {
				$levels = (int)$this->getState('filter.max_category_levels', '1');
				// Create a subquery for the subcategory list
				$subQuery = $db->getQuery(true);
				$subQuery->select('sub.id');
				$subQuery->from('#__categories as sub');
				$subQuery->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt');
				$subQuery->where('this.id = ' . (int)$categoryId);

				if ($levels >= 0) {
					$subQuery->where('sub.level <= this.level + ' . $levels);
				}

				// Add the subquery to the main query
				$query->where('(' . $categoryEquals . ' OR a.catid IN (' . $subQuery->__toString() . '))');
			} else {
				$query->where($categoryEquals);
			}
		} else if (is_array($categoryId) && (count($categoryId) > 0)) {
			ArrayHelper::toInteger($categoryId);
			$categoryId = implode(', ', $categoryId);
			if (!empty($categoryId)) {
				$type = $this->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';
				$query->where('a.catid ' . $type . ' (' . $categoryId . ')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}


		// Add the list ordering clause.
		$query->order($this->getState('list.ordering', 'a.ordering') . ' ' . $this->getState('list.direction', 'ASC'));

		return $query;
	}

	/**
	 * Build the orderby for the query
	 *
	 * @return    string    $orderby portion of query
	 */
	protected function _buildContentOrderBy() {
		$app = JFactory::getApplication('site');
		$db = $this->getDbo();
		$params = $this->state->params;

		$orderby = $params->get('orderby', 'rdate');

		switch ($orderby) {
			case 'date':
				$orderby = 'a.created';
				break;

			case 'rdate':
				$orderby = 'a.created DESC ';
				break;

			case 'alpha':
				$orderby = 'a.title';
				break;

			case 'ralpha':
				$orderby = 'a.title DESC';
				break;

			case 'order' :
				$orderby = 'a.ordering';
				break;

			case 'rorder' :
				$orderby = 'a.ordering DESC';
				break;
			default :
				$orderby = 'c.lft, a.ordering';
				break;
		}

		return $orderby;
	}

	public function getItems() {
		$items = parent::getItems();
		foreach ($items as &$item) {
			$item->tags	= array();
			$tags 		= new JHelperTags();
			$tags		= $tags->getItemTags('com_advportfoliopro.project', $item->id);

			foreach ($tags as $tag) {
				$tmp			= new stdClass();
				$tmp->title		= $tag->title;
				$tmp->alias		= $tag->alias;
				$tmp->ordering	= $tag->lft;
				$item->tags[]	= $tmp;
			}
		}

		return $items;
	}
}