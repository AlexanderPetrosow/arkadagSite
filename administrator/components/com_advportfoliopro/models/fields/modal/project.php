<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Supports a modal article picker.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolioPro
 */
class JFormFieldModal_Project extends JFormField {
	/** @var	string	The form field type. */
	protected $type = 'Modal_Project';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput() {
		// Load the modal behavior script.
		JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_advportfoliopro/helpers/html');
		JHtml::_('advportfoliopro.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectProject_' . $this->id . '(id, title, price) {';
		$script[] = '		jQuery("#' . $this->id . '_id").val(id);';
		$script[] = '		jQuery("#' . $this->id . '_name").val(title);';
		$script[] = '		jQuery.fancybox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_advportfoliopro&amp;view=projects&amp;layout=modal&amp;tmpl=component&amp;function=jSelectProject_' . $this->id;

		if (isset($this->element['language'])) {
			$link .= '&amp;forcedLanguage='.$this->element['language'];
		}

		$db	= JFactory::getDBO();
		$db->setQuery(
			'SELECT title' .
			' FROM #__advportfoliopro_projects' .
			' WHERE id = '.(int) $this->value
		);

		try {
			$title = $db->loadResult();
		} catch (RuntimeException $e){
			JError::raiseWarning(500, $e->getMessage());
		}

		if (empty($title)) {
			$title = JText::_('COM_ADVPORTFOLIOPRO_SELECT_A_PROJECT');
		}

		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current user display field.
		$html[] = '<span class="input-append input-group">';
		$html[] = '<input type="text" class="input-medium form-control" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" /><a data-fancybox data-type="iframe" class="exmodal btn btn-primary hasTooltip" title="' . JText::_('COM_ADVPORTFOLIOPRO_CHANGE_PROJECT') . '"  href="' . $link . '&amp;' . JSession::getFormToken() . '=1"><i class="icon-file"></i> ' . JText::_('JSELECT') . '</a>';
		$html[] = '</span>';

		// The active article id field.
		if (0 == (int) $this->value) {
			$value = '';
		} else {
			$value = (int) $this->value;
		}

		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
}
