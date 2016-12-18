<?php
if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
?>
<section id="bel_cms_members_view" class="padding-top-60 padding-top-sm-30">
	<div class="">
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-3">

				<div class="widget center">
					<div id="bel_cms_members_view_avatar">
						<img data-toggle="tooltip" title="<?=$this->data['username']?>" src="<?=$this->data['avatar']?>" alt="Avatar_<?=$this->data['username']?>">
					</div>
				</div>

				<div class="widget">
					<div class="panel panel-default">
						<div class="panel-heading">Groups</div>
						<div class="panel-body no-padding">
							<ul class="panel-list-bordered">
								<?php
								foreach ($this->data['groups'] as $k => $v):
									echo '<li id="groups_'.$k.'"><a href="Members/View/'.$this->data['username'].'#groups_'.$k.'">'.$v.'</a></li>';
								endforeach;
								?>
							</ul>
						</div>
					</div>
				</div>

				<div class="widget">
					<div class="panel panel-default">
						<div class="panel-heading"><?=ABOUT_ME?></div>
						<div class="panel-body">
							<?=$this->data['info_text']?>
						</div>
					</div>
				</div>
				
				<div class="widget widget-friends">
					<div class="panel panel-default">
						<div class="panel-heading">Friends (<?=count($this->data['friends'])?>)</div>
						<div class="panel-body">
							<ul>
								<?php
								foreach ($this->data['friends'] as $k => $v):
									$li  = '<li>';
									$li .= '<a href="Members/View/'.$v['name'].'" data-toggle="tooltip" title="'.$v['name'].'">';
									$li .= '<img src="'.$v['avatar'].'" alt="friends_'.$v['name'].'">';
									$li .= '</a></li>';
									echo $li;
								endforeach;
								?>
							</ul>
						</div>
					</div>
				</div>

			</div>
			
			<div class="col-xs-12 col-sm-8 col-md-9">
				<div class="widget">
					<table class="table">
						<tr>
							<td class="col-md-6 col-sm-6">
								<span class="label label-primary label-icon-left"><i class="fa fa-facebook" aria-hidden="true"></i> facebook</span></td>
							<td class="col-md-6 col-sm-6"><?=$this->data['facebook']?></td>
						</tr>
						<tr>
							<td class="col-md-6 col-sm-6"><span class="label label-info label-icon-left"><i class="fa fa-twitter" aria-hidden="true"></i> twitter</span></td>
							<td class="col-md-6 col-sm-6"><?=$this->data['twitter']?></td>
						</tr>
						<tr>
							<td class="col-md-6 col-sm-6"><span class="label label-success label-icon-left"><i class="fa fa-linkedin" aria-hidden="true"></i> linkedin</span></td>
							<td class="col-md-6 col-sm-6"><?=$this->data['linkedin']?></td>
						</tr>
						<tr>
							<td class="col-md-6 col-sm-6"><span class="label label-danger label-icon-left"><i class="fa fa-google-plus" aria-hidden="true"></i>
 googleplus</span></td>
							<td class="col-md-6 col-sm-6"><?=$this->data['googleplus']?></td>
						</tr>
						<tr>
							<td class="col-md-6 col-sm-6"><span class="label label-warning label-icon-left"><i class="fa fa-pinterest" aria-hidden="true"></i> pinterest</span></td>
							<td class="col-md-6 col-sm-6"><?=$this->data['pinterest']?></td>
						</tr>
					</table>
				</div>

				<h4 class="page-header text-center no-padding"><i class="fa fa-comments-o"></i> 3 <?=LAST_FORUM?></h4>
				<?php
				foreach ($this->data['forum'] as $k => $v):
				?>
				<div class="panel panel-default panel-post">
					<div class="panel-body">
						<div class="post">
							<div class="post-header post-author">
								<div class="post-title">
									<h3><?=$v->title?></h3>
									<ul class="post-meta">
										<li><i class="fa fa-calendar-o"></i> <?=$v->date_post?></li>
									</ul>
								</div>
							</div>
							<?=$v->content?>
						</div>
					</div>
				</div>
				<?php
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>