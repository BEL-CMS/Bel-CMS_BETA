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

class PrefGen extends Pages
{
	var $models = array('ModelsPrefGen');
	var $intern = 'true';

	public function index ()
	{
		$set['form'] = self::form();
		$this->set($set);
		$this->internManagement(true);
		$this->render('index');
	}

	public function send ()
	{
		$this->internManagement(true);
		$return = $this->ModelsPrefGen->send($_POST);
		$this->error('Paramètres', $return['text'], $return['type']);
		$this->redirect('prefgen?management', 3);
	}

	private function form ()
	{
		$formText     = array('CMS_WEBSITE_NAME', 'CMS_WEBSITE_DESCRIPTION', 'CMS_MAIL_WEBSITE', 'CMS_WEBSITE_KEYWORDS');
		$formRadio    = array(
			'CMS_JQUERY'      => array('on', 'off'),
			'CMS_JQUERY_UI'   => array('on', 'off'),
			'CMS_BOOTSTRAP'   => array('on', 'off'),
			'CMS_TPL_WEBSITE' => self::getTpl()
		);
		$cms_tpl_full   = Common::ScanDirectory(DIR_PAGES);
		$cms_tpl_full[] = 'readmore';
		$formCheckbox = array(
			'CMS_TPL_FULL'    => $cms_tpl_full
		);

		$sql = New BDD();
		$sql->table('TABLE_CONFIG');
		$sql->orderby(array(array('name' => 'name', 'type' => 'DESC')));
		$sql->queryAll();
		$return = $sql->data;

		$form  = '';

		foreach ($return as $d) {
			$input = '';
			$name  = (defined('ADMIN_'.$d->name)) ? constant('ADMIN_'.$d->name) : $d->name;
			$help  = (defined('ADMIN_'.$d->name.'_HELP')) ? constant('ADMIN_'.$d->name.'_HELP') : null;
			if (in_array($d->name, $formText)) {
				$input = '<input name="'.$d->id.'" type="text" class="form-control" id="label_'.$d->id.'" value="'.$d->value.'">';
			} else if (array_key_exists($d->name, $formRadio)) {
				foreach ($formRadio[$d->name] as $a) {
					$checked = $a == $d->value ? 'checked="checked"' : '';

					$input .= '<div class="form-group">';
					$input .= '<div class="input-group">';
					$input .= '<span class="input-group-addon">';
					$input .= '<input type="radio" name="'.$d->id.'" id="label_'.$d->id.'" value="'.$a.'" '.$checked.'>';
					$input .= '</span>';
					$input .= '<input class="form-control" type="text" value="'.$a.'" readonly>';
					$input .= '</div>';
					$input .= '</div>';
				}
			} else if (array_key_exists($d->name, $formCheckbox)) {
				$value = explode(',', $d->value);
				foreach ($formCheckbox[$d->name] as $a) {
					$checked = in_array($a, preg_replace('/\s+/', '', $value)) ? 'checked="checked"' : '';
					$input .= '<div class="form-group">';
					$input .= '<div class="input-group">';
					$input .= '<span class="input-group-addon">';
					$input .= '<input type="checkbox" name="'.$d->id.'[]" id="label_'.$d->id.'" value="'.$a.'" '.$checked.'>';
					$input .= '</span>';
					$input .= '<input class="form-control" type="text" value="'.$a.'" readonly>';
					$input .= '</div>';
					$input .= '</div>';
				}
			} else {
				$input = '<input name="'.$d->id.'" type="text" class="form-control" id="label_'.$d->id.'" value="'.$d->value.'">';
			}
			$form .= '<div class="row">';
			$form .= '<div class="col-sm-12">';
			$form .= '<div class="form-group">';
			$form .= '<label for="label_'.$d->id.'">'.$name.'</label>';
			$form .= $input;
			$form .= $help;
			$form .= '</div>';
			$form .= '</div>';
			$form .= '</div>';

			unset($name,$help, $input);
		}
		return $form;
	}
	private function getTpl ()
	{
		$return = Common::ScanDirectory(DIR_TPL);

		if (count($return) !== 0) {
			foreach ($return as $k => $n) {
				if (!is_file(DIR_TPL.$n.DS.'template.php')) {
					unset($return[$k]);
				}
			}
		}

		return $return;
	}
}
