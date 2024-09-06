<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * ExtStore Advanced Portfolio Pro Component - Project Table Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProTableProject extends JTable {

	/**
	 * Constructor.
	 *
	 * @param	JDatabase	A database connector object.
	 */
	public function __construct(&$db) {

		$JVersion	= new JVersion;

		if (version_compare($JVersion->getShortVersion(), '4.0', '>=')) {
			$this->typeAlias = 'com_advportfoliopro.project';
			parent::__construct('#__advportfoliopro_projects', 'id', $db);
		} else {
			parent::__construct('#__advportfoliopro_projects', 'id', $db);
			JTableObserverTags::createObserver($this, array('typeAlias' => 'com_advportfoliopro.project'));
			JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_advportfoliopro.project'));

		}

	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param	array		Named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error.
	 * @see		JTable:bind
	 */
	public function bind($array, $ignore = '') {
		if (isset($array['params']) && is_array($array['params'])) {
			$registry			= new JRegistry();
			$registry->loadArray($array['params']);
			$array['params']	= (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (isset($array['images']) && is_array($array['images'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['images']);
			$array['images'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overload the store method for the Project table.
	 *
	 * @param	boolean	Toggle whether null values should be updated.
	 * @return	boolean	True on success, false on failure.
	 */
	public function store($updateNulls = false) {
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		if ($this->id) {
			// Existing item
			$this->modified		= $date->toSql();
			$this->modified_by	= $user->get('id');
		} else {
			// New Project. A Project created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (! (int) $this->created) {
				$this->created	= $date->toSql();
			}

			if (empty($this->created_by)) {
				$this->created_by	= $user->get('id');
			}

		}

		// Verify that the alias is unique
		$table	= JTable::getInstance('Project', 'AdvPortfolioProTable');
		if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0)) {
			$this->setError(JText::_('COM_ADVPORTFOLIOPRO_PROJECT_ERROR_UNIQUE_ALIAS'));
			return false;
		}

		// Attempt to store the user data.
		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return	boolean		True on success.
	 */
	public function check() {
		// check for valid name
		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_ADVPORTFOLIOPRO_PROJECT_ERROR_TABLES_TITLE'));
			return false;
		}

		// check for existing name
		$query = 'SELECT id'
				. ' FROM #__advportfoliopro_projects'
				. ' WHERE title = ' . $this->_db->quote($this->title) . ' AND catid = ' . (int) $this->catid
		;
		$this->_db->setQuery($query);

		$xid	= intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::_('COM_ADVPORTFOLIOPRO_PROJECT_ERROR_TABLES_NAME'));
			return false;
		}

		// check for valid video link
		if ($this->type == 1) {
			require JPATH_ADMINISTRATOR . '/components/com_advportfoliopro/helpers/video.php';

			if (!AdvPortfolioProVideoHelper::check($this->video_link)) {
				$this->setError(JText::_('COM_ADVPORTFOLIOPRO_PROJECT_ERROR_VIDEO_LINK'));
				return false;
			}
		} else {
			if ($this->type == 1 && !preg_match('/^(http:\/\/)/', $this->video_link)) {
				$this->video_link = 'http://' . $this->video_link;
			}
		}

		if (empty($this->alias)) {
			$this->alias = $this->title;
		}

		$this->alias	= JApplicationHelper::stringURLSafe($this->alias);
		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		// clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey)) {
			// only process if not empty
			$bad_characters = array("\n", "\r", '"', '<', '>');
			$after_clean	= JString::str_ireplace($bad_characters, '', $this->metakey);
			$keys			= explode(',', $after_clean);
			$clean_keys		= array();

			foreach ($keys as $key) {
				if (trim($key)) {
					$clean_keys[]	= trim($key);
				}
			}

			$this->metakey	= implode(', ', $clean_keys);
		}

		return true;
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to check-in rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 */
	public function publish($pks = null, $state = 1, $userId = 0) {
		$k = $this->_tbl_key;

		// Sanitize input.
		$pks = ArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			} else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

				return false;
			}
		}

		$query = $this->_db->getQuery(true)
						   ->update($this->_db->quoteName($this->_tbl))
						   ->set($this->_db->quoteName('state') . ' = ' . (int) $state);

		// Build the WHERE clause for the primary keys.
		$query->where($k . '=' . implode(' OR ' . $k . '=', $pks));

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = false;
		} else {
			$query->where('(checked_out = 0 OR checked_out = ' . (int) $userId . ')');
			$checkin = true;
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery($query);

		try {
			$this->_db->execute();
		} catch (RuntimeException $e) {
			$this->setError($this->_db->getMessage());

			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			// Checkin the rows.
			foreach ($pks as $pk) {
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');

		return true;
	}

}