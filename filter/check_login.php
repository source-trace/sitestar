<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

class CheckLogin {
    public function execute() {
        if (!ACL::checkLogin()) {
            if (R_TPE == '_page') {
                // Now use javascript to redirect to the login page
                if (R_MOD == 'mod_order' && R_ACT == 'ordernow') {
                    /**
                     * for bugfree 350 14:38 2010-7-23 edit start
                     */
//                    $login_url = Html::uriquery('mod_auth', 'loginregform', 
//                            array('_f' => 'index.php?'.$_SERVER['QUERY_STRING']));
                    
                    SessionHolder::set('goto', '');
                    if (MOD_REWRITE == 2) {
                    	SessionHolder::set('goto', 'mod_order-ordernow.html');
                    	$login_url =  Html::uriquery('mod_auth', 'loginregform');
                    } else {
                    	SessionHolder::set('goto', '');
                    	$login_url = Html::uriquery('mod_auth', 'loginregform', 
                            			array('_f' => 'index.php?'.$_SERVER['QUERY_STRING']));                    	
                    }
                    /**
                     * for bugfree 350 14:38 2010-7-23 edit end
                     */
                } else {
                    $login_url = Html::uriquery('mod_auth', 'loginform', 
                            array('_f' => 'index.php?'.$_SERVER['QUERY_STRING']));
                }
echo <<< LOGIN
<script type="text/javascript" language="javascript">
<!--
    window.top.location.href = "$login_url";
//-->
</script>
LOGIN;
            }
            return false;
        } else {
            return true;
        }
    }
}
?>
