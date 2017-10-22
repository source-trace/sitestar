<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$o_param = new Parameter();
$arr_params =& $o_param->findAll();
$curr_locale = trim(SessionHolder::get('_LOCALE'));
if (sizeof($arr_params) > 0) {
    foreach ($arr_params as $param) {
//货币常量处理
	    if ($param->key=="CURRENCY") {
	    	if (strstr($param->val,"|")) {
	    		$c_arr = explode("|",$param->val);
		    	if (is_array($c_arr)) {
		    		foreach ($c_arr as $arr){
		    			list($loc,$curr) = explode(",",$arr);
		    			if ($loc==$curr_locale) {
		    				define(trim("$param->key"), "$curr");
		    				continue;
		    			}
		    		}
		    	}
	    	}else{
	    		if (strstr($param->val,",")) {
	    			list($loc,$curr) = explode(",",$param->val);
		    		//如果只定义了一种货币，则就显示这种货币
		    		define(trim("$param->key"), "$curr");
		    		continue;
	    		}else{
	    			define(trim("$param->key"), "$param->val");
	    		}
	    		
	    	}
	   	}
	   	if ($param->key=="CURRENCY_SIGN") {
	   		if (strstr($param->val,"|")) {
	   			$c_arr = explode("|",$param->val);
		    	if (is_array($c_arr)) {
		    		foreach ($c_arr as $arr){
		    			list($loc,$curr) = explode(",",$arr);
		    			if ($loc==$curr_locale) {
		    				define(trim("$param->key"), "$curr");
		    				continue;
		    			}
		    		}
		    	}
	   		}else{
	   			if (strstr($param->val,",")) {
	    			list($loc,$curr) = explode(",",$param->val);
		    		//如果只定义了一种货币符号，则就显示这种货币符号
		    		define(trim("$param->key"), "$curr");
		    		continue;
	    		}else{
	    			define(trim("$param->key"), "$param->val");
	    		}
	    		define(trim("$param->key"), "$param->val");
	    	}
	   	}
    }
}

?>