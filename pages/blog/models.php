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

class ModelsBlog
{
	public function GetBlog ($id = false)
	{

		if (defined('MANAGEMENT')) {
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

		$page = (Dispatcher::RequestPages() * $nbpp) - $nbpp;

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
				$sql->data->link = 'blog/readmore/'.$sql->data->rewrite_name.'?id='.$sql->data->id;
				if (empty($sql->data->tags)) {
					$sql->data->tags = array();
				} else {
					$sql->data->tags = explode(',', $sql->data->tags);
				}
				$author = $sql->data->author;
				if (empty($author)) {
					$sql->data->author =  (object) array(
						'username' => DELETE,
						'avatar'   => DEFAULT_AVATAR,
						'groups'   => array()
					);
				} else {
					$sql->data->author = AutoUser::getInfosUser($author);
				}
			}
		} else {
			$sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
			$sql->limit(array(0 => $page, 1 => $nbpp), true);
			$sql->queryAll();
			foreach ($sql->data as $k => $v) {
				$sql->data[$k]->link = 'blog/readmore/'.$v->rewrite_name.'/'.$v->id;
				if (empty($sql->data[$k]->tags)) {
					$sql->data[$k]->tags = array();
				} else {
					$sql->data[$k]->tags = explode(',', $sql->data[$k]->tags);
				}
				$author = $sql->data[$k]->author;
				if (empty($author)) {
					$sql->data[$k]->author =  (object) array(
						'username' => DELETE,
						'avatar'   => DEFAULT_AVATAR,
						'groups'   => array()
					);
				} else {
					$sql->data[$k]->author = AutoUser::getInfosUser($author);
				}
			}
		}
		return $sql->data;
	}
}