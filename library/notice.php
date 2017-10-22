<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * The global notice holder
 *
 * @global array $GLOBALS['_NOTICE_HOLDER']
 * @name $_NOTICE_HOLDER
 */
$GLOBALS['_NOTICE_HOLDER'] = null;

/**
 * The class for storing notices between pages
 *
 * @package parameter
 */
class Notice {
    /**
     * Set notice
     *
     * @access public
     * @static
     * @global array
     * @param string $key_path The context path for storing notice
     * @param mixed $value The notice text
     */
    public static function set($key_path, $value) {
        global $_SESSION_HOST_NAME;
        ParamParser::assign($_SESSION[$_SESSION_HOST_NAME]['_NOTICE'],
            $key_path, $value);
    }

    /**
     * Dump notice from session to notice holder
     *
     * @access public
     * @static
     * @global array
     */
    public static function dump() {
        global $_SESSION_HOST_NAME;
		if(isset($_SESSION[$_SESSION_HOST_NAME]['_NOTICE'])){
        $GLOBALS['_NOTICE_HOLDER'] =
            $_SESSION[$_SESSION_HOST_NAME]['_NOTICE'];
		}else{
			$GLOBALS['_NOTICE_HOLDER']='';
		}
        $_SESSION[$_SESSION_HOST_NAME]['_NOTICE'] = null;
    }

    /**
     * Get notice from notice holder
     *
     * @access public
     * @static
     * @global array
     * @param string $key_path The context path for retrieving notice
     * @param mixed $default The default notice as fallback
     * @return mixed
     */
    public static function &get($key_path, $default = false) {
        global $_NOTICE_HOLDER;
        $rs =& ParamParser::retrive($_NOTICE_HOLDER,
            $key_path, $default);
        return $rs;
    }
}
?>