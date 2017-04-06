<?php
foreach ($blog as $k => $v):
?>
<div class="col-md-12 bg-dark-2 main_blog">
	<div class="nk-blog-post nk-blog-post-border-bottom">
		<div class="nk-gap"></div>
		<h2 class="nk-post-title h4"><a href="<?=$v->link?>"><?=$v->name?></a></h2>
		<div class="nk-post-text">
			<?=$v->content?>
		</div>
		<div class="nk-gap"></div>
		<a href="<?php echo $v->link; ?>" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Read More</a>
		<div class="nk-post-date pull-right">
			<span class="fa fa-calendar"></span> <?= Common::transformDate($v->date_create, true, 'd-M-Y # H:i')?>
		</div>
	</div>
</div>
<?php
endforeach;
echo $this->pagination;

