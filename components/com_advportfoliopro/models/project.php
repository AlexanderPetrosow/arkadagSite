<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Project Model.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProModelProject extends JModelItem {

	/**
	 * @var		string	Model context string.
	 */
	protected $_context = 'com_advportfoliopro.project';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('project.id', $pk);

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$this->setState('filter.state', 1);
		$this->setState('filter.archived', 2);
		$this->setState('filter.access', !$params->get('show_noauth'));

		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}


	/**
	 * Method to get an object.
	 *
	 * @param	integer	The id of the object to get.
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($id = null) {
		$pk	= (!empty($pk)) ? $pk : (int) $this->getState('project.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {
			try {
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);

				$query->select(
					$this->getState('item.select', 'a.*')
				);
				$query->from('#__advportfoliopro_projects AS a');
				$query->where('a.id = ' . (int) $pk);

				// Join over the Categories.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access');
				$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

				// Join on user table.
				$query->select('u.name AS author');
				$query->join('LEFT', '#__users AS u on u.id = a.created_by');


				// Filter by published state.
				$published	= $this->getState('filter.state');
				$archived	= $this->getState('filter.archived');

				if (is_numeric($published)) {
					$query->where('(a.state = ' . (int) $published . ' OR a.state = ' . (int) $archived . ')');
				}

				// Filter by access level.
				if ($access = $this->getState('filter.access')) {
					$user	= JFactory::getUser();
					$groups	= implode(', ', $user->getAuthorisedViewLevels());
					$query->where('a.access IN (' . $groups . ')');
					$query->where('c.access IN (' . $groups . ')');
				}

				$db->setQuery($query);
				$data	= $db->loadObject();

				if (empty($data)) {
					return JError::raiseError(404, JText::_('COM_ADVPORTFOLIOPRO_ERROR_PROJECT_NOT_FOUND'));
				}

				// Check for published state if filter set.
				if (((is_numeric($published)) || (is_numeric($archived))) && (($data->state != $published) && ($data->state != $archived))) {
					return JError::raiseError(404, JText::_('COM_ADVPORTFOLIOPRO_ERROR_PROJECT_NOT_FOUND'));
				}

				// Convert parameter fields to objects
				$registry	= new JRegistry();
				$params		= clone $this->getState('params');
				$registry->loadString($data->params);
				$params->merge($registry);
				$data->params	= $params;

				$registry	= new JRegistry();
				$registry->loadString($data->metadata);
				$data->metadata	= $registry;

				$registry	= new JRegistry();
				$registry->loadString($data->images);
				$data->images	= AdvPortfolioProHelper::getImages($registry->toObject());

				// Compute view access permissions.
				if ($access = $this->getState('filter.access')) {
					// If the access filter has been set, we already know this user can view.
					$data->params->set('access-view', true);
				} else {
					// If no access filter is set, the layout takes some responsibility for display of limited information.
					$user = JFactory::getUser();
					$groups = $user->getAuthorisedViewLevels();

					if ($data->catid == 0 || $data->category_access === null) {
						$data->params->set('access-view', in_array($data->access, $groups));
					} else {
						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
					}
				}

				$this->_item[$pk] = $data;

			} catch(Exception $e) {
				if ($e->getCode() == 404) {
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				} else {
					$this->setError($e);
					$this->_item[$pk]	= false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	 * Method to get next project.
	 */
	public function getNextItem($orderBy = null, $value) {
		$orderBy = (!empty($orderBy)) ? $orderBy : (int) $this->getState('project.id');

		return $this->_getNearItem($orderBy, $value);
	}

	/**
	 * Method to get prev project.
	 */
	public function getPrevItem($orderBy = null, $value) {
		$orderBy = (!empty($orderBy)) ? $orderBy : (int) $this->getState('project.id');

		return $this->_getNearItem($orderBy, $value, false);
	}

	/**
	 * Method to get next/prev project.
	 */
	protected function _getNearItem($orderBy, $value, $next = true) {
		$db			= $this->getDbo();
		$query		= $db->getQuery(true);
		$whereOrder = $this->getWhereOrder($orderBy, $value, $next);

		$item = $this->getItem();

		$query->select('a.id, a.title, a.alias');
		$query->select('CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END AS slug');

		$query->from('#__advportfoliopro_projects AS a');
		$query->where($whereOrder['where']);
		$query->order($whereOrder['order']);

		if ($item->params->get('nav_pro_cat')) {
			$query->where('a.catid=' . $item->catid);
		}

		// Join over the Categories.
		$query->select('CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END AS catslug');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Filter by published state.
		$published	= $this->getState('filter.state');
		$archived	= $this->getState('filter.archived');

		if (is_numeric($published)) {
			$query->where('(a.state = ' . (int) $published . ' OR a.state = ' . (int) $archived . ')');
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$user	= JFactory::getUser();
			$groups	= implode(', ', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		$db->setQuery($query);
		$data	= $db->loadObject();

		return $data;
	}

	protected function getWhereOrder($orderBy, $value, $next = true) {
		$query = array();

		if (strcmp($orderBy,'a.created') == 0) {
			$condition 		= $next ? '>' : '<';
			$query['where'] = "a.created $condition '{$value}'";
			$query['order'] = $next ? 'a.created' : 'a.created DESC';
		} elseif (strcmp($orderBy,'a.created DESC ') == 0) {
			$condition 		= $next ? '<' : '>';
			$query['where'] = "a.created $condition '{$value}'";
			$query['order'] = $next ? 'a.created DESC' : 'a.created';
		} elseif (strcmp($orderBy,'a.title') == 0) {
			$condition 		= $next ? '1' : '-1';
			$query['where'] = "(SELECT STRCMP(a.title, \"{$value}\")) = $condition";
			$query['order'] = $next ? 'a.title' : 'a.title DESC';
		} elseif (strcmp($orderBy, 'a.title DESC') == 0) {
			$condition 		= $next ? '-1' : '1';
			$query['where'] = "(SELECT STRCMP(a.title, \"{$value}\")) = $condition";
			$query['order'] = $next ? 'a.title DESC' : 'a.title';
		} elseif (strcmp($orderBy, 'c.lft, a.ordering') == 0) {
			$condition 		= $next ? '>' : '<';
			$query['where'] = "a.ordering $condition $value";
			$query['order'] = $next ? 'a.ordering' : 'a.ordering DESC';
		}

		return $query;
	}

}