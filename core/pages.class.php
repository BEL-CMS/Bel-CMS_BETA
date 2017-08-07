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

if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}

class Pages
{
	var $vars = array();
	var $page = null;

	function __construct () {
		if (isset($_POST)) {
			$this->data = $_POST;
		}
		if (isset($this->models)){
			foreach($this->models as $v){
				$this->loadModel($v); 
			}
		}
	}

	function set ($d) {
		$this->vars = array_merge($this->vars,$d);
	}

	function jquery ($d) {
		$this->jquery = $d;
	}
	function affiche ($d) {
		$this->affiche = $d;
	}

	function render($filename) {
		extract($this->vars);
		ob_start();
		$dir = defined('MANAGEMENT') ?
			DIR_PAGES.strtolower(get_class($this)).DS.'management'.DS.$filename.'.php' : 
			DIR_PAGES.strtolower(get_class($this)).DS.$filename.'.php';
		$custom = defined('MANAGEMENT') ? null :
			DIR_TPL.CMS_TPL_WEBSITE.DS.'custom'.DS.lcfirst(get_class($this)).'.'.$filename.'.php';
		if (is_file($custom)) {
			require_once $custom;
		} else if (is_file($dir)) {
			require_once $dir;
		} else {
			$error_name    = 'file no found';
			$error_content = '<p><strong>file : '.$filename.' no found</strong><p>';
			require DIR_ASSET_TPL.'error'.DS.'404.php';
		}

		$this->page = ob_get_contents();

		if (ob_get_length() != 0) { 
			ob_end_clean();
		}
	}

	function debug($d) {
		ob_start();
		debug($d);
		$this->page = ob_get_contents();
		ob_end_clean();
	}

	function error ($title, $msg, $type, $debug = null)
	{
		ob_start();
		$type = empty($type) ? 'danger' : $type;
		new notification ($title, $msg, $type, $debug);
		$this->page = ob_get_contents();
		ob_end_clean();
	}

	function loadModel ($name)
	{
		$dir = defined('MANAGEMENT') ?
			DIR_PAGES.strtolower(get_class($this)).DS.'management'.DS.'models.php' : 
			DIR_PAGES.strtolower(get_class($this)).DS.'models.php';
		if (is_file($dir)) {
			require_once $dir;
			$this->$name = new $name();
		} else {
			ob_start();
			$error_name    = 'file no found';
			$error_content = '<strong>file models no found</strong> : <br>'.DIR_PAGES.strtolower(get_class($this)).DS.'models.php';
			require DIR_ASSET_TPL.'error'.DS.'404.php';
			$this->page = ob_get_contents();
			ob_end_clean();
		}
	}

	function paginationCount ($nb, $table, $where = false)
	{
		$return = 0;

		$sql = New BDD();
		$sql->table($table);
		if ($where !== false) {
			$sql->where($where);
		}
		$sql->count();
		$return = $sql->data;

		return $return;
	}

	function pagination ($nbpp = 5, $page, $table, $where = false)
	{
		$management  = defined('MANAGEMENT') ? '?management&' : '?';
		$current     = (int) Dispatcher::RequestPages();
		$page_url    = $page.$management;
		$total       = self::paginationCount($nbpp, $table, $where);
		$adjacents   = 1; 
		$current     = ($current == 0 ? 1 : $current);  
		$start       = ($current - 1) * $nbpp;
		$prev        = $current - 1;
		$next        = $current + 1;
		$setLastpage = ceil($total/$nbpp);
		$lpm1        = $setLastpage - 1;
		$setPaginate = "";

		if ($setLastpage > 1) {	
			$setPaginate .= "<ul class='pagination'>";
			// $setPaginate .= "<li>Page $current of $setLastpage</li>"; /* retirer: compteur de nombre de page
			if ($setLastpage < 7 + ($adjacents * 2)) {	
				for ($counter = 1; $counter <= $setLastpage; $counter++) {
					if ($counter == $current) {
						$setPaginate.= "<li class='active'><a>$counter</a></li>";
					} else {
						$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
					}
				}
			} else if($setLastpage > 5 + ($adjacents * 2)) {
				if ($current < 1 + ($adjacents * 2)) {
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
						if ($counter == $current) {
							$setPaginate.= "<li class='active'><a>$counter</a></li>";
						} else {
							$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
						}
					}
					$setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
					$setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
				}
				else if($setLastpage - ($adjacents * 2) > $current && $current > ($adjacents * 2)) {
					$setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
					$setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
					for ($counter = $current - $adjacents; $counter <= $current + $adjacents; $counter++) {
						if ($counter == $current) {
							$setPaginate.= "<li class='active'><a>$counter</a></li>";
						}
						else {
							$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
						}
					}
					$setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
					$setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
				} else {
					$setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
					$setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
					for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++) {
						if ($counter == $current) {
							$setPaginate.= "<li class='active'><a>$counter</a></li>";
						} else {
							$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
						}
					}
				}
			}
			
			if ($current < $counter - 1) { 
				$setPaginate .= "<li><a href='{$page_url}page=$next'>Suivant</a></li>";
				$setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>Dernière</a></li>";
			} else{
				$setPaginate .= "<li class='active'><a>Suivant</a></li>";
				$setPaginate .= "<li class='active'><a>Dernière</a></li>";
			}

			$setPaginate.= "</ul>".PHP_EOL;
		}

		$this->pagination = $setPaginate;
	}

	#########################################
	# Redirect
	#########################################
	function redirect ($url = null, $time = null)
	{
		if ( $url === true) {
			$url = $_SERVER['HTTP_REFERER'];
			header("refresh:$time;url='$url'");
		}

		$scriptName = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

		$fullUrl = ($_SERVER['HTTP_HOST'].$scriptName);

		if (!strpos($_SERVER['HTTP_HOST'], $scriptName)) {
			$fullUrl = $_SERVER['HTTP_HOST'].$scriptName.$url;
		}

		if (!strpos($fullUrl, 'http://')) {
			if ($_SERVER['SERVER_PORT'] == 80) {
				$url = 'http://'.$fullUrl;
			} else if ($_SERVER['SERVER_PORT'] == 443) {
				$url = 'https://'.$fullUrl;
			} else {
				$url = 'http://'.$fullUrl;
			}
		}
		header("refresh:$time;url='$url'");
	}
}