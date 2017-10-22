<?php

if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("loginform_stat");
    if (o_result.result == "ERROR") {
        document.forms["loginform"].reset();
        reload_captcha();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
        window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["loginform"].reset();
    reload_captcha();
    
    document.getElementById("loginform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function reload_captcha() {
    var captcha = document.getElementById("login_captcha");
    if (captcha) {
        captcha.src = "captcha.php?s=" + random_str(6);
    }
}
//-->
</script>
<div class="loginblock">
<?php
$loginform = new Form('index.php', 'loginform', 'check_login_info');
$loginform->p_open('mod_auth', 'dologin', '_ajax');
?>
<h3 class="usr_blk_t"><?php _e('User Login'); ?></h3>
<table cellspacing="1" class="front_form_table" width="100%">
    <tbody>
        <tr>
            <td class="label"><?php _e('Username'); ?></td>
            <td class="entry" colspan="2"><?php echo Html::input('text', 'login_user', '', '', $loginform, 'RequiredTextbox', __('Please input your username!')); ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Password'); ?></td>
            <td class="entry" colspan="2"><?php echo Html::input('password', 'login_pwd', '', '', $loginform, 'RequiredTextbox',  __('Please input your password!')); ?></td>
        </tr>
        <?php if (SITE_LOGIN_VCODE) { ?>
        <tr>
            <td class="label"><?php _e('Security'); ?></td>
            <td><img id="login_captcha" src="captcha.php" class="captchaimg" border="0"></td>
            <td class="entry"><?php echo Html::input('text', 'rand_rs', '', 'size="2"', $loginform, 'RequiredTextbox', __('Please give me an answer!')); ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" align="center">
                <input type="button" value="<?php _e('Register user!'); ?>" onclick="show_iframe_win('index.php?<?php echo Html::xuriquery('mod_user', 'reg_form'); ?>', '<?php _e('Register'); ?>', 560, 520);return false;" />
                <?php
                echo Html::input('submit', 'bt_login', __('Login'));
                echo Html::input('hidden', '_f', $forward_url);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span id="loginform_stat" class="status" style="display: none;"></span>
            </td>
        </tr>
    </tfoot>
</table>
<?php
$loginform->close();
$running_msg = __('Checking user...');
$custom_js = <<<JS
$("#loginform_stat").css({"display":"block"});
$("#loginform_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$loginform->addCustValidationJs($custom_js);
$loginform->writeValidateJs();
?>
</div>
