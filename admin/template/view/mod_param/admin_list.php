<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_paramSuccess(response) {
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
	    window.parent.tb_remove();
	    window.location.reload();
    } else {
        return on_failure(response);
    }
}

function on_paramFailure(response) {
    document.forms["sparamform"].reset();
    
    document.getElementById("adminsinfofrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<!--  
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e('Site Parameters'); ?></div></td>
			</tr>
		</tbody>
	</table>
</div>
-->
<div class="title" style="position:relative;top:8px;color:#596b9d;margin-bottom:4px;"><?php _e('Site Parameters'); ?></div>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$sparam_form = new Form('index.php', 'sparamform', 'check_sparam_info');
$sparam_form->p_open('mod_param', 'save_param', '_ajax');
?>
<table id="sparamform_table" class="form_table" cellspacing="0">
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
	<!--
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
        </tr>-->
        <tr>
            <td class="label"><?php _e('Records per page'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[PAGE_SIZE]', PAGE_SIZE, '', 
            	$sparam_form, 'RequiredTextbox', __('Please input record number displayed on one page!'));
            ?>
            </td>
        </tr>
		<!--
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
		-->
        <tr>
        	<td class="label"><?php _e('Default LANG'); ?></td>
        	<td class="entry">
            <?php
            //get type of language
            $o_lang = new Language();
        	$langs =& $o_lang->findAll();
        	$arr = array();
        	$i = $j = 0;
        	foreach($langs as $lang)
        	{
        		/*$arr[] = $lang->name;
        		if($lang->locale == SessionHolder::get('_LOCALE'))
        		{
        			$j = $i;
        		}
        		$i++;*/
        		$arr[$lang->id] = $lang->name;
        		if($lang->locale == SessionHolder::get('_LOCALE'))
        		{
        			$j = $lang->id;
        		}
        	}
            echo Html::select('sparam[USE_LANGUAGE]', $arr, $j);
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
            <td class="label"><?php _e('copyright'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[COPYRIGHT]', COPYRIGHT, 'size=64', 
            	$sparam_form);
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$sparam_form->close();
$running_msg = __('Saving site parameters...');
$custom_js = <<<JS
$("#adminsinfofrm_stat").css({"display":"block"});
$("#adminsinfofrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_paramSuccess, on_paramFailure);
return false;

JS;
$sparam_form->addCustValidationJs($custom_js);
$sparam_form->writeValidateJs();
?>
