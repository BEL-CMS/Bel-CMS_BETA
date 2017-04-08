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
function checkWriteConfig ()
{
	return isWritable(ROOTCMS.DS.'config');
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

		createConfig();

	    return true;
	}
	catch(PDOException $e) {
		redirect('?page=sql', 3);
	    return $e->getMessage();
	}
}

function createConfig ()
{
	if (isWritable(ROOTCMS.DS.'config') === false) {
		trigger_error("No Writable dir : ".ROOT.DS."config", E_USER_ERROR);
	}
	$dirFile = ROOTCMS.DS.'config'.DS.'config.inc.php';
	if (is_file($dirFile)) {
		unlink($dirFile);
	}
	$fp = fopen ($dirFile, "w+");
	fwrite($fp,configIncPhp());
	fclose($fp);
	chmod($dirFile, 0644);
}
function configIncPhp ()
{
	$content  = "<?php".PHP_EOL;
	$content .= "/**".PHP_EOL;
	$content .= "* Bel-CMS [Content management system]".PHP_EOL;
	$content .= "* @version 0.0.1".PHP_EOL;
	$content .= "* @link http://www.bel-cms.be".PHP_EOL;
	$content .= "* @link http://www.stive.eu".PHP_EOL;
	$content .= "* @license http://opensource.org/licenses/GPL-3.0 copyleft".PHP_EOL;
	$content .= "* @copyright 2014 Bel-CMS".PHP_EOL;
	$content .= "* @author Stive - mail@stive.eu".PHP_EOL;
	$content .= "*/".PHP_EOL;
	$content .= "\$BDD = 'server';".PHP_EOL;
	$content .= '$databases["server"] = array('.PHP_EOL;
	$content .= "#####################################".PHP_EOL;
	$content .= "# Réglages MySQL - SERVEUR".PHP_EOL;
	$content .= "#####################################".PHP_EOL;
	$content .= "'DB_DRIVER'   => 'mysql',".PHP_EOL;
	$content .= "'DB_NAME'     => '".$_SESSION['dbname']."',".PHP_EOL;
	$content .= "'DB_USER'     => '".$_SESSION['username']."',".PHP_EOL;
	$content .= "'DB_PASSWORD' => '".$_SESSION['password']."',".PHP_EOL;
	$content .= "'DB_HOST'     => '".$_SESSION['host']."',".PHP_EOL;
	$content .= "'DB_PREFIX'   => '".$_SESSION['prefix']."',".PHP_EOL;
	$content .= "'DB_PORT'     => '".$_SESSION['port']."'".PHP_EOL;
	$content .= ");".PHP_EOL;
	$content .= "Common::constant(\$databases[\$BDD]); unset(\$databases, \$BDD);".PHP_EOL;

	return $content;
}

function isWritable($path) {
    if ($path{strlen($path)-1}=='/')
        return isWritable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return isWritable($path.'/'.uniqid(mt_rand()).'.tmp');
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}