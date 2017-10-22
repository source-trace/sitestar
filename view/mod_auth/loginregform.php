<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_loginreg_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("loginreg_stat");
    if (o_result.result == "ERROR") {
        document.forms["lr_loginform"].reset();
        reload_loginreg_captcha();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
        window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_loginreg_failure(response) {
    document.forms["lr_loginform"].reset();
    reload_captcha();
    
    document.getElementById("loginreg_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function reload_loginreg_captcha() {
    var captcha = document.getElementById("lr_login_captcha");
    captcha.src = "captcha.php?s=" + random_str(6);
}

function check_login_name() {
    var login_name = document.getElementById("user_login_").value;
    _ajax_request("mod_user", "chk_login_name", {login: login_name}, 
        on_chk_login_success, on_chk_login_failure)
}

function on_chk_login_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_chk_login_failure(response);
    }
    
    var chk_rs = document.getElementById("chk_login_rs");
    if (o_result.result == "OK") {
        chk_rs.innerHTML = o_result.msg;
    } else {
        chk_rs.innerHTML = o_result.errmsg;
    }
}

function on_chk_login_failure(response) {
    var chk_rs = document.getElementById("chk_login_rs");
    chk_rs.innerHTML = "<?php _e('Check failed!'); ?>";
}
//-->
</script>



<div class="art_list">
	<div class="art_list_title"><?php _e('User Login'); ?></div>
	<span id="loginreg_stat" class="status" style="display:none;"></span>
	<?php
$loginform = new Form('index.php', 'lr_loginform', 'check_loginreg_info');
$loginform->p_open('mod_auth', 'dologin', '_ajax');
?>
<div id="mess_main">
<div class="mess_list">
	<div class="mess_title"><?php _e('Username'); ?></div><div class="mess_input"><?php echo Html::input('text', 'login_user', '', '', $loginform, 'RequiredTextbox', __('Please input your username!')); ?></div>
    </div>
<div class="mess_list">
	<div class="mess_title"><?php _e('Password'); ?></div><div class="mess_input"><?php echo Html::input('password', 'login_pwd', '', '', $loginform, 'RequiredTextbox',  __('Please input your password!')); ?></div>
    </div>
	 <?php if (SITE_LOGIN_VCODE) { ?>
     <div class="mess_list">
	<div class="mess_title"><?php _e('Security'); ?></div><div class="mess_input yzm_input"><img id="lr_login_captcha" src="captcha.php" class="captchaimg" border="0"><?php echo Html::input('text', 'rand_rs_reglogn', '', 'size="2"', $loginform, 'RequiredTextbox', __('Please give me an answer!')); ?></div>
    </div>
	<?php }?>
	<div class="blankbar1"></div>
	<div class="regformsub">
                <?php
                echo Html::input('submit', 'bt_login', __('Login'));
                echo Html::input('hidden', '_f', $forward_url);
                ?></div>
</div>
</div>
<div style="height:30px; clear:both;" class="left2_bot"></div>
<?php
$loginform->close();
$running_msg = __('Checking user...');
$custom_js = <<<JS
$("#loginreg_stat").css({"display":"block"});
$("#loginreg_stat").html("$running_msg");
_ajax_submit(thisForm, on_loginreg_success, on_loginreg_failure);
return false;

JS;
$loginform->addCustValidationJs($custom_js);
$loginform->writeValidateJs();
?>


<?php
$reg_form = new Form('index.php', 'lr_regform', 'check_reglogin_info');
$reg_form->p_open('mod_user', 'do_reg', '_ajax');
?>
<div class="art_list">
	<div class="art_list_title"><?php _e('First visit? Register user to continue!'); ?></div>
<div id="mess_main">
<div class="mess_list">
	<div class="mess_title"><?php _e('Login Name'); ?></div><div class="mess_input"><?php
            echo Html::input('text', 'user[login]', '', 
                '', $reg_form, 'RequiredTextbox', 
                __('Please input login name!'));
            ?>
            <a href="#" onclick="check_login_name();return false;"><?php _e('Check'); ?></a>
            <span id="chk_login_rs"></span></div>
            </div>
   <div class="mess_list">
	<div class="mess_title"><?php _e('Password'); ?></div><div class="mess_input"><?php
            echo Html::input('password', 'user[passwd]', '', 
                '', $reg_form, 'RequiredTextbox', 
                __('Please input your password!'));
            ?></div>
     </div>  
      <div class="mess_list">          
	<div class="mess_title"><?php _e('Confirm Password'); ?></div><div class="mess_input"><?php
            echo Html::input('password', 'user[re_passwd]', '', 
                '', $reg_form, 'RequiredTextbox', 
                __('Please retype your password for confirmation!'));
            ?></div>
      </div>
     <div class="mess_list">        
	<div class="mess_title"><?php _e('E-mail'); ?></div><div class="mess_input"><?php
            echo Html::input('text', 'user[email]', '', 
                '', $reg_form, 'RequiredTextbox', 
                __('Please input e-mail address!'));
            ?></div>
     </div>
<!--      <div class="mess_list">        
	<div class="mess_title"><?php _e('Name'); ?></div><div class="mess_input"><?php
            echo Html::input('text', 'user[full_name]');
            ?></div>
	</div>
          <div class="mess_list">        
	<div class="mess_title"><?php _e('Telephone'); ?></div><div class="mess_input"><?php
            echo Html::input('text', 'user[mobile]');
            ?></div>
	</div>-->
   <?php
foreach($user_fields as $fieldinfo){ 
?>
<div class="mess_list"> 
		 <div class="mess_title"><?php echo UserField::getUserDefineLabel($fieldinfo); ?></div>
           <div class="mess_input"> <?php echo UserField::getUserCustomComponent($fieldinfo,array(),$userparams);?>
            </div>
     </div>
<?php }?> 
	<div class="blankbar1"></div>
           <div class="regformsub"> <?php
            echo Html::input('submit', 'submit', __('Register'));
            echo Html::input('hidden', '_f', $forward_url);
            ?>
			</div>
</div>
</div>
<?php
$reg_form->close();
$reg_form->genCompareValidate('user[passwd]', 'user[re_passwd]', 
    '=', __('Passwords mismatch!'));
$running_msg = __('Registring user...');
$custom_js = <<<JS
$("#loginreg_stat").css({"display":"block"});
$("#loginreg_stat").html("$running_msg");
_ajax_submit(thisForm, on_loginreg_success, on_loginreg_failure);
return false;

JS;
$reg_form->addCustValidationJs($custom_js);
$reg_form->writeValidateJs();
?>