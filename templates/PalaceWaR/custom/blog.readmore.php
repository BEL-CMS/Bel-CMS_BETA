<?php

$tags = null;
if (count($blog->tags) != 0) {
    $tags .= '<div class="nk-post-categories">';
    foreach ($blog->tags as $v) {
        $tags .= '<span class="bg-main-1">'.$v.'</span>';
    }
    $tags .= '</div>';
}
?>
<h3 class="nk-decorated-h-2">
	<span><?=$blog->name?></span>
</h3>

<div class="nk-post-text mt-0">
    <div class="nk-gap-1"></div>
    <div class="nk-post-by">
        <img src="<?=$blog->author->avatar?>" alt="<?=$blog->author->username?>" class="img-circle" width="35"> by <a href="Members/<?=$blog->author->username?>"><?=$blog->author->username?></a> in <?=Common::transformDate($blog->date_create, true, 'd-M-Y # H:i')?>
        <?=$tags?>
    </div>
    <div class="nk-gap"></div>
    <?=$blog->content?>

    <div class="nk-gap"></div>
    <div class="nk-post-share">
        <span class="h5">Share Article:</span>

        <ul class="nk-social-links-2">
            <li>
                <span class="nk-social-facebook" title="Share page on Facebook" data-share="facebook">
                    <span class="fa fa-facebook"></span>
                </span>
            </li>
            <li>
                <span class="nk-social-google-plus" title="Share page on Google+" data-share="google-plus">
                    <span class="fa fa-google-plus"></span>
                </span>
            </li>
            <li>
                <span class="nk-social-twitter" title="Share page on Twitter" data-share="twitter">
                    <span class="fa fa-twitter"></span>
                </span>
            </li>
        </ul>
    </div>
</div>
<?php New Comment; ?>
