<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Image Handler Controller.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProControllerImageHandler extends JControllerLegacy {

	/**
	 * Method to upload an image.
	 */
	public function upload() {
		// check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		// initialize variables
		$params		= JComponentHelper::getParams('com_advportfoliopro');

		// Get some data from the request
		$input 		= JFactory::getApplication()->input;
		$file		= $input->files->get('image');
		$image_id	= $this->input->get('image_id');
		$folder		= $this->input->getString('folder');
		$folder		= \JFilterInput::getInstance()->clean($folder, 'path');

		// set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= str_replace('{root}', JPATH_ROOT, $params->get('image_upload_path', '{root}/images/advportfoliopro/images')) . '/';
		$return_url	= JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . $folder, false);

		// check if valid upload file
		if (!isset($file['name']) || !AdvPortfolioProImageLib::check($file)) {
			$this->setRedirect($return_url, JText::_('COM_ADVPORTFOLIOPRO_INVALID_IMAGE'));
		}

		// sanitize the image name
		$file_name	= AdvPortfolioProImageLib::sanitize($base_path, $file['name']);
		$file_path	= $base_path . ($folder ? $folder . '/' : '') . $file_name;

		// upload the image
		if (!\JFile::upload($file['tmp_name'], $file_path, false, true)) {
			$this->setRedirect($return_url, JText::_('COM_ADVPORTFOLIOPRO_IMAGE_UPLOAD_FAILED'));
		} else {
			$this->setRedirect($return_url, JText::_('COM_ADVPORTFOLIOPRO_IMAGE_UPLOAD_SUCCESS'));
		}
	}

	/**
	 * Method to ajax upload an image.
	 */
	public function ajaxUpload() {
		// check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		// initialize variables
		$params		= JComponentHelper::getParams('com_advportfoliopro');

		// Get some data from the request
		$input 		= JFactory::getApplication()->input;
		$file		= $input->files->get('image');
		$image_id	= $this->input->get('image_id');
		$folder		= $this->input->getString('folder');
		$folder		= \JFilterInput::getInstance()->clean($folder, 'path');

		// set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= str_replace('{root}', JPATH_ROOT, $params->get('image_upload_path', '{root}/images/advportfoliopro/images')) . '/';
		$return_url	= JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . $folder, false);

		// check if valid upload file
		if (!isset($file['name']) || !AdvPortfolioProImageLib::check($file)) {
			exit;
		}

		// sanitize the image name
		$file_name	= AdvPortfolioProImageLib::sanitize($base_path, $file['name']);
		$file_path	= $base_path . ($folder ? $folder . '/' : '') . $file_name;

		// upload the image
		\JFile::upload($file['tmp_name'], $file_path, false, true);

		$imageName	= ($folder ? $folder . '/' : '') . $file_name;

		$data	= array(
			'name'		=> $imageName,
			'preview'	=> JHtml::_('advportfoliopro.image', $imageName, 200, 200)
		);

		echo json_encode($data);

		exit;
	}

	/**
	 * Method to delete images.
	 */
	public function delete() {
		// initialize variables
		$params		= JComponentHelper::getParams('com_advportfoliopro');

		// Get some data from the request
		$image_id	= $this->input->getString('image_id');
		$images		= $this->input->get('rm', array(), 'array');

		// set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= str_replace('{root}', JPATH_ROOT, $params->get('image_upload_path', '{root}/images/advportfoliopro/images')) . '/';
		$return_url	= JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $image_id, false);

		// Just return if there's nothing to do
		if (empty($images)) {
			$this->setRedirect($return_url, JText::_('JERROR_NO_ITEMS_SELECTED'), 'error');
		}
		
		if (count($images)) {
			$return_url	= JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . dirname($images[0]), false);

			foreach ($images as $image) {
				if ($image !== \JFilterInput::getInstance()->clean($image, 'path')) {
					JError::raiseWarning(100, JText::_('COM_ADVPORTFOLIOPRO_IMAGE_UNABLE_DELETE') . ' ' . htmlspecialchars($image, ENT_COMPAT, 'UTF-8'));
					$this->setRedirect($return_url);
				}

				$full_path	= JPath::clean($base_path . $image);
				if (is_file($full_path)) {
					JFile::delete($full_path);
				}
			}
		}

		$this->setRedirect($return_url, JText::_('COM_ADVPORTFOLIOPRO_IMAGE_DELETE_SUCCESS'));
	}

	/**
	 * Method to delete Folders.
	 */
	public function deleteFolder() {
		// initialize variables
		$params		= JComponentHelper::getParams('com_advportfoliopro');

		// Get some data from the request
		$image_id	= $this->input->getString('image_id');
		$folders	= $this->input->get('rm', array(), 'array');

		// set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= str_replace('{root}', JPATH_ROOT, $params->get('image_upload_path', '{root}/images/advportfoliopro/images')) . '/';
		$return_url	= JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $image_id, false);

		if (count($folders)) {
			$return_url	= JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $image_id, false);

			foreach ($folders as $folder) {
				if ($folder !== \JFilterInput::getInstance()->clean($folder, 'path')) {
					JError::raiseWarning(100, JText::_('COM_ADVPORTFOLIOPRO_FOLDER_UNABLE_DELETE') . ' ' . htmlspecialchars($folder, ENT_COMPAT, 'UTF-8'));
					$this->setRedirect($return_url);
				}

				$full_path	= JPath::clean($base_path . $folder);
				if (is_dir($full_path)) {
					JFolder::delete($full_path);
				}
			}
		}

		$this->setRedirect($return_url, JText::_('COM_ADVPORTFOLIOPRO_FOLDER_DELETE_SUCCESS'));
	}

	/**
	 * Create new folder.
	 */
	public function createFolder() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$file_id	= $this->input->getCmd('image_id');
		$folder		= $this->input->getString('folder');
		$folder		= \JFilterInput::getInstance()->clean($folder, 'path');
		$newFolder	= $this->input->getString('new_folder');
		$newFolder	= JFolder::makeSafe($newFolder, 'path');
		$params		= JComponentHelper::getParams('com_advportfoliopro');
		$basePath	= str_replace('{root}', JPATH_ROOT, $params->get('image_upload_path', '{root}/images/advportfoliopro/images')) . '/';

		if ($newFolder) {
			JFolder::create($basePath . '/' . ($folder ? $folder . '/' : '') . $newFolder);
		}

		$this->setRedirect(JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $file_id . '&folder=' . $folder, false), JText::_('COM_ADVPORTFOLIOPRO_FOLDER_CREATE_SUCCESS'));
	}
}