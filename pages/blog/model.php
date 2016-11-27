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
		$this->sql = New BDD();
		$this->sql->table('TABLE_PAGES_BLOG');

		if ($id) {
			$request = Common::secureRequest($id);
			if (ctype_digit($id)) {
				$this->sql->where(array(
					'name'  => 'id',
					'value' => $request
				));
			} else {
				$this->sql->where(array(
					'name'  => 'rewrite_name',
					'value' => $request
				));
			}
			$this->sql->queryOne();
			if (!empty($this->sql->data)) {
				$this->sql->data->link = '/blog/readmore/'.$this->sql->data->rewrite_name;
				$this->sql->data->tags = explode(',', $this->sql->data->tags);
			}
		} else {
			$this->sql->limit($this->config['MAX_NEWS']);
			$this->sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
			$this->sql->queryAll();
			foreach ($this->sql->data as $k => $v) {
				$this->sql->data[$k]->link = '/blog/readmore/'.$v->rewrite_name;
				$this->sql->data[$k]->tags = explode(',', $this->sql->data[$k]->tags);
				$author = $this->sql->data[$k]->author;
				$this->sql->data[$k]->author = User::getInfosUser($author);
			}
		}
		return $this->sql->data;
	}
}
