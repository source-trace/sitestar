<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$articlesearch_form = new Form($_SERVER['REQUEST_URI'], 'articlesearchform', 'check_articlesearch_info');
$articlesearch_form->open();
?>
<?php echo Html::input('text', 'article_keyword', $article_keyword, '', $articlesearch_form, 'RequiredTextbox', __('Please give me a keyword!')); ?><?php echo Html::input('submit', 'articlesearch_submit', __('Search')); ?>
						
<?php
$articlesearch_form->close();
$articlesearch_form->writeValidateJs();
?>