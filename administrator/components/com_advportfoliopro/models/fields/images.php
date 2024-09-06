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
 * Supports an HTML for multi images field.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class JFormFieldImages extends JFormField {

	/** @var string		The form field type. */
	protected $type	= 'Images';

	/**
	 * Method to get the name used for the field input tag.
	 *
	 * @param   string  $fieldName  The field element name.
	 * @return  string  The name to be used for the field input tag.
	 */
	protected function getBaseName($item = '') {
		$fieldName	= $this->fieldname;
		$name		= '';

		// If there is a form control set for the attached form add it first.
		if ($this->formControl) {
			$name .= $this->formControl;
		}

		// If the field is in a group add the group control to the field name.
		if ($this->group) {
			// If we already have a name segment add the group control as another level.
			$groups = explode('.', $this->group);
			if ($name) {
				foreach ($groups as $group) {
					$name .= '[' . $group . ']';
				}
			} else {
				$name .= array_shift($groups);
				foreach ($groups as $group) {
					$name .= '[' . $group . ']';
				}
			}
		}

		// If we already have a name segment add the field name as another level.
		if ($name) {
			$name .= '[' . $fieldName . ']';
		} else {
			$name .= $fieldName;
		}

		if ($item) {
			$name .= '[' . $item . '][]';
		}

		return $name;
	}

	/**
	 * Method to get the id used for the field input tag.
	 *
	 * @param	int		$index	The id of sub item of field.
	 * @param	string	$item	The field element name.
	 *
	 * @return	string	The id to be used for the field input tag.
	 */
	protected function getBaseId($index = '', $item = '') {
		$id = $this->id;

		if ($index !== null) {
			$id .= '_' . $index;
		}

		if ($item) {
			$id .= '_' . $item;
		}

		return $id;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 */
	protected function getInput() {
		// add asset
		JHtml::_('jquery.ui', array('core', 'sortable'));
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
						ExtStore.AdvPortfolioPro.image.initList();
					});
				})(jQuery);
			");
		}

		// Initialize variables
		$html	= array();
		$items	= AdvPortfolioProHelper::getImages($this->value);

		$document	= JFactory::getDocument();
		$document->addScriptDeclaration('ExtStore.AdvPortfolioPro.image.count = ' . count($items) . ';');

		$html[]	= '<ul id="' . $this->getBaseId('container', '') . '" class="images-container">';

		for ($i = 0, $n = count($items); $i < $n; $i++) {
			$item	= $items[$i];

			$html[]	= '<li>';
			$html[]	= '<div class="image-sortable"></div>';
			$html[]	= '<div class="image-container">';
			$html[]	= '<input type="hidden" id="' . $this->getBaseId($i, 'image') . '" name="' . $this->getBaseName('image')
				. '" value="' . $item->image . '" class="image-input" />';
			$html[]	= '<a  href="javascript:;" data-src="' . JRoute::_('index.php?option=com_advportfoliopro&view=imagehandler&tmpl=component&image_id=' . $this->getBaseId($i, 'image'))
				. '" data-fancybox data-type="iframe" class="exmodal image-select btn btn-secondary"><i class="icon-pictures"></i> '
				. JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';
			$html[]	= '<a href="javascript:void(0);" class="btn btn-secondary image-clear"><i class="icon-remove"></i> ' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
			$html[]	= '<div class="image-title"><input type="text" name="' . $this->getBaseName('title')
				. '" placeholder="' . JText::_('COM_ADVPORTFOLIOPRO_IMAGE_TITLE') . '" value="' . $item->title . '" />';
			$html[]	= '</div>';
			$html[]	= '<div class="image-preview">';
			$html[]	= JHtml::_('advportfoliopro.image', $item->image, 200, 200, $item->image, false, 'class="img-polaroid"');
			$html[]	= '</div>';
			$html[]	= '</div>';
			$html[]	= '</li>';
		}

		return implode("\n", $html);
	}
}