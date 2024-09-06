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
$config = acymailing_config();
$licenseKey = $config->get('license_key', '');
$automaticSend = acymailing_getEscaped($this->config->get('active_cron', 0));

$frequencyVal[] = acymailing_selectOption(900, acymailing_translation('ACY_15_MINUTES'));
$frequencyVal[] = acymailing_selectOption(1800, acymailing_translation('ACY_30_MINUTES'));
$frequencyVal[] = acymailing_selectOption(3600, acymailing_translation('ACY_1_HOUR'));
$frequency = acymailing_select($frequencyVal, "config[cron_updateme_frequency]", 'size="1" style="width:150px;"', 'value', 'text', $config->get('cron_updateme_frequency', 900));
?>

<div id="page-queue">
	<div class="onelineblockoptions">
		<span class="acyblocktitle"><?php echo acymailing_translation('ACY_MY_LICENSE'); ?></span>
		<a style="display: inline-block; text-decoration: underline; font-size: .6rem" target="_blank" id="acymailing__configuration__subscription__page" class="margin-left-1" href="<?php echo ACYMAILING_REDIRECT.'subscription-page'; ?>"><?php echo acymailing_translation('ACY_GET_MY_LICENSE_KEY'); ?></a>
		<table class="acymailing_table" cellspacing="1">
			<tr>
				<td><?php echo acymailing_translation('ACY_LICENSE_KEY'); ?></td>
				<td>
					<input type="text" name="config[license_key]" value="<?php echo $licenseKey; ?>">
					<button class="btn" type="button" onclick="acymailing.submitbutton('<?php echo empty($licenseKey) ? 'attachLicense' : 'unlinkLicense'; ?>')">
                        <?php echo acymailing_translation(empty($licenseKey) ? 'ACY_ATTACH_MY_LICENSE' : 'ACY_UNLINK_MY_LICENSE'); ?>
					</button>
				</td>
			</tr>
		</table>
        <?php if (!empty($licenseKey)) { ?>
			<span class="acyblocktitle"><?php echo acymailing_translation('CRON'); ?></span>
			<table class="acymailing_table" cellspacing="1">
				<tr>
					<td><?php echo acymailing_translation('ACY_SEND_PROCESS_FREQUENCY'); ?></td>
					<td><?php echo $frequency; ?></td>
				</tr>
				<tr>
					<td><?php echo acymailing_translation('ACY_AUTOMATIC_SEND_PROCESS'); ?></td>
					<td>
                        <?php echo acymailing_translation(empty($automaticSend) ? 'ACY_DEACTIVATED' : 'ACTIVATED'); ?>
						<button type="button" class="btn" onclick="acymailing.submitbutton('<?php echo empty($automaticSend) ? 'activateCron' : 'deactivateCron'; ?>')">
                            <?php echo acymailing_translation(empty($automaticSend) ? 'ACY_ACTIVATE' : 'ACY_DEACTIVATE'); ?>
						</button>
					</td>
				</tr>
				<tr>
					<td class="acykey">
                        <?php echo acymailing_tooltip(acymailing_translation('CRON_URL_DESC'), acymailing_translation('CRON_URL'), '', acymailing_translation('CRON_URL')); ?>
					</td>
					<td>
						<a href="<?php echo $this->escape($this->elements->cron_url); ?>" target="_blank"><?php echo $this->elements->cron_url; ?></a>
					</td>
				</tr>
			</table>
        <?php } ?>
	</div>
</div>

