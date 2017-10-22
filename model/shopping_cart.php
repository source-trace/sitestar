<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ShoppingCart {
    public static function addProduct($p_id, $p_num = 1) {
    	$user_id = SessionHolder::get('user/id','0');
        setcookie('prds'.$user_id.'['.$p_id.']', $p_id, time()+3600*24*30, '/');
        if (!isset($_COOKIE['n_prds'.$user_id])) {
            setcookie('n_prds'.$user_id, $p_num, time()+3600*24*30, '/');
            setcookie('n_prd'.$user_id.'['.$p_id.']', $p_num, time()+3600*24*30, '/');
            setcookie('n_prd2'.$user_id.'['.$p_id.']', Toolkit::cencode($p_num), time()+3600*24*30, '/');
            return $p_num;
        } else {
            setcookie('n_prds'.$user_id, intval($_COOKIE['n_prds'.$user_id]) + $p_num, time()+3600*24*30, '/');
            if (!isset($_COOKIE['prds'.$user_id][$p_id])) {
                setcookie('n_prd'.$user_id.'['.$p_id.']', $p_num, time()+3600*24*30, '/');
                setcookie('n_prd2'.$user_id.'['.$p_id.']', Toolkit::cencode($p_num), time()+3600*24*30, '/');
            } else {
//                setcookie('n_prd['.$p_id.']', intval($_COOKIE['prds'][$p_id]) + $p_num, time()+3600*24*30, '/');
				setcookie('n_prd'.$user_id.'['.$p_id.']', intval($_COOKIE['n_prd'.$user_id][$p_id]) + $p_num, time()+3600*24*30, '/');
				setcookie('n_prd2'.$user_id.'['.$p_id.']', Toolkit::cencode(intval($_COOKIE['n_prd'.$user_id][$p_id]) + $p_num), time()+3600*24*30, '/');
            }
           
            return intval($_COOKIE['n_prds'.$user_id]) + $p_num;
        }
    }
    
    public static function updateProductNum($p_id, $num) {
    	$user_id = SessionHolder::get('user/id','0');
        if (intval($num) <= 0) {
            return self::removeProduct($p_id);
        } else {
            if (isset($_COOKIE['prds'.$user_id][$p_id])) {
                setcookie('n_prd'.$user_id.'['.$p_id.']', $num, time()+3600*24*30, '/');
                setcookie('n_prd2'.$user_id.'['.$p_id.']', Toolkit::cencode($num), time()+3600*24*30, '/');
                setcookie('n_prds'.$user_id, intval($_COOKIE['n_prds'.$user_id]) - intval($_COOKIE['n_prd'.$user_id][$p_id]) + $num, time()+3600*24*30, '/');
                return intval($_COOKIE['n_prds'.$user_id]) - intval($_COOKIE['n_prd'.$user_id][$p_id]) + $num;
            } else {
                return intval($_COOKIE['n_prds'.$user_id]);
            }
        }
    }
    
    public static function discardProductNum() {
    	$user_id = SessionHolder::get('user/id','0');
        setcookie('n_prds'.$user_id, '', time()-3600*24*30, '/');
    }
    
    public static function removeProduct($p_id) {
    	$user_id = SessionHolder::get('user/id','0');
        if (isset($_COOKIE['prds'.$user_id][$p_id])) {
            setcookie('prds'.$user_id.'['.$p_id.']', '', time()-3600*24*30, '/');
            setcookie('n_prd'.$user_id.'['.$p_id.']', '', time()-3600*24*30, '/');
            setcookie('n_prds'.$user_id, intval($_COOKIE['n_prds'.$user_id]) - intval($_COOKIE['n_prd'.$user_id][$p_id]), time()+3600*24*30, '/');
            return intval($_COOKIE['n_prds'.$user_id]) - intval($_COOKIE['n_prd'.$user_id][$p_id]);
        } else {
            return intval($_COOKIE['n_prds'.$user_id]);
        }
    }
}
?>
