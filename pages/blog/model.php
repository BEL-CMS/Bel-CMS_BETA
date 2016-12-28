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

class ModelPagesBlog
{
	protected function GetBlog ($id = false)
	{
		$management = defined('MANAGEMENT') ? true : false;

		if ($management) {
			if (isset($_SESSION['pages']->blog->config['MAX_BLOG_ADMIN'])) {
				$nbpp = (int) $_SESSION['pages']->blog->config['MAX_BLOG_ADMIN'];
			} else {
				$nbpp = (int) 25;
			}
		} else {
			if (isset($_SESSION['pages']->blog->config['MAX_BLOG'])) {
				$nbpp = (int) $_SESSION['pages']->blog->config['MAX_BLOG'];
			} else {
				$nbpp = (int) 3;
			}
		}

		$page = (GET_PAGES * $nbpp) - $nbpp;

		if (isset($_REQUEST['id'])) {
			$id = (int) $_REQUEST['id'];
		}

		$sql = New BDD();
		$sql->table('TABLE_PAGES_BLOG');

		if ($id) {
			$request = Common::secureRequest($id);
			if (is_numeric($id)) {
				$sql->where(array(
					'name'  => 'id',
					'value' => $request
				));
			} else {
				$sql->where(array(
					'name'  => 'rewrite_name',
					'value' => $request
				));
			}
			$sql->queryOne();
			if (!empty($sql->data)) {
				$sql->data->link = '/blog/readmore/'.$sql->data->rewrite_name.'?id='.$sql->data->id;
				if (empty($sql->data->tags)) {
					$sql->data->tags = array();
				} else {
					$sql->data->tags = explode(',', $sql->data->tags);
				}
				$author = $sql->data->author;
				$sql->data->author = User::getInfosUser($author);
			}
		} else {
			$sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
			$sql->limit(array(0 => $page, 1 => $nbpp), true);
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$sql->data[$k]->link = '/blog/readmore/'.$v->rewrite_name.'?id='.$v->id;
				if (empty($sql->data[$k]->tags)) {
					$sql->data[$k]->tags = array();
				} else {
					$sql->data[$k]->tags = explode(',', $sql->data[$k]->tags);
				}
				$author = $sql->data[$k]->author;
				$sql->data[$k]->author = User::getInfosUser($author);
			}
		}
		return $sql->data;
	}
}
