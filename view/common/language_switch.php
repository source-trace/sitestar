<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$lang_switch_form = new Form($_SERVER['REQUEST_URI'], 'langswform');
$lang_switch_form->open();
echo Html::select('lang_sw', 
    Toolkit::toSelectArray($langs, 'locale', 'name'), 
    $lang_sw, 'onchange="document.forms[\'langswform\'].submit();"');
$lang_switch_form->close();
?>
