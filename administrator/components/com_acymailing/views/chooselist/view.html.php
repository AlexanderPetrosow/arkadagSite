<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.10.19
 * @author	acyba.com
 * @copyright	(C) 2009-2021 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');
?><?php

class chooselistViewchooselist extends acymailingView
{

    function display($tpl = null)
    {
        $function = $this->getLayout();
        if (method_exists($this, $function)) $this->$function();

        parent::display($tpl);
    }

    function listing()
    {
        $listClass = acymailing_get('class.list');
        $rows = $listClass->getLists();

        $selectedLists = acymailing_getVar('string', 'values', '', '');

        if (strtolower($selectedLists) == 'all') {
            foreach ($rows as $id => $oneRow) {
                $rows[$id]->selected = true;
            }
        } elseif (!empty($selectedLists)) {
            $selectedLists = explode(',', $selectedLists);
            foreach ($rows as $id => $oneRow) {
                if (in_array($oneRow->listid, $selectedLists)) {
                    $rows[$id]->selected = true;
                }
            }
        }

        $fieldName = acymailing_getVar('string', 'task');
        $controlName = acymailing_getVar('string', 'control', 'params');
        $popup = acymailing_getVar('string', 'popup', '1');

        $this->rows = $rows;
        $this->selectedLists = $selectedLists;
        $this->fieldName = $fieldName;
        $this->controlName = $controlName;
        $this->popup = $popup;
    }

    function customfields()
    {
        $fieldsClass = acymailing_get('class.fields');
        $fake = null;
        $rows = $fieldsClass->getFields('module', $fake);

        $selected = acymailing_getVar('string', 'values', '', '');
        $selectedvalues = explode(',', $selected);
        foreach ($rows as $id => $oneRow) {
            if (in_array($oneRow->namekey, $selectedvalues)) {
                $rows[$id]->selected = true;
            }
        }

        $full = acymailing_getVar('string', 'full', '', '');
        if (!empty($full)) {
            $basicColumns = [
                'subid',
                'userid',
                'created',
                'confirmed',
                'enabled',
                'accept',
                'ip',
                'key',
                'confirmed_date',
                'confirmed_ip',
                'lastopen_date',
                'lastopen_ip',
                'lastclick_date',
                'lastsent_date',
                'source',
                'listids',
                'listname',
            ];

            foreach ($basicColumns as $oneColumn) {
                $rows[$oneColumn] = (object)[
                    'fieldid' => '',
                    'fieldname' => $oneColumn,
                    'namekey' => $oneColumn,
                    'selected' => in_array($oneColumn, $selectedvalues),
                ];
            }
        }

        $this->fieldsClass = $fieldsClass;
        $this->rows = $rows;
        $controlName = acymailing_getVar('string', 'control', 'params');
        $this->controlName = $controlName;
    }
}

