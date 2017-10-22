<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$path_switch_form = new Form($_SERVER['REQUEST_URI'], 'pathswform');
$path_switch_form->open();
_e('Current Location');
echo ' &raquo; '.Html::select('ep', 
    array_merge(array($curr_entry.'..' => __("Level Up"), $curr_entry => $curr_entry), $dirs), 
    $curr_entry, 'onchange="document.forms[\'pathswform\'].submit();"');
$path_switch_form->close();
?>