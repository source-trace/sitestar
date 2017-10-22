<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Class that generates a random math equation
 * as a security seed in an HTML form.
 * 
 * @package captcha
 */
class RandMath {
    /**
     * Generate random math equation
     *
     * @access public
     * @static
     * @global string
     * @return string
     */
    public static function genRandExpr() {
        global $_SESSION_HOST_NAME;
        
        $first_opnum = rand(1, 20);
        $second_opnum = rand(1, 20);
        //$operand = rand(1, 3);
        $operand = rand(1, 2);
        
        $rand_math_result = 0;
        switch ($operand) {
            case 1:
                $rand_math_result = $first_opnum + $second_opnum;
                break;
            case 2:
                $rand_math_result = $first_opnum - $second_opnum;
                break;
            case 3:
                $rand_math_result = $first_opnum * $second_opnum;
                break;
        }
        
        ParamParser::assign($_SESSION[$_SESSION_HOST_NAME],
            '_RAND_MATH_RESULT', $rand_math_result);
                
        $rand_math_expr = '';
        switch ($operand) {
            case 1:
                $rand_math_expr = $first_opnum.' + '.$second_opnum.' =';
                break;
            case 2:
                $rand_math_expr = $first_opnum.' - '.$second_opnum.' =';
                break;
            case 3:
                $rand_math_expr = $first_opnum.' * '.$second_opnum.' =';
                break;
        }
        
        return $rand_math_expr;
    }
    
    /**
     * Check user input result
     *
     * @access public
     * @static
     * @global string
     * @param string $value The result user input
     * @return bool
     */
    public static function checkResult($value) {
        global $_SESSION_HOST_NAME;
        
        $rand_math_result =& ParamParser::retrive($_SESSION[$_SESSION_HOST_NAME],
            '_RAND_MATH_RESULT');
        
        if (intval($value) !== intval($rand_math_result)) {
            $_SESSION[$_SESSION_HOST_NAME]['_RAND_MATH_RESULT'] = null;
            return false;
        }
        $_SESSION[$_SESSION_HOST_NAME]['_RAND_MATH_RESULT'] = null;
        return true;
    }
}
?>