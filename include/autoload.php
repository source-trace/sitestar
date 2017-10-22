<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

function __autoload($class_name) {
	if (!class_exists($class_name)) {
	    $flat_class_name = Toolkit::transformClassName($class_name);
	    if (file_exists(P_MDL.'/'.$flat_class_name.'.php')) {
	        include_once(P_MDL.'/'.$flat_class_name.'.php');
	    } else if (file_exists(P_MOD.'/'.$flat_class_name.'.php')) {
	        include_once(P_MOD.'/'.$flat_class_name.'.php');
	    } else if($flat_class_name == 'session_holder') {
	    	include_once(P_LIB.'/param.php');
	    } else {
	        throw new Exception('Class not found!'."\n");
	    }
	    
	}
}
?>