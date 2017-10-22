<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$lang_switch_form = new Form($_SERVER['REQUEST_URI'], 'langswform');
$lang_switch_form->open();
echo Html::input('hidden',"type","sw");
echo Html::input('hidden',"_m",$_REQUEST['_m']);
echo Html::input('hidden',"_a",$_REQUEST['_a']);
echo Html::select('lang_sw', 
    Toolkit::toSelectArray($langs, 'locale', 'name'), 
    $lang_sw, 'onchange="document.forms[\'langswform\'].submit();"');
$lang_switch_form->close();
?>
