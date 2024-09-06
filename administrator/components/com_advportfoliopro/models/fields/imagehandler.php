<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Provides a modal image selector including upload mechanism.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class JFormFieldImageHandler extends JFormField {

	/** @var string		The form field type. */
	protected $type	= 'ImageHandler';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput() {
		JHtml::_('jquery.framework');
		JHtml::_('advportfoliopro.modal');
		JHtml::_('script', 'com_advportfoliopro/admin.script.js', array('version' => 'auto', 'relative' => true));
		JHtml::_('stylesheet', 'com_advportfoliopro/admin.style.css', array('version' => 'auto', 'relative' => true));

		static $js;
		$document	= JFactory::getDocument();

		if (!$js) {
			$js = true;
			$document->addScriptDeclaration("
(function($) {
	$(document).ready(function() {
		ExtStore.AdvPortfolioPro.image.init();
	});
})(jQuery);
");
		}

		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// The text field.
		$html[] = '<input type="hidden" class="image-input" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $attr . ' />';

		// The button.
		$html[] = '<a class="exmodal image-select btn btn-secondary"  href="javascript:;" data-fancybox data-type="iframe" data-src="'
			. ($this->element['readonly'] ? '' : JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $this->id))
			. '"><i class="icon-pictures"></i> ' . JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';

		$html[] = '<a class="btn btn-secondary image-clear"'
			. ' href="javascript:void(0);"><i class="icon-remove"></i> ' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		$html[] = '<div class="image-preview">';
		$html[]	= $this->value ? JHtml::_('advportfoliopro.image', $this->value, 200, 200, $this->value, false, 'class="img-polaroid"') : '';
		$html[]	= '</div>';

		return implode("\n", $html);
	}
}