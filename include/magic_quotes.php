<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

function &stripslashes_deep($var) {
    $var = is_array($var)?
        array_map('stripslashes_deep', $var):
        stripslashes($var);
    return $var;
}

function &addslashes_deep($var) {
    $var = is_array($var)?
        array_map('addslashes_deep', $var):
        addslashes($var);
    return $var;
}

if (intval(MAGIC_QUOTES_GPC_ON) == 1 && !get_magic_quotes_gpc()) {
    if (isset($_GET) && !empty($_GET)) {
        $_GET =& addslashes_deep($_GET);
    }
    if (isset($_POST) && !empty($_POST)) {
        $_POST =& addslashes_deep($_POST);
    }
    if (isset($_COOKIE) && !empty($_COOKIE)) {
        $_COOKIE =& addslashes_deep($_COOKIE);
    }
}

if (intval(MAGIC_QUOTES_GPC_ON) != 1 && get_magic_quotes_gpc()) {
    if (isset($_GET) && !empty($_GET)) {
        $_GET =& stripslashes_deep($_GET);
    }
    if (isset($_POST) && !empty($_POST)) {
        $_POST =& stripslashes_deep($_POST);
    }
    if (isset($_COOKIE) && !empty($_COOKIE)) {
        $_COOKIE =& stripslashes_deep($_COOKIE);
    }
}
?>