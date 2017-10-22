<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

//error_reporting(0);
define('DS', DIRECTORY_SEPARATOR);

define('ROOT', realpath(dirname(__FILE__).DS.'..'));
define('P_FLT', ROOT.'/filter');
define('P_INC', ROOT.'/include');
define('P_LIB', ROOT.'/library');
define('P_MDL', ROOT.'/model');
define('P_MOD', ROOT.'/module');

include_once(ROOT.'/config.php');

include_once(P_LIB.'/memorycache.php');
include_once(P_LIB.'/toolkit.php');

include_once(P_LIB.'/'.Config::$mysql_ext.'.php');
$db =& new MysqlConnection(
    Config::$db_host, 
    Config::$db_user, 
    Config::$db_pass, 
    Config::$db_name
);
if (Config::$enable_db_debug === true) {
    $db->debug = true;
}

include_once(P_INC.'/autoload.php');

define('CACHE_DIR', ROOT.'/cache');
include_once(P_LIB.'/record.php');
include_once(P_LIB.'/validator.php');

include_once(P_INC.'/db_param.php');
include_once(P_INC.'/userlevel.php');

if (intval(DB_SESSION) == 1) {
    include_once(P_LIB.'/session_db.php');
}

include_once(P_INC.'/magic_quotes.php');

define('P_TPL', ROOT.'/template/'.DEFAULT_TPL);
define('P_VIEW',ROOT.'/view');
define('P_SCP', '../../script');
define('P_TPL_WEB', '../../template/'.DEFAULT_TPL);
// Include template infomation
include_once(P_TPL.'/template_info.php');

include_once(P_LIB.'/pager.php');

include_once(P_LIB.'/rand_math.php');

include_once(P_LIB.'/param.php');
include_once(P_LIB.'/notice.php');
SessionHolder::initialize();
Notice::dump();

define('P_LOCALE', ROOT.'/locale');
//include_once(P_LIB.'/php-gettext/gettext.inc');
include_once(P_INC.'/locale.php');

include_once(P_INC.'/siteinfo.php');

include_once(P_LIB.'/acl.php');
ACL::loginGuest();

include_once(P_LIB.'/module.php');
include_once(P_LIB.'/form.php');

include_once(P_INC.'/global_filters.php');

function parse_speccode($param) {
    $return_parts = explode('>', $param);
    return explode(',', $return_parts[1]);
}

function check_history($user_id, $outer_oid, $payment_provider_id, $send_time, $finished) {
    $o_payhisto =& new OnlinepayHistory();
    $curr_histo =& $o_payhisto->find("user_id=? AND outer_oid=? AND payment_provider_id=? AND send_time=? AND finished=?", 
        array($user_id, $outer_oid, $payment_provider_id, $send_time, $finished));
    return $curr_histo;
}

function update_order($user_id, $oid, $order_amount) {
//	$order_amount = $order_amount/100;
    try {
        $o_order =& new OnlineOrder();
        $curr_order =& $o_order->find("user_id=? AND oid=?", array($user_id, $oid));
        if ($curr_order) {
            /*if ($curr_order->total_amount != $order_amount) {
                return false;
            }*/
           $curr_order->order_status = '2';
           $curr_order->save();
           return $curr_order->id;
        } else {
            return false;
        }
    } catch (Exception $ex) {
        return false;
    }
}

function save_money($user_id, $amount, $gateway_name) {
    try {
        $o_userext =& new UserExtend();
        $curr_userext =& $o_userext->find("user_id=?", array($user_id));
        if ($curr_userext) {
            $curr_userext->total_saving = floatval($curr_userext->total_saving) + floatval($amount);
            $curr_userext->balance = floatval($curr_userext->total_saving) - floatval($curr_userext->total_payment);
            $curr_userext->save();
            
            $o_transaction =& new Transaction();
            $o_transaction->action_time = time();
            $o_transaction->user_id = $user_id;
            $o_transaction->type = '1';
            $o_transaction->amount = $amount;
            $o_transaction->memo = __('Online Saving').' ('.$gateway_name.')';
            $o_transaction->save();

           return true;
        } else {
            return false;
        }
    } catch (Exception $ex) {
        return false;
    }
}

function logRegister($fileName,$msg)
{
	if(empty($fileName) || empty($msg))
	{
		return false;
	}
	else
	{
		if(file_exists($fileName))
		{
			chmod($fileName,0755);
			$handle = '';
			if(is_writable($fileName))
			{
				if ($handle = fopen($fileName, 'a'))
				{
					fwrite($handle, $msg);
				}
			}
		}
		else
		{
			file_put_contents($fileName,$msg);
		}
	}
}

$_SITE =& SessionHolder::get('_SITE');
?>
