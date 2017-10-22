<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$cat_switch_form = new Form($_SERVER['REQUEST_URI'], 'catswform');
$cat_switch_form->open();
echo Html::select('cat_sw', 
    $select_categories, 
    $cat_sw, 'onchange="document.forms[\'catswform\'].submit();"');
$cat_switch_form->close();
?>