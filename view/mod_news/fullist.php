<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>


<div class="art_list">
	<div class="art_list_title"><?php echo $category->name; ?></div>
	<div class="art_list_search"><?php include_once(dirname(__FILE__).'/_search.php'); ?></div>
	<div class="art_list_con">
		<ul>
		<?php
        if (sizeof($articles) > 0) {
            $row_idx = 0;
            foreach ($articles as $article) {
        ?>
		<?php if (defined('SYSVER')) { ?>
		<li><p class="l_title"><a href="index.php?<?php if($article->article_category_id == 2) echo Html::xuriquery('mod_news', 'news_content', array('news_id' => $article->id)); else echo Html::xuriquery('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></p><p class="n_time"><?php echo date('Y-m-d H:i', $article->create_time); ?></p></li>
		<?php } else { ?>
		<li><p class="l_title"><a href="index.php?<?php echo Html::xuriquery('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></p><p class="n_time"><?php echo date('Y-m-d H:i', $article->create_time); ?></p></li>
		<?php } ?>
		
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
		<div class="norecords"><?php _e('No Records!'); ?></div>
		<?php } ?>
		</ul>
	</div>
<?php include_once(P_TPL_VIEW.'/view/common/pager.php'); ?>
</div>