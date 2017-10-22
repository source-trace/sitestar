<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$bulletinsearch_form = new Form($_SERVER['REQUEST_URI'], 'bulletinsearchform', 'check_articlesearch_info');
$bulletinsearch_form->open();
?>
<?php echo Html::input('text', 'bulletin_keyword', $bulletin_keyword, '', $bulletinsearch_form, 'RequiredTextbox', __('Please give me a keyword!')); ?><?php echo Html::input('submit', 'articlesearch_submit', __('Search')); ?>
						
<?php
$bulletinsearch_form->close();
$bulletinsearch_form->writeValidateJs();
?>