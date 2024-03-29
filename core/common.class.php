<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.3
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

final class Common
{
	#########################################
	# define constant array or simple name
	#########################################
	public static function Constant ($data = false, $value = false)
	{
		if ($data) {
			if (is_array($data)) {
				foreach ($data as $constant => $tableName) {
					if (!defined(strtoupper($constant))) {
						$constant = trim($constant);
						define(strtoupper($constant), $tableName);
					}
				}
			} else {
				if ($value || $data) {
					if (!defined(strtoupper($data))) {
						$data = trim($data);
						define(strtoupper($data), $value);
					}
				}
			}
		}
	}
	#########################################
	# Debug
	#########################################
	public static function Debug ($data = null, $exitAfter = false)
	{
		echo '<pre>';
			print_r($data);
		echo '</pre>';
		if ($exitAfter === true) {
			exit();
		}
	}
	#########################################
	# Test Empty Var
	#########################################
	public static function IsEmpty($var)
	{
		return (is_array($var) && !count($var)) || (is_string($var) && $var == '') || is_null($var);
	}
	#########################################
	# clear url and constant name
	#########################################
	public static function MakeConstant ($d, $c = false) {
		$chr = array(
			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
			'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
			'Œ' => 'oe', 'œ' => 'oe',
			'$' => 's', '&' => '_AND_', '?' => '%3F');
		$return = strtr($d, $chr);
		$return = preg_replace('#[^A-Za-z0-9]+#', '_', $return);
		$return = trim($return, '-');
		if ($c == 'upper') {
			$return = strtoupper($return);
		} else if ($c == 'lower'){
			$return = strtolower($return);
		}
		return $return;
	}
	#########################################
	# Redirect
	#########################################
	public static function Redirect ($url = null, $time = null)
	{
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

		$time = (empty($time)) ? 0 : (int) $time * 1000;

		?>
		<script>
		window.setTimeout(function() {
			window.location = '<?php echo $url; ?>';
		}, <?php echo $time; ?>);
		</script>
		<?php
	}
	#########################################
	# Scan directory
	#########################################
	public static function ScanDirectory ($dir = false) {
		$return = array();
		if ($dir) {
			$myDirectory = @opendir($dir);

			while($entry = @readdir($myDirectory)) {
				if (is_dir($dir.DS.$entry) && $entry != '.' && $entry != '..') {
					$return[] = ($entry);
				}
			}
			@closedir($myDirectory);
		}
		return $return;
	}
	#########################################
	# Scan file
	#########################################
	public static function ScanFiles ($dir, $ext = false, $full_access = false, $Rext = false) {

		$return = array();

		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						if ($ext) {
							$fileExt = substr ($file, -3);
							if (is_array($ext)) {
								if (array_search($fileExt, $ext)) {
									$return[] = ($full_access) ? $dir.$file : $file;
								}
							} else {
								if ($fileExt == $ext) {
									$return[] = ($full_access) ? $dir.$file : $file;
								}
							}
						} else {
							$return[] = ($full_access) ? $dir.$file : $file;
						}
					}
				}
				closedir($dh);
			}
		}
		if ($Rext === true && !empty($ext)) {
			foreach ($return as $k => $v) {
				$remove = '.'.$ext;
				$return[$k] = basename($v, $remove);
			}
		}
		return $return;
	}
	#########################################
	# Get IP
	#########################################
	public static function GetIp () {

		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$return = $_SERVER['HTTP_CLIENT_IP'];
		}

		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$return = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$return = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		}

		if ($return == '::1') {
			$return = '127.0.0.1';
		}

		return $return;
	}
	#########################################
	# Date & DATETIME SQL
	#########################################
	public static function DatetimeSQL ($date, $time = false, $custom = false)
	{

		if ($date == '31-11-0001' or $date == '0000-00-00' or $date == '30-11--0001') {
			return date('Y-m-d');
		}

		$date = str_replace(' ', '', $date);
		$date = str_replace('/', '-', $date);

		if (!empty($custom)) {
			$date = new DateTime($date);
			$return = $date->format($custom);
		} else {
			if ($time) {
				$date = new DateTime($date);
				$return = $date->format('d/m/Y H:i:s');
			} else {
				$date = new DateTime($date);
				$return = $date->format('d/m/Y');
			}
		}

		return $return;
	}
	#########################################
	# Transform date
	# Le format à utiliser pour les dates:
	#	IntlDateFormatter::NONE (masque la date)
	#	IntlDateFormatter::SHORT (14/07/2017)
	#	IntlDateFormatter::MEDIUM (14 juil. 2017)
	#	IntlDateFormatter::LONG (14 juillet 2017)
	#	IntlDateFormatter::FULL (vendredi 14 juillet 2017)
	# Le format à utiliser pour l'heure:
	#	IntlDateFormatter::NONE (masque l'heure)
	#	IntlDateFormatter::SHORT (00:00)
	#	IntlDateFormatter::MEDIUM (à 00:00:00)
	#	IntlDateFormatter::LONG (à 00:00:00 UTC+2)
	#	IntlDateFormatter::FULL (à 00:00:00 heure d’été d’Europe centrale)
	#########################################
	public static function TransformDate ($date, $d = 'NONE', $t = 'NONE')
	{

		# fix empty date - 30-11-0001
		if ($date == '31-11-0001' or $date == '0000-00-00' or $date == '30-11--0001') {
			return date('Y-m-d');
		}

		if (CMS_WEBSITE_LANG == FRENCH) {
			$lg = 'fr_FR';
		} else if (CMS_WEBSITE_LANG == ENGLISH) {
			$lg = 'en_US';
		} else if (CMS_WEBSITE_LANG == NETHERLANDS) {
			$lg = 'nl_NL';
		} else if (CMS_WEBSITE_LANG == DEUTCH) {
			$lg = 'de_DE';
		}
		$d = strtoupper($d); $t = strtoupper($t);
		$date = str_replace('/', '-', $date);
		$date = new DateTime($date);

		if ($d == 'NONE' && $t == 'NONE') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::NONE, IntlDateFormatter::NONE);
		} else if ($d == 'SHORT' && $t == 'NONE') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
		} else if ($d == 'MEDIUM' && $t == 'NONE') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
		} else if ($d == 'LONG' && $t == 'NONE') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
		} else if ($d == 'FULL' && $t == 'NONE') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::FULL, IntlDateFormatter::NONE);
		}
		else if ($d == 'NONE' && $t == 'SHORT') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::NONE, IntlDateFormatter::SHORT);
		} else if ($d == 'SHORT' && $t == 'SHORT') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
		} else if ($d == 'MEDIUM' && $t == 'SHORT') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT);
		} else if ($d == 'LONG' && $t == 'SHORT') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
		} else if ($d == 'FULL' && $t == 'SHORT') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
		}
		else if ($d == 'NONE' && $t == 'MEDIUM') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::NONE, IntlDateFormatter::MEDIUM);
		} else if ($d == 'SHORT' && $t == 'MEDIUM') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::SHORT, IntlDateFormatter::MEDIUM);
		} else if ($d == 'MEDIUM' && $t == 'MEDIUM') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM);
		} else if ($d == 'LONG' && $t == 'MEDIUM') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::LONG, IntlDateFormatter::MEDIUM);
		} else if ($d == 'FULL' && $t == 'MEDIUM') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::FULL, IntlDateFormatter::MEDIUM);
		}
		else if ($d == 'NONE' && $t == 'LONG') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::NONE, IntlDateFormatter::LONG);
		} else if ($d == 'SHORT' && $t == 'LONG') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::SHORT, IntlDateFormatter::LONG);
		} else if ($d == 'MEDIUM' && $t == 'LONG') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::MEDIUM, IntlDateFormatter::LONG);
		} else if ($d == 'LONG' && $t == 'LONG') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::LONG, IntlDateFormatter::LONG);
		} else if ($d == 'FULL' && $t == 'LONG') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::FULL, IntlDateFormatter::LONG);
		}
		else if ($d == 'NONE' && $t == 'FULL') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::NONE, IntlDateFormatter::FULL);
		} else if ($d == 'SHORT' && $t == 'FULL') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::SHORT, IntlDateFormatter::FULL);
		} else if ($d == 'MEDIUM' && $t == 'FULL') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::MEDIUM, IntlDateFormatter::FULL);
		} else if ($d == 'LONG' && $t == 'FULL') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::LONG, IntlDateFormatter::FULL);
		} else if ($d == 'FULL' && $t == 'FULL') {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
		} else if ($d == 'SQLDATE') {
			$return = new IntlDateFormatter(
				'en_US',
				IntlDateFormatter::FULL,
				IntlDateFormatter::FULL,
				'America/Los_Angeles',
				IntlDateFormatter::GREGORIAN,
				'yyyy-MM-dd'
			);
		} else if ($d == 'SQLDATETIME') {
			$return = new IntlDateFormatter(
				'en_US',
				IntlDateFormatter::FULL,
				IntlDateFormatter::FULL,
				'America/Los_Angeles',
				IntlDateFormatter::GREGORIAN,
				'yyyy-MM-dd hh-mm-ss'
			);
		} else {
			$return = new IntlDateFormatter($lg, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
		}

		return $return->format($date);

	}
	#########################################
	# Send Mail
	#########################################
	public static function SendMail (array $data)
	{
		$fromName = (isset($data['name']) AND !empty($data['name'])) ? $data['name'] : 'Bel-CMS MAIL';
		$fromMail = (isset($data['mail']) AND !empty($data['mail'])) ? $data['mail'] : 'no-reply@bel-cms.be';
		$subject  = (isset($data['subject']) AND !empty($data['subject'])) ? $data['subject'] : 'Bel-CMS MAIL';
		$content  = (isset($data['content']) AND !empty($data['content'])) ? $data['content'] : 'Testing Website mail';
		$sendMail = (isset($data['sendMail']) AND !empty($data['sendMail'])) ? $data['sendMail'] : false;

		if ($sendMail) {

			if (filter_var($sendMail, FILTER_VALIDATE_EMAIL)) {
				$headers   = array();
				$headers[] = "MIME-Version: 1.0";
				$headers[] = 'Content-Type: text/html; charset="utf-8"';
				$headers[] = "From: {$fromName} <{$fromMail}>";
				$headers[] = "Reply-To: NoReply <{$fromMail}>";
				$headers[] = "X-Mailer: PHP/".phpversion();
				$return = @mail($sendMail, $subject, $content, implode("\n", $headers));
			} else {
				$return = false;
			}

		} else {
			$return = false;
		}

		return $return;
	}
	#########################################
	# Convert Size
	#########################################
	public static function ConvertSize ($size)
	{
		$unit = array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}
	#########################################
	# Change all array to Upper
	#########################################
	public static function ArrayChangeCaseUpper($arr)
	{
		return array_map(function($item){
			if (is_array($item)) {
				$item = self::ArrayChangeCaseUpper($item, CASE_UPPER);
			}
			return $item;
		}, array_change_key_case($arr, CASE_UPPER));
	}
	#########################################
	# Change all array to Lower
	#########################################
	public static function ArrayChangeCaseLower($arr)
	{
		return array_map(function($item){
			if (is_array($item)) {
				$item = self::ArrayChangeCaseLower($item, CASE_LOWER);
			}
			return $item;
		}, array_change_key_case($arr, CASE_LOWER));
	}
	#########################################
	# Secure PHP - HTML Var
	#########################################
	public static function VarSecure ($data = null, $authorised = 'html') {
		$return = null;
		$base_html = '<p><hr><em><big><a><b><u><s><i><div><img><pre><br><ul><li><ol><tr><th><table><tbody><thead><tfoot><colgroup><span><strong><blockquote><iframe><font><h1><h2><h3><h4><h5><h6><font><sup><sub>';

		if ($authorised == 'html') {
			$authorised = $base_html;
		} else if ($authorised == null) {
			$authorised = '';
		}

		if ($data != null) {
			if (is_array($data)) {
				foreach ($data as $k => $v) {
					$return[$k] = strip_tags($v, $authorised);
				}
			} else {
				$return = strip_tags($data, $authorised);
			}
		}

		return $return;
	}
	#########################################
	# Test table if exists
	#########################################
	public static function TableExists ($table, $full = false)
	{

		$cnx = SqlConnection::getInstance();
		$cnx = $cnx->cnx;

		if ($full) {
			$sql = "SHOW TABLES";
		} else {
			$table = defined($table) ? constant($table) : $table;
			$sql = "SHOW TABLES LIKE '$table'";
		}

		$result = $cnx->query($sql);

		if ($full) {
			$return = $result;
		} else {
			if ($result->rowCount() > 0) {
				$return = true;
			} else {
				$return = false;
			}
		}

		return $return;
	}

	public static function ShowColumns ($table)
	{
		$cnx = SqlConnection::getInstance();
		$cnx = $cnx->cnx;
		$query = $cnx->prepare("SHOW COLUMNS FROM ".$table."");
		$query->execute();
		if ($query === false)
			return false;
		$list = $query->fetchAll(PDO::FETCH_COLUMN, 0);

		return $list;
	}
	#########################################
	# Request ID or rewrite_name secure
	#########################################
	public static function SecureRequest ($data = false) {

		$return = false;

		if ($data) {
			if (is_numeric($data)) {
				$return = intval($data);
			} else {
				$return = Common::VarSecure($data, null);
			}
		}

		return $return;
	}
	#########################################
	# Check exist page
	#########################################
	public static function ExistsPage ($search)
	{
		//$return = defined(strtoupper($search)) ? true : false;
		$return = strtoupper($search);
		if ($return) {
			$return = in_array($search, self::ScanDirectory(ROOT_PAGES)) ? true : false;
		}
		return (bool) $return;
	}
	#########################################
	# Check sub page
	#########################################
	public static function ExistsSubPage ($page = false, $view = false)
	{
		$return = array(
			'model'      => (bool) false,
			'view'       => (bool) false,
			'controller' => (bool) false
		);

		if (GET_PAGE == 'page') {
			$return = array(
				'model'      => (bool) true,
				'view'       => (bool) true,
				'controller' => (bool) true
			);
			return $return;
		}

		$page = ($page === false) ? GET_PAGE   : trim($page);
		$view = ($view === false) ? GET_ACTION : trim($view);

		if (!self::ExistsPage($page)) {
			$return = (bool) false;
		} else {
			$scan = Common::ScanFiles(constant('ROOT_PAGES').$page.DS);
			if (in_array('controller.php', $scan)) {
				$return['controller'] = (bool) true;
			}
			$view = 'view.'.$view.'.php';
			if (in_array($view, $scan)) {
				$return['view'] = (bool) true;
			}
			if (in_array('model.php', $scan)) {
				$return['model'] = (bool) true;
			}
		}

		return $return;
	}
	public static function translate ($data, $ucfirst = true) {
		$str  = $data;
		$data = self::makeConstant($data);
		$data = strtoupper($data);
		if (defined($data)) {
			$return = $ucfirst === true ? ucfirst(constant($data)) : $str;
		} else {
			$return = $ucfirst === true ? ucfirst($str) : $str;
		}
		return $return;
	}
	public static function transformOpt ($data, $reverse = false, $bool = false) {
		$return = array();
		if ($reverse === false) {
			$opt = explode('|', $data);
			foreach ($opt as $k => $v) {
				$tmp_opt = explode('=', $v);
				if ($bool === true) {
					$return[$tmp_opt[0]] = $tmp_opt[1] == 1 ? true : false;
				} else {
					$return[$tmp_opt[0]] = $tmp_opt[1];
				}
			}
		} else if ($reverse === true) {
			foreach ($data as $k => $v) {
				$v = (empty($v)) ? '0' : $v;
				$return[] = $k.'='.$v;
			}
			$return = implode('|', $return);
		}
		return $return;
	}
	public static function PaginationCount ($nb, $table, $where = false)
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
	public static function Pagination ($nbpp = 5, $page, $table, $where = false)
	{
		$management  = defined('MANAGEMENT') ? '?management&' : '?';
		$current     = (int) GET_PAGES;
		$page_url    = $page.$management;
		$total       = self::PaginationCount($nbpp, $table, $where);
		$adjacents   = 1;
		$current     = ($current == 0 ? 1 : $current);
		$start       = ($current - 1) * $nbpp;
		$prev        = $current - 1;
		$next        = $current + 1;
		$setLastpage = ceil($total/$nbpp);
		$lpm1        = $setLastpage - 1;
		$setPaginate = "";

		if ($setLastpage >= 1) {
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
				$setPaginate .= "<li><a href='{$page_url}page=$next'>Next</a></li>";
				$setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>Last</a></li>";
			} else{
				$setPaginate .= "<li class='active'><a>Next</a></li>";
				$setPaginate .= "<li class='active'><a>Last</a></li>";
			}

			$setPaginate.= "</ul>".PHP_EOL;
		}

		return $setPaginate;
	}
	#########################################
	# Security Upload
	#########################################
	public static function Upload ($name, $dir, $ext = false)
	{
		if ($_FILES[$name]['error'] != 4) {
			$return  = '';
			$dir     = DIR_UPLOADS.$dir;
			$file    = basename($_FILES[$name]['name']);
			$sizeMax = self::GetMaximumFileUploadSize();
			$size    = filesize($_FILES[$name]['tmp_name']);

			if (!file_exists($dir)) {
				mkdir($dir, 0777);
			}

			if (!is_writable($dir)) {
				chmod($dir, 0777);
			}

			if ($ext !== false) {
				$extensions = $ext;
			} else {
				$extensions = array('.png', '.gif', '.jpg', '.jpeg', '.doc', '.txt', '.pdf', '.rar', '.zip', '.7zip');
			}

			$extension = strrchr($_FILES[$name]['name'], '.');
			if (!in_array($extension, $extensions)) {
				$err = UPLOAD_ERROR_FILE;
			}

			if ($size>$sizeMax) {
				$err = UPLOAD_ERROR_SIZE;
			}

			if (!isset($err)) {
				if (move_uploaded_file($_FILES[$name]['tmp_name'], $dir .'/'. self::FormatName($file))) {
					$return = UPLOAD_FILE_SUCCESS;
				} else {
					$return = UPLOAD_ERROR;
				}
			} else {
				$return = $err;
			}
		} else {
			$return = 'UPLOAD_NONE';
		}
		return $return;
	}
	public static function FormatName ($n)
	{
		$n = strtr($n,
			'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
			'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
		$n = preg_replace('/([^.a-z0-9]+)/i', '-', $n);
		return $n;
	}
	public static function SizeFile ($file = false)
	{
		$return = filesize($file);
		$return = self::ConvertSize($return);
		return $return;
	}
	public static function ConvertPHPSizeToBytes ($s)
	{
		if (is_numeric($s)) {
			return $s;
		}
		$suffix = substr($s, -1);
		$r = substr($s, 0, -1);
		switch(strtoupper($suffix)) {
			case 'P':
				$r *= 1024;
			case 'T':
				$r *= 1024;
			case 'G':
				$r *= 1024;
			case 'M':
				$r *= 1024;
			case 'K':
				$r *= 1024;
			break;
		}
		return $r;
	}

	public static function GetMaximumFileUploadSize()
	{
		return min(self::ConvertPHPSizeToBytes(ini_get('post_max_size')), self::ConvertPHPSizeToBytes(ini_get('upload_max_filesize')));
	}
	public static function SiteMap()
	{
		$fp = fopen(ROOT.'/sitemap.xml', 'w+');
		if ($fp !== false){
			$file = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			$file .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

			$scanDir = self::ScanDirectory(ROOT_PAGES);
			$pages	 = array();

			$file .= '<url>'.PHP_EOL;
			$file .= '<loc>'.BASE_URL.'</loc>'.PHP_EOL;
			$file .= '<changefreq>daily</changefreq>'.PHP_EOL;
			$file .= '<priority>1</priority>'.PHP_EOL;
			$file .= '</url>'.PHP_EOL;

			foreach ($scanDir as $k => $v) {
				$file .= '<url>'.PHP_EOL;
				$file .= '<loc>'.BASE_URL.$v.'</loc>'.PHP_EOL;
				$file .= '<changefreq>daily</changefreq>'.PHP_EOL;
				$file .= '<priority>0.80</priority>'.PHP_EOL;
				$file .= '</url>'.PHP_EOL;
			}
			$file .= '</urlset>'.PHP_EOL;
			fwrite($fp, chr(0xEF) . chr(0xBB)  . chr(0xBF) . utf8_encode($file)); //Ajout de la marque d'Octet
			fclose($fp);
		}
	}
	#########################################
	# List Contry
	#########################################
	public static function contryList ()
	{
		return array (
			'AF' => 'Afghanistan',
			'ZA' => 'Afrique du Sud',
			'AL' => 'Albanie',
			'DZ' => 'Algérie',
			'DE' => 'Allemagne',
			'AD' => 'Andorre',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctique',
			'AG' => 'Antigua-et-Barbuda',
			'SA' => 'Arabie saoudite',
			'AR' => 'Argentine',
			'AM' => 'Arménie',
			'AW' => 'Aruba',
			'AU' => 'Australie',
			'AT' => 'Autriche',
			'AZ' => 'Azerbaïdjan',
			'BS' => 'Bahamas',
			'BH' => 'Bahreïn',
			'BD' => 'Bangladesh',
			'BB' => 'Barbade',
			'BE' => 'Belgique',
			'BZ' => 'Belize',
			'BJ' => 'Bénin',
			'BM' => 'Bermudes',
			'BT' => 'Bhoutan',
			'BY' => 'Biélorussie',
			'BO' => 'Bolivie',
			'BA' => 'Bosnie-Herzégovine',
			'BW' => 'Botswana',
			'BR' => 'Brésil',
			'BN' => 'Brunéi Darussalam',
			'BG' => 'Bulgarie',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodge',
			'CM' => 'Cameroun',
			'CA' => 'Canada',
			'CV' => 'Cap-Vert',
			'EA' => 'Ceuta et Melilla',
			'CL' => 'Chili',
			'CN' => 'Chine',
			'CY' => 'Chypre',
			'CO' => 'Colombie',
			'KM' => 'Comores',
			'CG' => 'Congo-Brazzaville',
			'CD' => 'Congo-Kinshasa',
			'KP' => 'Corée du Nord',
			'KR' => 'Corée du Sud',
			'CR' => 'Costa Rica',
			'CI' => 'Côte d’Ivoire',
			'HR' => 'Croatie',
			'CU' => 'Cuba',
			'CW' => 'Curaçao',
			'DK' => 'Danemark',
			'DG' => 'Diego Garcia',
			'DJ' => 'Djibouti',
			'DM' => 'Dominique',
			'EG' => 'Égypte',
			'SV' => 'El Salvador',
			'AE' => 'Émirats arabes unis',
			'EC' => 'Équateur',
			'ER' => 'Érythrée',
			'ES' => 'Espagne',
			'EE' => 'Estonie',
			'VA' => 'État de la Cité du Vatican',
			'FM' => 'États fédérés de Micronésie',
			'US' => 'États-Unis',
			'ET' => 'Éthiopie',
			'FJ' => 'Fidji',
			'FI' => 'Finlande',
			'FR' => 'France',
			'GA' => 'Gabon',
			'GM' => 'Gambie',
			'GE' => 'Géorgie',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Grèce',
			'GD' => 'Grenade',
			'GL' => 'Groenland',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernesey',
			'GN' => 'Guinée',
			'GQ' => 'Guinée équatoriale',
			'GW' => 'Guinée-Bissau',
			'GY' => 'Guyana',
			'GF' => 'Guyane française',
			'HT' => 'Haïti',
			'HN' => 'Honduras',
			'HU' => 'Hongrie',
			'CX' => 'Île Christmas',
			'AC' => 'Île de l’Ascension',
			'IM' => 'Île de Man',
			'NF' => 'Île Norfolk',
			'AX' => 'Îles Åland',
			'KY' => 'Îles Caïmans',
			'IC' => 'Îles Canaries',
			'CC' => 'Îles Cocos',
			'CK' => 'Îles Cook',
			'FO' => 'Îles Féroé',
			'GS' => 'Îles Géorgie du Sud et Sandwich du Sud',
			'FK' => 'Îles Malouines',
			'MP' => 'Îles Mariannes du Nord',
			'MH' => 'Îles Marshall',
			'UM' => 'Îles mineures éloignées des États-Unis',
			'SB' => 'Îles Salomon',
			'TC' => 'Îles Turques-et-Caïques',
			'VG' => 'Îles Vierges britanniques',
			'VI' => 'Îles Vierges des États-Unis',
			'IN' => 'Inde',
			'ID' => 'Indonésie',
			'IQ' => 'Irak',
			'IR' => 'Iran',
			'IE' => 'Irlande',
			'IS' => 'Islande',
			'IL' => 'Israël',
			'IT' => 'Italie',
			'JM' => 'Jamaïque',
			'JP' => 'Japon',
			'JE' => 'Jersey',
			'JO' => 'Jordanie',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KG' => 'Kirghizistan',
			'KI' => 'Kiribati',
			'XK' => 'Kosovo',
			'KW' => 'Koweït',
			'RE' => 'La Réunion',
			'LA' => 'Laos',
			'LS' => 'Lesotho',
			'LV' => 'Lettonie',
			'LB' => 'Liban',
			'LR' => 'Libéria',
			'LY' => 'Libye',
			'LI' => 'Liechtenstein',
			'LT' => 'Lituanie',
			'LU' => 'Luxembourg',
			'MK' => 'Macédoine',
			'MG' => 'Madagascar',
			'MY' => 'Malaisie',
			'MW' => 'Malawi',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malte',
			'MA' => 'Maroc',
			'MQ' => 'Martinique',
			'MU' => 'Maurice',
			'MR' => 'Mauritanie',
			'YT' => 'Mayotte',
			'MX' => 'Mexique',
			'MD' => 'Moldavie',
			'MC' => 'Monaco',
			'MN' => 'Mongolie',
			'ME' => 'Monténégro',
			'MS' => 'Montserrat',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibie',
			'NR' => 'Nauru',
			'NP' => 'Népal',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigéria',
			'NU' => 'Niue',
			'NO' => 'Norvège',
			'NC' => 'Nouvelle-Calédonie',
			'NZ' => 'Nouvelle-Zélande',
			'OM' => 'Oman',
			'UG' => 'Ouganda',
			'UZ' => 'Ouzbékistan',
			'PK' => 'Pakistan',
			'PW' => 'Palaos',
			'PA' => 'Panama',
			'PG' => 'Papouasie-Nouvelle-Guinée',
			'PY' => 'Paraguay',
			'NL' => 'Pays-Bas',
			'BQ' => 'Pays-Bas caribéens',
			'PE' => 'Pérou',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Pologne',
			'PF' => 'Polynésie française',
			'PR' => 'Porto Rico',
			'PT' => 'Portugal',
			'QA' => 'Qatar',
			'HK' => 'R.A.S. chinoise de Hong Kong',
			'MO' => 'R.A.S. chinoise de Macao',
			'CF' => 'République centrafricaine',
			'DO' => 'République dominicaine',
			'CZ' => 'République tchèque',
			'RO' => 'Roumanie',
			'GB' => 'Royaume-Uni',
			'RU' => 'Russie',
			'RW' => 'Rwanda',
			'EH' => 'Sahara occidental',
			'BL' => 'Saint-Barthélemy',
			'KN' => 'Saint-Christophe-et-Niévès',
			'SM' => 'Saint-Marin',
			'MF' => 'Saint-Martin (partie française)',
			'SX' => 'Saint-Martin (partie néerlandaise)',
			'PM' => 'Saint-Pierre-et-Miquelon',
			'VC' => 'Saint-Vincent-et-les-Grenadines',
			'SH' => 'Sainte-Hélène',
			'LC' => 'Sainte-Lucie',
			'WS' => 'Samoa',
			'AS' => 'Samoa américaines',
			'ST' => 'Sao Tomé-et-Principe',
			'SN' => 'Sénégal',
			'RS' => 'Serbie',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapour',
			'SK' => 'Slovaquie',
			'SI' => 'Slovénie',
			'SO' => 'Somalie',
			'SD' => 'Soudan',
			'SS' => 'Soudan du Sud',
			'LK' => 'Sri Lanka',
			'SE' => 'Suède',
			'CH' => 'Suisse',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard et Jan Mayen',
			'SZ' => 'Swaziland',
			'SY' => 'Syrie',
			'TJ' => 'Tadjikistan',
			'TW' => 'Taïwan',
			'TZ' => 'Tanzanie',
			'TD' => 'Tchad',
			'TF' => 'Terres australes françaises',
			'IO' => 'Territoire britannique de l’océan Indien',
			'PS' => 'Territoires palestiniens',
			'TH' => 'Thaïlande',
			'TL' => 'Timor oriental',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinité-et-Tobago',
			'TA' => 'Tristan da Cunha',
			'TN' => 'Tunisie',
			'TM' => 'Turkménistan',
			'TR' => 'Turquie',
			'TV' => 'Tuvalu',
			'UA' => 'Ukraine',
			'UY' => 'Uruguay',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'WF' => 'Wallis-et-Futuna',
			'YE' => 'Yémen',
			'ZM' => 'Zambie',
			'ZW' => 'Zimbabwe',
		);
	}

	public static function truncate($text, $chars = 25) {
		if (strlen($text) <= $chars) {
			return $text;
		}
		$text = $text." ";
		$text = substr($text,0,$chars);
		$text = substr($text,0,strrpos($text,' '));
		if (count($text) < $chars) {
			$text = $text."...";
		}
		return $text;
	}
}

final class Secure
{
	public static function isMail ($data = false)
	{
		$return = false;
		if ($data !== false) {
			if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
				$return = true;
			}
		}
		return $return;
	}

	public static function isBool ($data = false)
	{
		$return = false;
		if ($data !== false) {
			if (filter_var($data, FILTER_VALIDATE_BOOLEAN)) {
				$return = true;
			}
		}
		return $return;
	}

	public static function isInt ($data = false)
	{
		$return = false;
		if ($data !== false) {
			if (filter_var($data, FILTER_VALIDATE_INT)) {
				$return = true;
			}
		}
		return $return;
	}

	public static function isfloat ($data = false)
	{
		$return = false;
		if ($data !== false) {
			if (filter_var($data, FILTER_VALIDATE_FLOAT)) {
				$return = true;
			}
		}
		return $return;
	}

	public static function isIp ($data = false)
	{
		$return = false;
		if ($data !== false) {
			if (filter_var($data, FILTER_VALIDATE_IP)) {
				$return = true;
			}
		}
		return $return;
	}

	public static function isUrl ($data = false)
	{
		$return = false;
		if ($data !== false) {
			if (filter_var($data, FILTER_VALIDATE_URL)) {
				$return = true;
			}
		}
		return $return;
	}

	public function isString($data = false)
	{
		$return = false;
		if ($data !== false) {
			if (is_string($data)) {
				$return = true;
			}
		}
		return $return;
	}
}

function debug ($data = null, $exitAfter = false) {
	return Common::Debug($data, $exitAfter);
}
function cesure_href($d) {
	return '<a href="' . $d[1] . '" title="' . $d[1] . '" >[Lien]</a>';
}
function fixUrl ($d) {
	return strtr($d, array('?' => '%3F'));
}
function defixUrl ($d) {
	return strtr($d, array('%3F' => '?'));
}
function isFloat($d) {
	if (!is_scalar($d)) {return false;}

	$type = gettype($d);

	if ($type === "float") {
		return true;
	} else {
		return preg_match("/^\\d+\\.\\d+$/", $d) === 1;
	}
}
