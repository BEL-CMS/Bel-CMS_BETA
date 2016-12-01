<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014-2016 Bel-CMS
 * @author Stive - mail@stive.eu
 */

function checkPhp ()
{
	$return = false;
	if (version_compare(PHP_VERSION, '5.4.17') >= 0) {
		$return = true;
	}
	return $return;
}
function checkMysqli ()
{
	$return = false;
	if (function_exists('mysqli_connect')) {
		$return = true;
	}
	return $return;
}
function checkRewrite ()
{
	$return = false;
	if (in_array('mod_rewrite', apache_get_modules())) {
		$return = true;
	}
	return $return;
}
function checkPDO ()
{
	$return = false;
	if (class_exists('PDO')) {
		$return = true;
	}
	return $return;
}
function checkPDOConnect ($d)
{
	try {
		$cnx = new PDO('mysql:host='.$d["serversql"].';port='.$d["port"].';dbname='.trim($d["name"]), $d["user"], $d["password"], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

		$_SESSION['host']       = $d["serversql"];
		$_SESSION['username']   = $d["user"];
		$_SESSION['password']   = $d["password"];
		$_SESSION['dbname']     = $d["name"];
		$_SESSION['port']       = $d["port"];
		$_SESSION['prefix']     = $d['prefix'];

	    return true;
	}
	catch(PDOException $e) {
		redirect('?page=sql', 3);
	    return $e->getMessage();
	}
}