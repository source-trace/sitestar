<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$url = $_SERVER['QUERY_STRING'];
$arr = explode('&', $url);
$newurl='';
if(sizeof($arr) > 1) {
    foreach($arr as $v) {
        $array = explode('=', $v);
        if($array[0] != '_l') {
            $newurl .=$v.'&';
        }
    }
}
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$newurl;
foreach($langs as $lang){
    echo "<a href=$url"."_l=".$lang->locale.">".$lang->name."</a>&nbsp;";
}
?>