<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Session table structure.
 *
 * CREATE TABLE `sessions` (
 *  `sess_id` varchar(32) NOT NULL,
 *  `sess_data` longtext NOT NULL,
 *  `sess_access` int unsigned NOT NULL,
 *  PRIMARY KEY (sess_id),
 *  KEY (sess_access)
 *  );
 */

/**
 * The hostname or IP of database server
 *
 * @global string $GLOBALS['_SESS_DB_HOST']
 * @name $_SESS_DB_HOST
 */
$GLOBALS['_SESS_DB_HOST'] = 'localhost';

/**
 * The user name for accessing databases
 *
 * @global string $GLOBALS['_SESS_DB_USER']
 * @name $_SESS_DB_USER
 */
$GLOBALS['_SESS_DB_USER'] = 'root';

/**
 * The password of the user
 *
 * @global string $GLOBALS['_SESS_DB_PWD']
 * @name $_SESS_DB_PWD
 */
$GLOBALS['_SESS_DB_PWD'] = '';

/**
 * The name of database you want to use
 *
 * @global string $GLOBALS['_SESS_DB_NAME']
 * @name $_SESS_DB_NAME
 */
$GLOBALS['_SESS_DB_NAME'] = 'test';

/**
 * The global database connection reference
 *
 * @global resource $GLOBALS['_SESS_DB']
 * @name $_SESS_DB
 */
$GLOBALS['_SESS_DB'] = null;

/**
 * Table name for storing sessions
 *
 * @global string $GLOBALS['_SESS_TABLE']
 * @name $_SESS_TABLE
 */
$GLOBALS['_SESS_TABLE'] = "`sessions`";

/**
 * Callback function for session open
 *
 * @return true
 */
function openSession() {
    global $_SESS_DB_HOST;
    global $_SESS_DB_USER;
    global $_SESS_DB_PWD;
    global $_SESS_DB_NAME;

    global $_SESS_DB;

    $_SESS_DB = mysql_pconnect(
                    $_SESS_DB_HOST, 
                    $_SESS_DB_USER, 
                    $_SESS_DB_PWD
                );
    mysql_select_db($_SESS_DB_NAME, $_SESS_DB);

    return true;
}

/**
 * Callback function for session close
 *
 * @return true
 */
function closeSession() {
    global $_SESS_DB;

    mysql_close($_SESS_DB);

    return true;
}

/**
 * Callback function for session read
 *
 * @param string $sess_id The session ID
 * @return mixed
 */
function readSession($sess_id) {
    global $_SESS_DB;
    global $_SESS_TABLE;

    $rs = mysql_query("SELECT * FROM $_SESS_TABLE WHERE `sess_id`='$sess_id'", 
        $_SESS_DB);
    $row = mysql_fetch_array($rs, MYSQL_ASSOC);
    if ($row) {
        return $row['sess_data'];
    } else {
        return '';
    }
}

/**
 * Callback function for session write
 *
 * @param string $sess_id The session ID
 * @param string $sess_data The session data
 * @return resource|bool
 */
function writeSession($sess_id, $sess_data) {
    global $_SESS_DB;
    global $_SESS_TABLE;

    $sess_data = mysql_real_escape_string($sess_data, $_SESS_DB);
    $now = time();
    $rs = mysql_query(
        "INSERT INTO $_SESS_TABLE VALUES ('$sess_id', '$sess_data', '$now')", 
        $_SESS_DB);
    return $rs;
}

/**
 * Callback function for session destroy
 *
 * @param string $sess_id The session ID
 * @return resource|bool
 */
function destroySession($sess_id) {
    global $_SESS_DB;
    global $_SESS_TABLE;

    $rs = mysql_query("DELETE FROM $_SESS_TABLE WHERE `sess_id`='$sess_id'", 
        $_SESS_DB);
    return $rs;
}

/**
 * Callback function for session clean
 *
 * @param int $max_life The session timeout value
 * @return resource|bool
 */
function cleanSession($max_life) {
    global $_SESS_DB;
    global $_SESS_TABLE;

    $sess_access = time() - $max_life;

    $rs = mysql_query("DELETE FROM $_SESS_TABLE WHERE `sess_access` < '$sess_access'", 
        $_SESS_DB);
    return $rs;
}

session_set_save_handler(
    'openSession', 
    'closeSession', 
    'readSession', 
    'writeSession', 
    'destroySession', 
    'cleanSession'
);
?>