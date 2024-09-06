<?php
/**
 * @copyright	Copyright (c) 2015 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports custom spacer field.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPoll
 */
class JFormFieldHeader extends JFormField {

	/** @var string		The form field type. */
	protected $type	= 'Header';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput() {
		JHtml::_('stylesheet', 'com_advportfoliopro/admin.style.css', array(), true);
		return '<div class="xheader"><span>' . JText::_($this->element['text']) . '</span></div>';
	}

	protected function getLabel() {
		return '';
	}
}