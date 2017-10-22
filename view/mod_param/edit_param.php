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
    
    var stat = document.getElementById("adminsparamfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["sparamform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('Parameters updated!'); ?>";
	    window.parent.tb_remove();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["sparamform"].reset();
    
    document.getElementById("adminsparamfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="content_title">
	<h3><?php _e('Site Parameters'); ?></h3>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="adminsparamfrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$sparam_form = new Form('index.php', 'sparamform', 'check_sparam_info');
$sparam_form->p_open('mod_param', 'save_param', '_ajax');
?>
<table id="sparamform_table" class="form_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Sending Mail'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('sparam[USE_SMTP]', 
				array('0' => 'PHP mail()', '1' => 'SMTP'), USE_SMTP);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('SMTP Server'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_SERVER]', SMTP_SERVER);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('SMTP User'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_USER]', SMTP_USER);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('SMTP Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_PASS]', SMTP_PASS);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Records per page'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[PAGE_SIZE]', PAGE_SIZE, '', 
            	$sparam_form, 'RequiredTextbox', __('Please input record number displayed on one page!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Auto detect LANG'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[AUTO_LOCALE]', '1', 
                Toolkit::switchText(AUTO_LOCALE, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Site Counter'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[SITE_COUNTER]', '1', 
                Toolkit::switchText(SITE_COUNTER, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Login Security Code'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[SITE_LOGIN_VCODE]', '1', 
                Toolkit::switchText(SITE_LOGIN_VCODE, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Site offline'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[SITE_OFFLINE]', '1', 
                Toolkit::switchText(SITE_OFFLINE, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Site offline msg'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('sparam[SITE_OFFLINE_MSG]', SITE_OFFLINE_MSG, 
                'cols="48" rows="6"');
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Site statistics code'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('sparam[SITE_HAOSH]', stripslashes(SITE_HAOSH), 
                'cols="46" rows="6"');
            ?>
			<br /><a href="http://www.hao.sh" target="_blank"><?php _e('Signup a free site statistics account'); ?></a>
            </td>
        </tr>
    </tbody>
</table>
<?php
$sparam_form->close();
$running_msg = __('Saving site parameters...');
$custom_js = <<<JS
$("#adminsparamfrm_stat").css({"display":"block"});
$("#adminsparamfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$sparam_form->addCustValidationJs($custom_js);
$sparam_form->writeValidateJs();
?>
