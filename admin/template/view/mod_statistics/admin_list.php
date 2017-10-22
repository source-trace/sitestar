<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_Success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminsinfofrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["sparamform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('Parameters updated!'); ?>";
	    //window.parent.tb_remove();
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_Failure(response) {
    document.forms["sparamform"].reset();
    
    document.getElementById("adminsinfofrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$sparam_form = new Form('index.php', 'sparamform', 'check_sparam_info');
$sparam_form->p_open('mod_statistics', 'admin_update', '_ajax');
?>
<table id="sparamform_table" class="form_table" border="0" cellspacing="0" cellpadding="2" style="margin-left:15px;width:98%;line-height:24px;">
    <tbody>
    <tr><td colspan="2" height="20"></td></tr>
		<tr>
            <td width="15%"><span class="label"><?php _e('Site statistics code'); ?></span></td>
            <td colspan="2"><span class="entry">
            <?php
            echo Html::textarea('sparam[SITE_HAOSH]', stripslashes(SITE_HAOSH), 
                'cols="46" rows="6" class="textinput" style="width:450px;"');
            ?>
            </span></td>
        </tr>
        <tr>
        	<td rowspan="4"><span class="label"><?php _e('Statistics Provider'); ?></span></td>
        	<td width="13%"><span style="width: 10px;"><?php _e('CNZZ'); ?></span></td>
        	<td width="75%"><span style="width: 10px;"><a href="http://www.cnzz.com" target="_blank">http://www.cnzz.com</a></span></td>
        </tr>
        <tr>
        	<td><span style="width: 10px;"><?php _e('51.LA'); ?></span></td>
        	<td colspan="2"><a href="http://www.51.la" target="_blank">http://www.51.la</a></td>
        </tr>
        <tr>
        	<td><span style="width: 10px;"><?php _e('GOOGLE'); ?></span></td>
        	<td colspan="2"><a href="http://www.google.com/intl/zh-CN_ALL/analytics/" target="_blank">http://www.google.com/intl/zh-CN_ALL/analytics/</a></td>
        </tr>
        <tr>
        	<td><span style="width: 10px;"><?php _e('LINEZING'); ?></span></td>
        	<td><a href="http://tongji.baidu.com/" target="_blank">http://tongji.baidu.com/</a></td>
        </tr>
        <tr>
            <td colspan="3">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
			echo Html::input('submit', 'submit', __('Save'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$sparam_form->close();
$running_msg = __('Saving parameters...');
$custom_js = <<<JS
$("#adminsinfofrm_stat").css({"display":"block"});
$("#adminsinfofrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_Success, on_Failure);
return false;

JS;
$sparam_form->addCustValidationJs($custom_js);
$sparam_form->writeValidateJs();
?>
