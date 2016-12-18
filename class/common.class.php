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
			'$' => 's', '&' => '_AND_');
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
	# Transform date
	#########################################
	public static function TransformDate ($date, $time = false, $custom = false)
	{
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
	public static function VarSecure ($data = null, $authorised = null) {
		$return = null;
		$base_secure = '<p><hr><a><b><u><i><div><img><pre><br><ul><li><ol><tr><table><tbody><thead><tfoot><colgroup><span><strong><blockquote><iframe><font>';
		$authorised = ($authorised == null) ? $base_secure : $authorised;

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
	#########################################
	# Request ID or rewrite_name secure
	#########################################
	public static function SecureRequest ($data = false) {

		$return = false;

		if ($data) {
			if (ctype_digit($data)) {
				$return = intval($data);
			} else {
				$return = Common::MakeConstant($data);
			}
		}

		return $return;
	}	#########################################
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
		$return = '';
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
	public static function Pagination ($nb, $table, $where = false)
	{
		$return = 0;

		$sql = New BDD();
		$sql->table($table);
		if ($where !== false) {
			$sql->where($where);
		}
		$sql->count();
		$total = $sql->data;
		$return = ceil($total/$nb);

		return $return;
	}
	public static function PaginationHtml ($nb, $table, $where = false)
	{
		$count   = Common::Pagination($nb, $table, $where);
		$current = GET_PAGES;
		$return = '<ul class="pagination">';
		for ($i=1; $i <= $count ; $i++):
			if ($current +1 == $i) {
				$active = 'class="active"';
			} else {
				$active = '';
			}
			$page = $i -1;
			$return .= '<li '.$active.'><a href="'.GET_PAGE.'?page='.$page.'">'.$i.'</a></li>';
		endfor;
		$return .= '</ul>';

		return $return;
	}
	#########################################
	# Security Upload
	#########################################
	public static function Upload ($name, $dir, $ext = false)
	{
		if ($_FILES[$name]['error'] != 4) {
			$return  = '';
			$dir     = ROOT_UPLOADS.$dir;
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
	#########################################
	# List Contry
	#########################################
	public static function contryList ()
	{
		return array(
			'United States',
			'United Kingdom',
			'Afghanistan',
			'Albania',
			'Algeria',
			'American Samoa',
			'Andorra',
			'Angola ',
			'Anguilla',
			'Antarctica',
			'Antigua and Barbuda',
			'Argentina',
			'Armenia',
			'Aruba',
			'Australia',
			'Austria ',
			'Azerbaijan',
			'Bahamas',
			'Bahrain',
			'Bangladesh ',
			'Barbados',
			'Belarus',
			'Belgium',
			'Belize',
			'Benin',
			'Bermuda',
			'Bhutan',
			'Bolivia',
			'Bosnia and Herzegovina ',
			'Botswana',
			'Bouvet Island',
			'Brazil',
			'British Indian Ocean Territory ',
			'Brunei Darussalam',
			'Bulgaria',
			'Burkina Faso',
			'Burundi',
			'Cambodia',
			'Cameroon',
			'Canada ',
			'Cape Verde',
			'Cayman Islands',
			'Central African Republic',
			'Chad',
			'Chile',
			'China',
			'Christmas Island',
			'Cocos (Keeling) Islands',
			'Colombia',
			'Comoros',
			'Congo',
			'Congo, The Democratic Republic of The',
			'Cook Islands',
			'Costa Rica',
			'Cote D\'ivoire ',
			'Croatia',
			'Cuba',
			'Cyprus> ',
			'Czech Republic ',
			'Denmark',
			'Djibouti ',
			'Dominica',
			'Dominican Republic',
			'Ecuador',
			'Egypt',
			'El Salvador',
			'Equatorial Guinea',
			'Eritrea',
			'Estonia',
			'Ethiopia',
			'Falkland Islands (Malvinas)',
			'Faroe Islands ',
			'Fiji ',
			'Finland',
			'France ',
			'French Guiana',
			'French Polynesia',
			'French Southern Territories',
			'Gabon',
			'Gambia',
			'Georgia',
			'Germany',
			'Ghana ',
			'Gibraltar',
			'Greece',
			'Greenland',
			'Grenada ',
			'Guadeloupe',
			'Guam ',
			'Guatemala',
			'Guinea',
			'Guinea-bissau',
			'Guyana',
			'Haiti',
			'Heard Island and Mcdonald Islands',
			'Holy See (Vatican City State)',
			'Honduras',
			'Hong Kong ',
			'Hungary',
			'Iceland',
			'India',
			'Indonesia',
			'Iran, Islamic Republic of',
			'Iraq',
			'Ireland',
			'Israel',
			'Italy',
			'Jamaica',
			'Japan ',
			'Jordan',
			'Kazakhstan',
			'Kenya',
			'Kiribati',
			'Korea, Democratic People\'s Republic of',
			'Korea, Republic of',
			'Kuwait',
			'Kyrgyzstan ',
			'Lao People\'s Democratic Republic',
			'Latvia',
			'Lebanon ',
			'Lesotho',
			'Liberia',
			'Libyan Arab Jamahiriya',
			'Liechtenstein ',
			'Lithuania',
			'Luxembourg',
			'Macao',
			'Macedonia, The Former Yugoslav Republic of',
			'Madagascar',
			'Malawi',
			'Malaysia',
			'Maldives',
			'Mali',
			'Malta',
			'Marshall Islands ',
			'Martinique',
			'Mauritania',
			'Mauritius',
			'Mayotte',
			'Mexico',
			'Micronesia, Federated States of',
			'Moldova, Republic of',
			'Monaco',
			'Mongolia',
			'Montserrat',
			'Morocco',
			'Mozambique',
			'Myanmar',
			'Namibia',
			'Nauru ',
			'Nepal ',
			'Netherlands ',
			'Netherlands Antilles',
			'New Caledonia ',
			'New Zealand',
			'Nicaragua ',
			'Niger',
			'Nigeria',
			'Niue',
			'Norfolk Island',
			'Northern Mariana Islands',
			'Norway ',
			'Oman',
			'Pakistan',
			'Palau',
			'Palestinian Territory, Occupied',
			'Panama',
			'Papua New Guinea',
			'Paraguay ',
			'Peru',
			'Philippines ',
			'Pitcairn',
			'Poland',
			'Portugal',
			'Puerto Rico',
			'Qatar',
			'Reunion',
			'Romania',
			'Russian Federation',
			'Rwanda',
			'Saint Helena',
			'Saint Kitts and Nevis ',
			'Saint Lucia',
			'Saint Pierre and Miquelon',
			'Saint Vincent and The Grenadines',
			'Samoa',
			'San Marino',
			'Sao Tome and Principe ',
			'Saudi Arabia',
			'Senegal',
			'Serbia and Montenegro',
			'Seychelles',
			'Sierra Leone ',
			'Singapore ',
			'Slovakia',
			'Slovenia',
			'Solomon Islands',
			'Somalia ',
			'South Africa',
			'South Georgia and The South Sandwich Islands',
			'Spain ',
			'Sri Lanka',
			'Sudan',
			'Suriname',
			'Svalbard and Jan Mayen',
			'Swaziland',
			'Sweden ',
			'Switzerland',
			'Syrian Arab Republic ',
			'Taiwan, Province of China',
			'Tajikistan',
			'Tanzania, United Republic of',
			'Thailand',
			'Timor-leste',
			'Togo',
			'Tokelau',
			'Tonga',
			'Trinidad and Tobago',
			'Tunisia',
			'Turkey',
			'Turkmenistan',
			'Turks and Caicos Islands ',
			'Tuvalu',
			'Uganda ',
			'Ukraine',
			'United Arab Emirates',
			'United Kingdom ',
			'United States ',
			'United States Minor Outlying Islands',
			'Uruguay ',
			'Uzbekistan ',
			'Vanuatu',
			'Venezuela',
			'Viet Nam',
			'Virgin Islands, British',
			'Virgin Islands, U.S.',
			'Wallis and Futuna ',
			'Western Sahara',
			'Yemen',
			'Zambia',
			'Zimbabwe'
		);
	}
}
function debug ($data = null, $exitAfter = false)
{
	return Common::Debug($data, $exitAfter);
}
function cesure_href($d) { 
    return '<a href="' . $d[1] . '" title="' . $d[1] . '" >[Lien]</a>';      
}