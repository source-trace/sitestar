<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$downloadsearch_form = new Form($_SERVER['REQUEST_URI'], 'downloadsearchform', 'check_downloadsearch_info');
$downloadsearch_form->open();
?>
<?php echo Html::input('text', 'download_keyword', $download_keyword, '',$downloadsearch_form, 'RequiredTextbox', __('Please give me a keyword!')); ?><?php echo Html::input('submit', 'downloadsearch_submit', __('Search')); ?>
<?php
$downloadsearch_form->close();
$downloadsearch_form->writeValidateJs();
?>