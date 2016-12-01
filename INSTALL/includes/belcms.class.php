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

class BelCMS
{
	var $page;

	function __construct()
	{
		if (!session_id()) {
			session_start();
		}
		$this->page = (!isset($_REQUEST['page'])) ? 'home' : $_REQUEST['page'];
		require_once ROOT.DS.'includes'.DS.'checkCompatibility.php';
	}

	public function VIEW()
	{
		ob_start();
		require ROOT.DS.'pages'.DS.$this->page.'.tpl';
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function HTML()
	{
		if ($_REQUEST['page'] == 'CreateSQL'):
			$table = $_REQUEST['table'];
			require_once ROOT.DS.'includes'.DS.'tables.php';
			if ($error === true) {
				echo $class;
			} else {
				echo false;
			}
		else:
			ob_start("ob_gzhandler");
			?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
			<meta http-equiv="content-type" content="text/html; charset=UTF-8">
			<meta charset="utf-8">
			<title>BEL-CMS # Install</title>
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
			<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
			<link href="css/styles.css" rel="stylesheet">
			</head>
			<body>
				<div class="page-container">

					<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
						<div class="navbar-header">
							<a class="navbar-brand" href="#">BEL-CMS Installation</a>
						</div>
					</nav>

					<div id="container" class="container">
						<?=self::VIEW()?>
					</div>
				</div>

				<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
				<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
				<script src="js/scripts.js"></script>
			</body>
			</html>
			<?php
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		endif;
	}

	public static function TABLES () {

		$tables = array(
			'config',
			'config_pages',
			'groups',
			'mails_blacklist',
			'page',
			'page_blog',
			'page_blog_cat',
			'page_forum',
			'page_forum_post',
			'page_forum_posts',
			'page_forum_threads',
			'page_shoutbox',
			'page_users',
			'page_users_management',
			'page_users_profils',
			'page_users_social',
			'stats',
			'visitors',
			'widgets'
		);

		return $tables;
	}
}
#########################################
# Debug
#########################################
function debug ($data = null, $exitAfter = false)
{
	echo '<pre>';
		print_r($data);
	echo '</pre>';
	if ($exitAfter === true) {
		exit();
	}
}
function redirect ($url = null, $time = null)
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
?>