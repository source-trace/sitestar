<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

function check_mod($module) {
	if(EZSITE_LEVEL =='1' && $module=='mod_cart'){
		return false;
	}
    if (strpos(ALLOWED_MOD, $module.',') !== false)
        return true;
    else
        return false;
}

// Verify EZSITE_LEVEL here
$_STATIC_SEED = '32cd84b73b7197ed23a33ea3c6543a0b56e78a3f03ea64e5bcc35f804fa29d18';
//$_EZ_SEED = '^MCHL$'.EZSITE_UID.$_STATIC_SEED.ALLOWED_MOD.'EZLV$';
// For compatability
$_EZ_SEED = '^MCHL$'.EZSITE_UID.$_STATIC_SEED.EZSITE_LEVEL.'EZLV$';
if(function_exists("hash")){
	$_S = hash('sha256', $_EZ_SEED);
} else {
	include_once 'hash.php';
	$_S = SHA256::hash($_EZ_SEED);
}
if (strtoupper(EZSITE_S) != strtoupper($_S)) {
    die("System version error!");
}
?>
