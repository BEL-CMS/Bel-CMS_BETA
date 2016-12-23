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

final class Comments
{
	function __construct($action = 'view', $url = false, $nb = 5)
	{
		$this->url = $url === false ? GET_PAGE.DS.GET_ACTION.DS.GET_ID.'?id='.GET_ID_SUPP : $url;
		$this->nb  = (int) $nb;
		self::$action();
	}
	private function view ()
	{
		$comments = self::GetComments($this->url, $this->nb);
		$user  = User::ReturnUser();
		if ($user !== false) {
			$form =  '<form class="alertAjaxForm" action="comments/send&ajax" method="post">';
			$form .= '<a href="Members/View/'.$user->username.'" class="bel_cms_comments_tabs_user">';
			$form .= '<img class="commentsAvatar" alt="avatar_'.$user->username.'" src="'.$user->avatar.'">';
			$form .= '</a>';
			$form .= '<div class="bel_cms_comments_tabs_post">';
			$form .= '<a href="Members/View/'.$user->username.'">'.$user->username.'</a>';
			$form .= '<textarea placeholder="'.YOUR_COMMENT.' ..." name="text"></textarea>';
			$form .= '<input type="hidden" name="url" value="'.$this->url.'">';
			$form .= '<button class="btn btn-default" type="submit"><i class="fa fa-share-square"></i> '.PUBLISH.'</button>';
			$form .= '</div>';
			$form .= '</form>';
		} else {
			$form = '<a href="User/Login" title="'.SIGN_IN.'">'.SIGN_IN.'</a>';
		}
		unset($user);            	
		if ($comments !== null) {
			$li = '';
			foreach ($comments as $k => $v) {
				$user = User::getNameAvatar($v->hash_key);
				$li .= '<li>';
				$li .= '<a href="Members/View/'.$user->username.'" class="bel_cms_comments_tabs_user">';
				$li .= '<img class="commentsAvatar" alt="avatar_'.$user->username.'" src="'.$user->avatar.'">';
				$li .= '</a>';
				$li .= '<div class="bel_cms_comments_tabs_post">';
				$li .= '<a href="#">'.$user->username.'</a>';
				$li .= '<span class="commentsDate">'.Common::TransformDate($v->date_com, false, 'd/m/Y H:i').'</span>';
				$li .= '<p>';
				$li .= $v->comment;
				$li .= '</p>';
				$li .= '</div>';
				$li .= '</li>';
			}
		} else {
			$li = '';
		}
		unset($user);
		$html  = '<section class="bel_cms_comments panel panel-default">';
		$html .= '	<div class="panel-heading">Commentaires</div>';
		$html .= '	<div class="panel-body">';
		$html .= '		<ul class="nav nav-tabs">
							<li class="active">
								<a href="#commentsIn" data-toggle="tab" aria-expanded="true"><i class="fa fa-commenting"></i> '.CMS_WEBSITE_NAME.'</a>
							</li>
							<li>
								<a href="#commentsOut" data-toggle="tab" aria-expanded="false"><i class="fa fa-comments"></i> Facebook</a>
							</li>
						</ul>';
		$html .= '		<div class="tab-content bel_cms_comments_tabs_in">
							<div class="tab-pane fade in active" id="commentsIn">
								'.$form.'
								<ul>
								'.$li.'
								</ul>
							</div>
							<div class="tab-pane fade" id="commentsOut">
								<div class="fb-comments" data-href="'.BASE_URL.$this->url.'" data-numposts="'.$this->nb.'" data-colorscheme="light"></div>
							</div>	
					 	</div>';
		$html .= '	</div>';
		$html .= '</section><div class="clear"></div>';
		echo $html;
	}
	private function GetComments ($url = false, $limit = false)
	{
		$return = null;
		$sql = New BDD();
		$sql->table('TABLE_COMMENTS');
		$sql->orderby(array(array('name' => 'id', 'type' => 'DESC')));
		if (!empty($url)) {
			$sql->where(array('name' => 'link', 'value' => $url));
		}
		if ($limit !== false) {
			$sql->limit($limit);
		}
		$sql->queryAll();
		if (!empty($sql->data)) {
			$return = $sql->data;
		}
		return $return;
	}
}
