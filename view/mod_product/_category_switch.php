<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$cap_switch_form = new Form($_SERVER['REQUEST_URI'], 'capswform');
$cap_switch_form->open();
echo Html::select('cap_sw', 
    $select_categories, 
    $cap_sw, 'onchange="document.forms[\'capswform\'].submit();"');
$cap_switch_form->close();

?>
