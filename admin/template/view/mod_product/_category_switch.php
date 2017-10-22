<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$cap_switch_form = new Form('index.php?_m=mod_product&_a=admin_list', 'capswform');
$cap_switch_form->open();
echo Html::select('cap_sw', 
    $select_categories, 
    $cap_sw, 'onchange="document.forms[\'capswform\'].submit();"');
echo Html::input('hidden', 'hidkeyword', '');
$cap_switch_form->close();
?>
