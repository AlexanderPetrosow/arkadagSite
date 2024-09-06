<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

JLoader::register('AdvPortfolioProHelper', JPATH_ADMINISTRATOR . '/components/com_advportfoliopro/helpers/advportfoliopro.php');


/**
 * Project Model.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProModelProject extends JModelAdmin {
	/** @var string		The type alias for this content type (for example, 'com_content.article'). */
	public $typeAlias = 'com_advportfoliopro.project';

	/** @var string		The prefix to use with controller messages. */
	protected $text_prefix	= 'COM_ADVPORTFOLIOPRO_PROJECTS';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	bool	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record) {
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return;
			}

			$user = JFactory::getUser();

			if ($record->catid) {
				return $user->authorise('core.delete', 'com_advportfoliopro.category.' . (int) $record->catid);
			} else {
				return parent::canDelete($record);
			}
		}
	}

	/**
	 * Method to test whether a record can have its state changed.
	 *
	 * @param	object	A record object.
	 * @return	bool	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record) {
		$user	= JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_advportfoliopro.category.' . (int) $record->catid);
		} else {
			return parent::canEditState($record);
		}
	}
	
	/**
	 * Returns a reference to a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @pararm	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'Project', $prefix = 'AdvPortfolioProTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm				A JForm object on success, false on failure.
	 */
	public function getForm($data = array(), $loadData = true) {
		// Initialize variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form	= $this->loadForm('com_advportfoliopro.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('project.id')) {
			// Existing record. Can only edit in selected Categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected Categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable field while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed	The data for the form.
	 */
	protected function loadFormData() {

		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data	= $app->getUserState('com_advportfoliopro.edit.project.data', array());

		if (empty($data)) {
			$data	= $this->getItem();

			// Prime some default values.
			if ($this->getState('project.id') == 0) {

				$filters = (array) $app->getUserState('com_advportfoliopro.projects.filter');
				$data->set(
					'state',
					$app->input->getInt(
						'state',
						((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
					)
				);
				$data->set('catid', $app->input->getInt('catid', $app->getUserState('com_advportfoliopro.projects.filter.category_id')));

			}
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	int		The id of the primary key.
	 * @return	mixed	Object on success, false, on failure.
	 */
	public function getItem($pk = null) {
		if ($item	= parent::getItem($pk)) {
			// Convert the metadata field to an array.
			$registry = new Registry($item->metadata);
			$item->metadata	= $registry->toArray();

			// Convert the images field to an array.
			$registry = new Registry($item->images);
			$item->images = $registry->toArray();

			if (!empty($item->id)) {
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_advportfoliopro.project');
			}
		}

		return $item;
	}

	/**
	 * Prepare and sanitize the table prior to saving.
	 */
	protected function prepareTable($table) {
		// Reorder the Projects within the category so the new Project is first
		if (empty($table->id)) {
			$table->reorder('catid = ' . (int) $table->catid . ' AND state >= 0');
		}
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to ordering queries.
	 */
	protected function getReorderConditions($table) {
		return array('catid = ' . (int) $table->catid);
	}

	protected function preprocessForm(\JForm $form, $data, $group = 'content') {
		// Determine correct permissions to check.
		if ($this->getState('project.id')) {
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		if ($this->canCreateCategory()) {
			$form->setFieldAttribute('catid', 'allowAdd', 'true');
		}

		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Is the user allowed to create an on the fly category?
	 *
	 * @return  boolean
	 *
	 * @since   3.6.1
	 */
	private function canCreateCategory() {
		return \JFactory::getUser()->authorise('core.create', 'com_advportfoliopro');
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since	3.1
	 */
	public function save($data) {
		$app = JFactory::getApplication();
		$JVersion	= new JVersion;

		if (version_compare($JVersion->getShortVersion(), '3.6', '>=')) {
			JLoader::register('CategoriesHelper', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/categories.php');

			// Cast catid to integer for comparison
			$catid = (int) $data['catid'];

			// Check if New Category exists
			if ($catid > 0) {
				$catid = CategoriesHelper::validateCategoryId($data['catid'], 'com_advportfoliopro');
			}

			// Save New Category
			if ($catid == 0) {
				$table = array();
				$table['title'] = $data['catid'];
				$table['parent_id'] = 1;
				$table['extension'] = 'com_advportfoliopro';
				$table['language'] = $data['language'];
				$table['published'] = 1;

				// Create new category and get catid back
				$data['catid'] = CategoriesHelper::createCategory($table);
			}
		}

		// Alter the title for save as copy
		if ($app->input->get('task') == 'save2copy') {
			$origTable = clone $this->getTable();
			$origTable->load($app->input->getInt('id'));

			if ($data['title'] == $origTable->title) {
				list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			} else {
				if ($data['alias'] == $origTable->alias) {
					$data['alias'] = '';
				}
			}

			$data['state'] = 0;
		}

		return parent::save($data);
	}

	/**
	 * Method to toggle the featured setting of projects.
	 *
	 * @param   array    $pks    The ids of the items to toggle.
	 * @param   integer  $value  The value to toggle to.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function featured($pks, $value = 0) {
		// Sanitize the ids.
		$pks = ArrayHelper::toInteger((array) $pks);

		if (empty($pks)) {
			$this->setError(JText::_('COM_ADVPORTFOLIOPRO_NO_ITEM_SELECTED'));

			return false;
		}

		$table = $this->getTable();

		try {
			$db = $this->getDbo();

			$query = $db->getQuery(true);
			$query->update('#__advportfoliopro_projects');
			$query->set('featured = ' . (int) $value);
			$query->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);

			$db->execute();
		} catch (Exception $e) {
			$this->setError($e->getMessage());

			return false;
		}

		$table->reorder();

		// Clean component's cache
		$this->cleanCache();

		return true;
	}
}