<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$prdsearch_form = new Form(Html::uriquery2('mod_product', 'prdlist'), 'prdsearchform', 'check_prdsearch_info');
$prdsearch_form->open();
?>
<?php echo Html::input('text', 'prd_keyword', $prd_keyword, '',$prdsearch_form, 'RequiredTextbox', __('Please give me a keyword!')); ?><?php echo Html::input('submit', 'prdsearch_submit', __('Search')); ?>

<?php
$prdsearch_form->close();
$prdsearch_form->writeValidateJs(); 
?>