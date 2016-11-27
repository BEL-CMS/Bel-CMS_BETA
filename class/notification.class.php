<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */

final class Notification
{

	function __construct($title = null, $content = 'Unknow Error', $color = null, $ico = false, $die = false)
	{
		if ($die) {
			$GLOBALS['ERROR'] = array($title, $content);
		} else {
			if ($GLOBALS['CSS_GLOBAL'] === false):
			?>
  			<style type="text/css">
  			<?php
			require_once(ROOT_ASSETS.'styles'.DS.'global.css');
			?>
			</style>
			<?php
			$GLOBALS['CSS_GLOBAL'] = true;
			endif;
			?>
		<div class="belcms_notification <?php echo $color; ?>">
			<div class="info">
				<h1><?php echo ucfirst($title); ?></h1>
				<p><?php echo $content; ?></p>
			</div>
			<?php
			if ($ico):
			?>
			<div class="icon">
				<i class="<?php self::ico($color, $custom = false); ?>"></i>
			</div>
			<?php
			endif;
			?>
		</div>
		<?php
		}
	}

	public static function newPage ($title, $content)
	{
		include ROOT_TPL_DFT.'error/error.tpl.php';
		die();
	}

	private function ico ($ico = false, $custom = false)
	{
		if (!empty($custom)) {
			$return = trim($custom);
		} else
		switch ($ico) {
			case 'red':
				$return = 'fa fa-ban';
				break;

			case 'blue':
				$return = 'fa fa-eyedropper';
				break;

			case 'purple':
				$return = 'fa fa-external-link';
				break;

			case 'green':
				$return = 'fa fa-check';
				break;

			case 'orange':
				$return = 'fa fa-exclamation-circle';
				break;

			case 'yellow':
				$return = 'fa fa-exclamation-triangle';
				break;

			default:
				$return = 'fa fa-question-circle';
				break;
		}
		echo $return;
	}
	public static function ErrorAccesPage ()
	{
		$return = (bool) false;
		if (count(Common::getInfosPages('ACCESS_GROUPS')) == 0) {
			$return = (bool) true;
		} else {
			if (is_array($_SESSION['USER']['groups'])) {
				foreach ($_SESSION['USER']['groups'] as $v) {
					if (in_array($v, Common::getInfosPages('ACCESS_GROUPS'))) {
						$return = (bool) true;
						break;
					}
				}
			}
		}

		if ($return === false) {
			$return = array(ACCESS, NO_ACCESS_GROUP_PAGE, 'red');
		}

		return $return;
	}
}
