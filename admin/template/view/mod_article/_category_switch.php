<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$caa_switch_form = new Form('index.php?_m=mod_article&_a=admin_list', 'caaswform');
$caa_switch_form->open();
echo Html::select('caa_sw', 
    $select_categories, 
    $caa_sw, 'onchange="document.forms[\'caaswform\'].submit();"');
echo Html::input('hidden', 'hidkeyword', '');
$caa_switch_form->close();
?>
