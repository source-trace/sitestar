<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * The data validation functions
 * for working with RecordObject
 * 
 * @package tool
 */
// TODO : to be enriched
class DataValidator {
    /**
     * Check the value whether it's in a valid number format
     *
     * @access public
     * @static
     * @param string $var The value to be checked
     * @return bool
     */
    public static function isNumeric($var) {
        return is_numeric($var);
    }

    /**
     * Check the string whether it's empty
     * It will strip HTML tags and entities automatically
     *
     * @access public
     * @static
     * @param string $var The string to be checked
     * @return bool
     */
    public static function isEmpty($var) {
        $var = strip_tags($var);
        $var = str_replace('&nbsp;', '', $var);
        return self::customMatch('/^\s*$/', $var);
    }

    /**
     * Check the value whether it's in a valid email format
     *
     * @access public
     * @static
     * @param string $var The value to be checked
     * @return bool
     */
    public static function isEmail($var) {
		//qq登陆下email字段可以为空	
	   if(!isset($var) || $var=='') return true;
        $email_parts = explode('@', $var);
        if (sizeof($email_parts) != 2) {
            return false;
        }
        
        /* check the name part */
        if (preg_match('/^\..*$/', trim($email_parts[0])) || 
            preg_match('/^.*\.$/', trim($email_parts[0])) ||
            preg_match('/^\..*\.$/', trim($email_parts[0]))) {
            return false;
        }
        if (!preg_match('/^[0-9a-zA-Z\!#\$%\*\/\?\|\^\{\}`~&\'\+\-=_\.]+$/', trim($email_parts[0]))) {
            return false;
        }
        
        /* check the domain part */
        if (preg_match('/\.\./', trim($email_parts[1]))) {
            return false;
        }
        $domain_parts = explode('.', $email_parts[1]);
        if (sizeof($domain_parts) < 2) {
            return false;
        }
        foreach ($domain_parts as $s) {
            $s = trim($s);
            if (preg_match('/^\-.*$/', $s) || 
                preg_match('/^.*\-$/', $s) ||
                preg_match('/^\-.*\-$/', $s)) {
                return false;
            }
            if (!preg_match('/^[0-9a-zA-Z\-]+$/', $s)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check the string according to the given pattern
     *
     * @access public
     * @static
     * @param string $regexp The pattern you want to test on the input string
     * @param string $var The input string
     * @return bool
     */
    public static function customMatch($regexp, $var) {
        return preg_match($regexp, $var);
    }
}
?>