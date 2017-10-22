<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle alignleft"><?php echo $curr_article->title; ?></div>
</div>
<div class="articleinfo alignleft medium">
    <?php _e('Publish Time'); ?>: <?php echo date('Y-m-d H:i', $curr_article->create_time); ?>&nbsp;
    <?php echo $curr_article->v_num.' '.__('Views'); ?>
</div>
<div class="contentbody">
    <blockquote class="articleintro">
        <?php echo $curr_article->intro; ?>
    </blockquote>
    <div class="articlecontent">
        <?php echo $curr_article->content; ?>
    </div>
    <div class="articlesource alignleft medium">
        <?php _e('Source'); ?>: <?php echo $curr_article->source; ?>
    </div>
</div>
