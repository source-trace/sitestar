<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$caa_switch_form = new Form($_SERVER['REQUEST_URI'], 'caaswform');
$caa_switch_form->open();
echo Html::select('caa_sw', 
    $select_categories, 
    $caa_sw, 'onchange="document.forms[\'caaswform\'].submit();"');
$caa_switch_form->close();
?>
