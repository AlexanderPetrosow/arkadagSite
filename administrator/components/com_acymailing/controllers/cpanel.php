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

class CpanelController extends acymailingController
{

    var $config;

    function __construct($config = [])
    {
        parent::__construct($config);
        $this->registerDefaultTask('display');
        $this->config = acymailing_config();
    }

    function save()
    {
        $this->store();

        return $this->cancel();
    }

    function apply()
    {
        $this->store();

        return $this->display();
    }

    function listing()
    {
        if (!$this->isAllowed('configuration', 'manage')) return;

        return $this->display();
    }

    function store()
    {
        if (!$this->isAllowed('configuration', 'manage')) return;
        acymailing_checkToken();

        $config = acymailing_config();

        $source = is_array($_POST['config']) ? 'POST' : 'REQUEST';
        $formData = acymailing_getVar('array', 'config', [], $source);

        $aclcats = acymailing_getVar('array', 'aclcat', [], 'POST');

        if (!empty($aclcats)) {

            if (acymailing_getVar('string', 'acl_configuration', 'all') != 'all' && !acymailing_isAllowed($formData['acl_configuration_manage'])) {
                acymailing_enqueueMessage(acymailing_translation('ACL_WRONG_CONFIG'), 'notice');
                unset($formData['acl_configuration_manage']);
            }

            $deleteAclCats = [];
            $unsetVars = ['save', 'create', 'manage', 'modify', 'delete', 'fields', 'export', 'import', 'view', 'send', 'schedule', 'bounce', 'test'];
            foreach ($aclcats as $oneCat) {
                if (acymailing_getVar('string', 'acl_'.$oneCat) == 'all') {
                    foreach ($unsetVars as $oneVar) {
                        unset($formData['acl_'.$oneCat.'_'.$oneVar]);
                    }
                    $deleteAclCats[] = $oneCat;
                }
            }
        }

        if (acymailing_level(1)) {
            $newHours = acymailing_getVar('none', 'cronplghours');
            if (empty($newHours)) $newHours = '00';
            $newMinutes = acymailing_getVar('none', 'cronplgminutes');
            if (empty($newMinutes)) $newMinutes = '00';

            $newTime = acymailing_getTime(date('Y').'-'.date('m').'-'.date('d').' '.$newHours.':'.$newMinutes);
            if ($newTime < time()) $newTime += 86400;
            $formData['cron_plugins_next'] = $newTime;
        }

        if (!empty($formData['hostname'])) {
            $formData['hostname'] = preg_replace('#https?://#i', '', $formData['hostname']);
            $formData['hostname'] = preg_replace('#[^a-z0-9_.-]#i', '', $formData['hostname']);
        }

        $reasons = acymailing_getVar('array', 'unsub_reasons', [], 'POST');
        $unsub_reasons = [];
        foreach ($reasons as $oneReason) {
            if (empty($oneReason)) continue;
            $unsub_reasons[] = strip_tags($oneReason);
        }
        $formData['unsub_reasons'] = serialize($unsub_reasons);

        if (!empty($formData['smtp_username'])) $formData['smtp_username'] = acymailing_punycode($formData['smtp_username']);

        $status = $config->save($formData);

        if (!empty($deleteAclCats)) {
            acymailing_query("DELETE FROM `#__acymailing_config` WHERE `namekey` LIKE 'acl_".implode("%' OR `namekey` LIKE 'acl_", $deleteAclCats)."%'");
        }

        if ($status) {
            acymailing_enqueueMessage(acymailing_translation('JOOMEXT_SUCC_SAVED'), 'message');
        } else {
            acymailing_enqueueMessage(acymailing_translation('ERROR_SAVING'), 'error');
        }

        $config->load();
    }

    function test()
    {
        if (!$this->isAllowed('configuration', 'manage')) return;
        $this->store();

        acymailing_displayErrors();

        $config = acymailing_config();

        $mailClass = acymailing_get('helper.mailer');
        $addedName = $config->get('add_names', true) ? $mailClass->cleanText(acymailing_currentUserName()) : '';
        $mailClass->AddAddress(acymailing_currentUserEmail(), $addedName);
        $mailClass->Subject = 'Test e-mail from '.ACYMAILING_LIVE;
        $mailClass->Body = acymailing_translation('TEST_EMAIL');
        $mailClass->SMTPDebug = 1;
        if (acymailing_isDebug()) $mailClass->SMTPDebug = 2;
        $result = $mailClass->send();

        if (!$result) {
            $bounce = $config->get('bounce_email');
            if ($config->get('mailer_method') == 'smtp' && $config->get('smtp_secured') == 'ssl' && !function_exists('openssl_sign')) {
                acymailing_enqueueMessage('The PHP Extension openssl is not enabled on your server, this extension is required to use an SSL connection, please enable it', 'notice');
            } elseif (!empty($bounce) AND !in_array($config->get('mailer_method'), ['smtp', 'elasticemail'])) {
                acymailing_enqueueMessage(acymailing_translation_sprintf('ADVICE_BOUNCE', '<b><i>'.$bounce.'</i></b>'), 'notice');
            } elseif ($config->get('mailer_method') == 'smtp' AND !$config->get('smtp_auth') AND strlen($config->get('smtp_password')) > 1) {
                acymailing_enqueueMessage(acymailing_translation('ADVICE_SMTP_AUTH'), 'notice');
            } elseif ((strpos(ACYMAILING_LIVE, 'localhost') OR strpos(ACYMAILING_LIVE, '127.0.0.1')) AND in_array($config->get('mailer_method'), ['sendmail', 'qmail', 'mail'])) {
                acymailing_enqueueMessage(acymailing_translation('ADVICE_LOCALHOST'), 'notice');
            } elseif ($config->get('mailer_method') == 'smtp' AND $config->get('smtp_port') AND !in_array($config->get('smtp_port'), [25, 2525, 465, 587])) {
                acymailing_enqueueMessage(acymailing_translation_sprintf('ADVICE_PORT', $config->get('smtp_port')), 'notice');
            }
        }

        return $this->display();
    }

    function plgtrigger()
    {
        $pluginToTrigger = acymailing_getVar('cmd', 'plg');
        $pluginType = acymailing_getVar('cmd', 'plgtype', 'acymailing');
        $fctName = 'onAcy'.acymailing_getVar('cmd', 'fctName', 'TestPlugin');
        $methodParam = acymailing_getVar('cmd', 'param', 'NoParam');

        if (!ACYMAILING_J16) {
            $path = JPATH_PLUGINS.DS.$pluginType.DS.$pluginToTrigger.'.php';
        } else {
            $path = JPATH_PLUGINS.DS.$pluginType.DS.$pluginToTrigger.DS.$pluginToTrigger.'.php';
        }

        if (!file_exists($path)) {
            acymailing_display('Plugin not found: '.$path, 'error');

            return;
        }

        require_once($path);
        $className = 'plg'.$pluginType.$pluginToTrigger;
        if (!class_exists($className)) {
            acymailing_display('Class not found: '.$className, 'error');

            return;
        }

        $dispatcher = ACYMAILING_J40 ? \JFactory::getApplication()->getDispatcher() : JDispatcher::getInstance();
        $instance = new $className($dispatcher, ['name' => $pluginToTrigger, 'type' => $pluginType]);

        $fctName = ($fctName == 'onAcyTestPlugin') ? 'onTestPlugin' : $fctName;
        if (!method_exists($instance, $fctName)) {
            acymailing_display('Method "'.$fctName.'" not found in: '.$className, 'error');

            return;
        }

        if ($methodParam == 'NoParam') {
            $instance->$fctName();
        } else $instance->$fctName($methodParam);

        return;
    }

    function seereport()
    {
        if (!$this->isAllowed('configuration', 'manage')) return;
        $config = acymailing_config();

        $path = trim(html_entity_decode($config->get('cron_savepath')));
        if (!preg_match('#^[a-z0-9/_\-{}]*\.log$#i', $path)) {
            acymailing_display('The log file must only contain alphanumeric characters and end with .log', 'error');

            return;
        }

        $path = str_replace(['{year}', '{month}'], [date('Y'), date('m')], $config->get('cron_savepath'));

        $reportPath = acymailing_cleanPath(ACYMAILING_ROOT.$path);

        if (file_exists($reportPath)) {
            try {
                $lines = 10000;
                $f = fopen($reportPath, "rb");
                fseek($f, -1, SEEK_END);
                if (fread($f, 1) != "\n") $lines -= 1;

                $logFile = '';
                while (ftell($f) > 0 && $lines >= 0) {
                    $seek = min(ftell($f), 4096); // Figure out how far back we should jump
                    fseek($f, -$seek, SEEK_CUR);
                    $logFile = ($chunk = fread($f, $seek)).$logFile; // Get the line
                    fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
                    $lines -= substr_count($chunk, "\n"); // Move to previous line
                }

                while ($lines++ < 0) {
                    $logFile = substr($logFile, strpos($logFile, "\n") + 1);
                }
                fclose($f);
            } catch (Exception $e) {
                $logFile = '';
            }
        }

        if (empty($logFile)) {
            acymailing_display(acymailing_translation('EMPTY_LOG'), 'info');
        } else {
            echo nl2br($logFile);
        }
    }

    function cleanreport()
    {
        if (!$this->isAllowed('configuration', 'manage')) return;

        $config = acymailing_config();
        $path = trim(html_entity_decode($config->get('cron_savepath')));
        if (!preg_match('#^[a-z0-9/_\-{}]*\.log$#i', $path)) {
            acymailing_display('The log file must only contain alphanumeric characters and end with .log', 'error');

            return;
        }

        $path = str_replace(['{year}', '{month}'], [date('Y'), date('m')], $config->get('cron_savepath'));

        $reportPath = acymailing_cleanPath(ACYMAILING_ROOT.$path);
        if (is_file($reportPath)) {
            $result = acymailing_deleteFile($reportPath);
            if ($result) {
                acymailing_display(acymailing_translation('SUCC_DELETE_LOG'), 'success');
            } else {
                acymailing_display(acymailing_translation('ERROR_DELETE_LOG'), 'error');
            }
        } else {
            acymailing_display(acymailing_translation('EXIST_LOG'), 'info');
        }
    }

    function cancel()
    {
        acymailing_redirect(acymailing_completeLink('dashboard', false, true));
    }

    function checkDB()
    {
        $queries = file_get_contents(ACYMAILING_BACK.'tables.sql');
        $tables = explode("CREATE TABLE IF NOT EXISTS", $queries);
        $structure = [];
        $createTable = [];
        $indexes = [];
        foreach ($tables as $oneTable) {
            $fields = explode("\n\t", $oneTable);
            $tableNameTmp = substr($oneTable, strpos($oneTable, '`') + 1, strlen($oneTable) - 1);
            $tableName = substr($tableNameTmp, 0, strpos($tableNameTmp, '`'));
            if (empty($tableName)) continue;
            foreach ($fields as $oneField) {
                if (strpos($oneField, '#__')) continue;

                if (substr($oneField, 0, 1) == '`') {
                    $fieldNameTmp = substr($oneField, strpos($oneField, '`') + 1, strlen($oneField) - 1);
                    $fieldName = substr($fieldNameTmp, 0, strpos($fieldNameTmp, '`'));
                    $structure[$tableName][$fieldName] = trim($oneField, ",");
                    continue;
                }


                $oneField = trim(str_replace("\n) /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;", '', $oneField));
                $oneField = rtrim($oneField, ',');

                if (strpos($oneField, 'PRIMARY KEY') !== false) {
                    $indexes[$tableName]['PRIMARY'] = $oneField;
                } elseif (strpos($oneField, 'KEY') !== false) {
                    $firstBackquotePos = strpos($oneField, '`');
                    $indexName = substr($oneField, $firstBackquotePos + 1, strpos($oneField, '`', $firstBackquotePos + 1) - $firstBackquotePos - 1);
                    $indexes[$tableName][$indexName] = $oneField;
                }
            }
            $createTable[$tableName] = "CREATE TABLE IF NOT EXISTS ".$oneTable;
        }

        $tableNames = array_keys($structure);
        $structureDB = [];
        foreach ($tableNames as $oneTableName) {
            try {
                $fields2 = acymailing_loadObjectList("SHOW COLUMNS FROM ".$oneTableName);
            } catch (Exception $e) {
                $fields2 = null;
            }
            if ($fields2 == null) {
                $errorMessage = (isset($e) ? $e->getMessage() : substr(strip_tags(acymailing_getDBError()), 0, 200));
                echo "<span style=\"color:blue\">Could not load columns from the table : ".$oneTableName." : ".$errorMessage."</span><br />";

                if (strpos($errorMessage, 'marked as crashed')) {
                    $repairQuery = 'REPAIR TABLE '.$oneTableName;

                    try {
                        $isError = acymailing_query($repairQuery);
                    } catch (Exception $e) {
                        $isError = null;
                    }
                    if ($isError === null) {
                        echo "<span style=\"color:red\">[ERROR]Could not repair the table ".$oneTableName." </span><br />";
                        acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags(acymailing_getDBError()), 0, 200).'...', 'error');
                    } else {
                        echo "<span style=\"color:green\">[OK]Problem solved : Table ".$oneTableName." repaired</span><br />";
                    }
                    continue;
                }

                try {
                    $isError = acymailing_query($createTable[$oneTableName]);
                } catch (Exception $e) {
                    $isError = null;
                }
                if ($isError === null) {
                    echo "<span style=\"color:red\">[ERROR]Could not create the table ".$oneTableName." </span><br />";
                    acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags(acymailing_getDBError()), 0, 200).'...', 'error');
                } else {
                    echo "<span style=\"color:green\">[OK]Problem solved : Table ".$oneTableName." created</span><br />";
                }
                continue;
            }
            foreach ($fields2 as $oneField) {
                $structureDB[$oneTableName][$oneField->Field] = $oneField->Field;
            }
        }

        foreach ($tableNames as $oneTableName) {
            if (empty($structureDB[$oneTableName])) continue;
            $resultCompare[$oneTableName] = array_diff(array_keys($structure[$oneTableName]), $structureDB[$oneTableName]);
            if (empty($resultCompare[$oneTableName])) {
                echo "<span style=\"color:green\">Table ".$oneTableName." OK</span><br />";
                continue;
            }
            foreach ($resultCompare[$oneTableName] as $oneField) {
                echo "<span style=\"color:blue\">Field ".$oneField." missing in ".$oneTableName."</span><br />";
                try {
                    $isError = acymailing_query("ALTER TABLE ".$oneTableName." ADD ".$structure[$oneTableName][$oneField]);
                } catch (Exception $e) {
                    $isError = null;
                }
                if ($isError === null) {
                    echo "<span style=\"color:red\">[ERROR]Could not add the field ".$oneField." on the table : ".$oneTableName."</span><br />";
                    acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags(acymailing_getDBError()), 0, 200).'...', 'error');
                    continue;
                } else {
                    echo "<span style=\"color:green\">[OK]Problem solved : Add ".$oneField." in ".$oneTableName."</span><br />";
                }
            }
        }

        foreach ($tableNames as $oneTableName) {
            if (empty($structureDB[$oneTableName])) continue;

            $results = acymailing_loadObjectList('SHOW INDEX FROM '.$oneTableName, 'Key_name');
            if (empty($results)) continue;

            foreach ($indexes[$oneTableName] as $name => $query) {
                if (in_array($name, array_keys($results))) continue;

                $keyName = $name == 'PRIMARY' ? 'primary key' : 'index '.$name;

                echo "<span style=\"color:blue\">".$keyName." missing in ".$oneTableName."</span><br />";
                try {
                    $isError = acymailing_query('ALTER TABLE '.$oneTableName.' ADD '.$query);
                } catch (Exception $e) {
                    $isError = null;
                }

                if ($isError === null) {
                    echo "<span style=\"color:red\">[ERROR]Could not add the ".$keyName." on the table : ".$oneTableName."</span><br />";
                    acymailing_display(substr(strip_tags(acymailing_getDBError()), 0, 200).'...', 'error');
                } else {
                    echo "<span style=\"color:green\">[OK]Problem solved : Added ".$keyName." in ".$oneTableName."</span><br />";
                }
            }
        }

        $nbdeleted = acymailing_query("DELETE listsub.* FROM #__acymailing_listsub as listsub LEFT JOIN #__acymailing_subscriber as sub ON sub.subid = listsub.subid WHERE sub.subid IS NULL");
        if (!empty($nbdeleted)) {
            echo "<span style=\"color:blue\">".$nbdeleted." lost subscriber entries fixed</span><br />";
        }

        $nbdeleted = acymailing_query("DELETE listsub.* FROM #__acymailing_listsub AS listsub LEFT JOIN #__acymailing_list AS b ON listsub.listid = b.listid WHERE b.listid IS NULL");
        if (!empty($nbdeleted)) {
            echo "<span style=\"color:blue\">".$nbdeleted." lost list entries fixed</span><br />";
        }

        $customFields = array_keys(acymailing_loadObjectList('SELECT namekey FROM #__acymailing_fields WHERE type NOT IN (\'category\',\'customtext\')', 'namekey'));
        $subFields = acymailing_loadObjectList("SHOW COLUMNS FROM #__acymailing_subscriber");
        $subFieldsName = [];
        foreach ($subFields as $oneField) {
            $subFieldsName[] = $oneField->Field;
        }
        $fieldsDiff = array_diff($customFields, $subFieldsName);
        if (!empty($fieldsDiff)) {
            echo '<span style="color:red;">At least one field is missing in the subscriber table or has not the same case between fields and subscriber table (they should all be lower case): <span style="font-weight: bold">'.implode(', ', $fieldsDiff).'</span>. You should only create fields using the custom fields interface.</span>';
        } else {
            echo '<span style="color:green;">Custom fields OK</span>';
        }
    }

    function anonymize()
    {
        acymailing_checkToken();
        $config = acymailing_config();
        if ($config->get('anonymous_tracking', 0) == 0 || $config->get('anonymizeold', 1) == 0) acymailing_redirect(acymailing_completeLink('cpanel', false, true));

        acymailing_increasePerf();

        if ($config->get('anonymizeoldstep', 0) > 0) {
            $newConfig = new stdClass();
            $newConfig->anonymizeoldtake = $config->get('anonymizeoldtake', 0) + 1;
            $config->save($newConfig);
        }

        if ($config->get('anonymizeoldstep', 0) <= 1) {
            $newConfig = new stdClass();
            $newConfig->anonymizeoldstep = 1;
            $config->save($newConfig);

            acymailing_query('UPDATE #__acymailing_subscriber SET ip = NULL, confirmed_ip = NULL, lastopen_date = 0, lastopen_ip = NULL, lastclick_date = 0');
        }

        if ($config->get('anonymizeoldstep') <= 2) {
            $newConfig = new stdClass();
            $newConfig->anonymizeoldstep = 2;
            $config->save($newConfig);

            acymailing_query('UPDATE #__acymailing_geolocation SET geolocation_subid = 0, geolocation_ip = ""');
        }

        if ($config->get('anonymizeoldstep') <= 3) {
            $newConfig = new stdClass();
            $newConfig->anonymizeoldstep = 3;
            $config->save($newConfig);

            acymailing_query('UPDATE #__acymailing_history SET ip = "", source = ""');
        }

        if ($config->get('anonymizeoldstep') <= 4) {
            $newConfig = new stdClass();
            $newConfig->anonymizeoldstep = 4;
            $config->save($newConfig);

            acymailing_query('UPDATE #__acymailing_forward SET ip = ""');
        }

        if ($config->get('anonymizeoldstep') <= 5) {
            $newConfig = new stdClass();
            $newConfig->anonymizeoldstep = 5;
            $config->save($newConfig);

            $clickStats = acymailing_loadObjectList('SELECT urlid, mailid, SUM(click) AS nbclicks FROM #__acymailing_urlclick GROUP BY urlid');
            if (!empty($clickStats)) {

                $insert = [];
                foreach ($clickStats as $stats) {
                    $insert[] = '('.$stats->urlid.', '.$stats->mailid.', '.$stats->nbclicks.', 0)';
                }

                acymailing_query('DELETE FROM #__acymailing_urlclick');
                acymailing_query('INSERT INTO #__acymailing_urlclick (urlid, mailid, click, subid) VALUES '.implode(',', $insert));
            }
        }

        $newConfig = new stdClass();
        $newConfig->anonymizeoldstep = 6;
        $config->save($newConfig);

        acymailing_query('UPDATE #__acymailing_userstats SET open = 0, opendate = 0, ip = NULL, browser = NULL, browser_version = NULL, is_mobile = NULL, mobile_os = NULL, user_agent = NULL');

        $newConfig = new stdClass();
        $newConfig->anonymizeold = 0;
        $newConfig->anonymizeoldstep = 0;
        $newConfig->anonymizeoldtake = 0;
        $config->save($newConfig);

        acymailing_redirect(acymailing_completeLink('cpanel', false, true), acymailing_translation('ACY_DATA_ANONYMIZE_SUCCESS'), 'success');
    }



    public function unlinkLicense()
    {
        $this->deactivateCron(false);

        $url = ACYMAILING_UPDATEMEURL.'license&task=unlinkWebsiteFromLicense';
        $fields = [
            'domain' => ACYMAILING_LIVE,
            'license_key' => $this->config->get('license_key', ''),
            'level' => $this->config->get('level', ''),
            'component' => ACYMAILING_COMPONENT_NAME_API,
        ];

        $result = acymailing_makeCurlCall($url, $fields);

        if (empty($result)) {
            $this->displayMessage('');
            $this->listing();

            return true;
        }

        $messageOK = $this->displayMessage($result['message']);

        if ($result['type'] == 'info' && $messageOK) {
            $this->config->save(['license_key' => '']);
        }

        $this->listing();

        return true;
    }

    public function attachLicense()
    {
        $this->store();

        $licenseKey = $this->config->get('license_key', '');

        if (empty($licenseKey)) {
            $this->displayMessage('LICENSE_NOT_FOUND');
            $this->listing();

            return true;
        }

        $url = ACYMAILING_UPDATEMEURL.'license&task=attachWebsiteKey';

        $fields = [
            'domain' => ACYMAILING_LIVE,
            'license_key' => $licenseKey,
        ];

        $result = acymailing_makeCurlCall($url, $fields);

        if (empty($result)) {
            $this->config->save(['license_key' => '']);

            $this->displayMessage('');
            $this->listing();

            return true;
        }

        if ($result['type'] == 'error' || !$this->displayMessage($result['message'])) {
            $this->config->save(['license_key' => '']);
        }

        $this->listing();

        return true;
    }

    public function activateCron()
    {
        $result = $this->modifyCron('activateCron');
        if ($result !== false && $this->displayMessage($result['message'])) $this->config->save(['active_cron' => 1]);
        $this->listing();

        return true;
    }

    public function deactivateCron($listing = true)
    {
        $result = $this->modifyCron('deactivateCron');
        if ($result !== false && $this->displayMessage($result['message'])) $this->config->save(['active_cron' => 0]);
        if ($listing) $this->listing();

        return true;
    }

    private function modifyCron($functionToCall)
    {
        $this->store();

        $licenseKey = $this->config->get('license_key', '');
        if (empty($licenseKey)) {
            $this->displayMessage('LICENSE_NOT_FOUND');

            return false;
        }

        $url = ACYMAILING_UPDATEMEURL.'launcher&task='.$functionToCall;

        $fields = [
            'domain' => ACYMAILING_LIVE,
            'license_key' => $licenseKey,
            'cms' => ACYMAILING_CMS_SIMPLE,
            'frequency' => $this->config->get('cron_updateme_frequency', 900),
            'level' => $this->config->get('level', ''),
            'version' => '5',
        ];

        $result = acymailing_makeCurlCall($url, $fields);


        if (empty($result)) {
            $this->displayMessage('');

            return false;
        }

        if ($result['type'] == 'error') {
            $this->displayMessage($result['message']);

            return false;
        }

        return $result;
    }

    private function displayMessage($message)
    {
        $correspondences = [
            'WEBSITE_NOT_FOUND' => ['message' => 'ACY_WEBSITE_NOT_FOUND', 'type' => 'error'],
            'LICENSE_NOT_FOUND' => ['message' => 'ACY_LICENSE_NOT_FOUND', 'type' => 'error'],
            'WELL_ATTACH' => ['message' => 'ACY_LICENSE_WELL_ATTACH', 'type' => 'info'],
            'ISSUE_WHILE_ATTACH' => ['message' => 'ACY_ISSUE_WHILE_ATTACHING_LICENSE', 'type' => 'error'],
            'ALREADY_ATTACH' => ['message' => 'ACY_LICENSE_ALREADY_ATTACH', 'type' => 'info'],
            'LICENSES_DONT_MATCH' => ['message' => 'ACY_CANT_UNLINK_WEBSITE_LICENSE_DONT_MATCH', 'type' => 'error'],
            'MAX_SITES_ATTACH' => ['message' => 'ACY_YOU_REACH_THE_MAX_SITE_ATTACH', 'type' => 'error'],
            'SITE_NOT_FOUND' => ['message' => 'ACY_ISSUE_WHILE_ATTACHING_LICENSE', 'type' => 'error'],
            'UNLINK_SUCCESSFUL' => ['message' => 'ACY_LICENSE_UNLINK_SUCCESSFUL', 'type' => 'info'],
            'UNLINK_FAILED' => ['message' => 'ACY_ERROR_WHILE_UNLINK_LICENSE', 'type' => 'error'],
            'CRON_WELL_ACTIVATED' => ['message' => 'ACY_AUTOMATIC_SEND_PROCESS_WELL_ACTIVATED', 'type' => 'info'],
            'CRON_WELL_DEACTIVATED' => ['message' => 'ACY_AUTOMATIC_SEND_PROCESS_WELL_DEACTIVATED', 'type' => 'info'],
            'CRON_NOT_SAVED' => ['message' => 'ACY_AUTOMATIC_SEND_PROCESS_NOT_ENABLED', 'type' => 'error'],
        ];

        if (empty($message) || empty($correspondences[$message])) {
            acymailing_enqueueMessage(acymailing_translation('ACY_ERROR_ON_CALL_ACYBA_WEBSITE'), 'error');

            return false;
        }

        acymailing_enqueueMessage(acymailing_translation($correspondences[$message]['message']), $correspondences[$message]['type']);

        return $correspondences[$message]['type'] == 'info';
    }
}

