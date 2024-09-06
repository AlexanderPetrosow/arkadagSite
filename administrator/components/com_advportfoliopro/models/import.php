<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/categoryadvportfolio.php';
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables/project.php');

/**
 * Import Model Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProModelImport extends JModelLegacy {

	protected $_catmap = array();

	public function getFreeCategories() {
		$category	= JCategories::getInstance('AdvPortfolio');
		$categories = $category->get()->getChildren(true);

		return $categories;
	}

	public function copyCategories() {
		$flag		= false;
		$categories = $this->getFreeCategories();

		foreach ($categories as $cate) {
			if ($this->checkCategory($cate->id, $cate->title)) {
				$table	= JTableCategory::getInstance('category');
				$data	= array();

				$data['asset_id']				= $cate->asset_id;
				$data['parent_id']				= $cate->parent_id;
				$data['lft']					= $cate->lft;
				$data['rgt']					= $cate->rgt;
				$data['level']					= $cate->level;
				$data['path']					= $cate->path;
				$data['extension']				= 'com_advportfoliopro';
				$data['title']					= $cate->title;
				$data['alias'] 					= $cate->alias;
				$data['note']					= $cate->note;
				$data['description']			= $cate->description;
				$data['published']				= $cate->published;
				$data['checked_out']			= $cate->checked_out;
				$data['checked_out_time']		= $cate->checked_out_time;
				$data['access']					= $cate->access;
				$data['params']					= $cate->params;
				$data['metadesc']				= $cate->metadesc;
				$data['metakey']				= $cate->metakey;
				$data['metadata']				= $cate->metadata;
				$data['created_user_id']		= $cate->created_user_id;
				$data['created_time']			= $cate->created_time;
				$data['modified_user_id']		= $cate->modified_user_id;
				$data['modified_time']			= $cate->modified_time;
				$data['hits']					= $cate->hits;
				$data['language']				= $cate->language;
				$data['version']				= $cate->version;

				$table->setLocation($data['parent_id'], 'last-child');
				$table->bind($data);

				if ($table->check()) {
					$flag = $table->store();
					$this->_catmap[$cate->id] = $table->id;
				}
			}
		}

		return $flag;
	}

	public function getFreeProjects() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('*')->from('#__advportfolio_projects');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function copyProjects() {
		$flag		= false;
		$projects	= $this->getFreeProjects();

		foreach ($projects as $project) {
			if ($this->checkProject($project->title)) {
				$table	= JTable::getInstance('Project', 'AdvPortfolioProTable');
				$data	= array();

				if (in_array($project->catid, array_keys($this->_catmap))) {
					$data['catid'] = $this->_catmap[$project->catid];
				}

				$data['title']				= $project->title;
				$data['alias']				= $project->alias;
				$data['description']		= $project->description;
				$data['state']				= $project->state;
				$data['checked_out']		= $project->checked_out;
				$data['checked_out_time']	= $project->checked_out_time;
				$data['ordering']			= $project->ordering;
				$data['access']				= $project->access;
				$data['language']			= $project->language;
				$data['created']			= $project->created;
				$data['created_by']			= $project->created_by;
				$data['created_by_alias']	= $project->created_by_alias;
				$data['modified']			= $project->modified;
				$data['modified_by']		= $project->modified_by;
				$data['params']				= $project->params;
				$data['metakey']			= $project->metakey;
				$data['metadesc']			= $project->metadesc;
				$data['metadata']			= $project->metadata;
				$data['images']				= $project->images;
				$data['short_description']	= $project->short_description;
				$data['thumbnail']			= $project->thumbnail;
				$data['link']				= $project->link;
				$data['type']				= $project->type;
				$data['video_link']			= $project->video_link;
				$data['featured']			= 0;

				$table->bind($data);

				if ($table->check()) {
					$flag = $table->store();

					//copy tags to project
					$tagsHelper 			= new JHelperTags();
					$tagsHelper->typeAlias 	= 'com_advportfoliopro.project';
					$tagids 				= $tagsHelper->getTagIds($project->id, 'com_advportfolio.project');
					$arr_tagids 			= explode(',', $tagids);

					$tagsHelper->preStoreProcess($table, $arr_tagids);
					$tagsHelper->postStoreProcess($table, $arr_tagids);
				}
			}
		}

		return $flag;
	}

	public function copyImages() {
		$flag				= false;
		$params				= JComponentHelper::getParams('com_advportfoliopro');
		$sourcePath			= JPath::clean(JPATH_ROOT . '/images/advportfolio/images/');
		$destinationPath	= str_replace('{root}', JPATH_ROOT, $params->get('image_upload_path', '{root}/images/advportfoliopro/images/'));
		$sourceFiles		= JFolder::files($sourcePath);

		foreach ($sourceFiles as $file) {
			$flag = JFile::copy($sourcePath . $file, $destinationPath . $file);
		}

		return $flag;
	}

	public function updateParentId() {
		$flag	= false;
		$table	= JTableCategory::getInstance('category');

		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('parent_id')->from('#__categories')
			->where('extension = ' . $db->quote('com_advportfoliopro'))
		;

		$db->setQuery($query);
		$parent_ids = array_unique($db->loadColumn());

		foreach ($parent_ids as $parent) {
			if (in_array($parent, array_keys($this->_catmap))) {
				$data	= $this->_catmap[$parent];
				$query	= $db->getQuery(true);

				$query->update('#__categories')
					->set('parent_id = ' . (int) $data)
					->where('extension = ' . $db->quote('com_advportfoliopro'))
					->where('parent_id = ' . (int) $parent)
				;

				$db->setQuery($query);
				$flag = $db->execute();
			}
		}

		if ($flag) {
			$table->rebuild();
		}
	}

	public function checkProject($title) {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('id')->from('#__advportfoliopro_projects')
			->where('title = ' . $db->quote($title))
		;

		$db->setQuery($query);

		if ($db->loadResult()) {
			return false;
		} else {
			return true;
		}
	}

	public function checkCategory($catid, $title) {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('id')->from('#__categories')
			->where('title = ' . $db->quote($title))
			->where('extension = ' . $db->quote('com_advportfoliopro'))
		;

		$db->setQuery($query);

		if ($id = $db->loadResult()) {
			$this->_catmap[$catid] = $id;
			return false;
		} else {
			return true;
		}
	}

}