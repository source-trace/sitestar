<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$local_list = array(__('Please select language'),'en'=>'English','zh_CN'=>'简体中文','zh_TW'=>'繁體中文','ja_JP'=>'日本語','ja_JP'=>'にほんご','ko_KR'=>'한국어','fr_CH'=>'français','other'=>__('Other'));
?>
<!--div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e('Upload Language File'); ?></div></td>
				<td><a href="<?php echo Html::uriquery('mod_lang', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="space"></div-->
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminartfrm_stat");
    if (o_result.result == "ERROR") {      
        //stat.innerHTML = o_result.errmsg;
        if ($('#local_lang option:selected').val() == 'other') {
        	$('#lang_name_').val('');
	        $('#lang_locale_').val('');
	        $('#lang_name_').focus();
        } else {
        	document.forms["adminaddlangform"].reset();
        }
        alert(o_result.errmsg);
        //reloadPage();
        return false;
    } else if (o_result.result == "OK") {
	    //stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
		/*
	    alert("<?php _e('Add language succeeded!');_e('Please compile the following language file:');?>\n\"locale/"+$('#lang_locale_').val()+"/lang.php\"\n\"admin/locale/"+$('#lang_locale_').val()+"/lang.php\"");
		*/
	    window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["adminaddlangform"].reset();
    
    document.getElementById("adminartfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function set_default(local) {
	$('#lang_name_').css('display','none');
	if (local == 'other') {
		$('#lang_name_').css('display','inline-block');
		$('#lang_name_').val('').focus();
		$('#lang_locale_').val('');
	} else if (local == '0') {
		$('#lang_name_').val('');
		$('#lang_locale_').val('');
	} else {
		$('#lang_name_').val($('#local_lang option:selected').text());
		$('#lang_locale_').val(local);
	}
}

function frmCheck() {
	var opt = $('#local_lang').val();
	if (opt == '0') {
		alert("<?php _e('Please select language');?>");
		return false;
	} else return true;
}
//-->
</script>
<div class="status_bar">
	<span id="adminartfrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$admin_addlang_form = new Form('index.php', 'adminaddlangform', 'check_lang_info');
//$admin_addlang_form->setEncType('multipart/form-data');
$admin_addlang_form->p_open('mod_lang', 'admin_create', '_ajax');
?>
<table id="adminaddlangform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.location.href=\''.Html::uriquery('mod_lang', 'admin_list').'\'"');
            echo Html::input('reset', 'reseted', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'), 'onclick="return frmCheck();"');
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Language Name'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('local_lang', $local_list, '', 'style="line-height:18px;" onchange="set_default(this.value)"');
            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.Html::input('text', 'lang[name]', '', 
                'class="textinput" style="width:100px;display:none;"', $admin_addlang_form, 'RequiredTextbox', 
                __('Please input language name!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Locale Code'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'lang[locale]', '', 
                'class="textinput" style="width:150px;" autocomplete="off" onkeyup="value=value.replace(/[^\w]/g,\'\')"', $admin_addlang_form, 'RequiredTextbox', 
                __('Please input locale code!'));
            ?>
            &nbsp;
            <img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Identification of a region encoding');?>" />
            </td>
        </tr><!--
		  <tr>
            <td class="label"><?php _e('Locale copy'); ?></td>
            <td class="entry">
			<select name="lang[copy]" id="local_copy" style="line-height:18px;" >
			<?php

		$defaultlanguage =DEFAULT_LOCALE;
		if (sizeof($langs) > 0) {
        $row_idx = 0;
        foreach ($langs as $lang) {
		?>
			<option value="<?php echo $lang->locale; ?>" <?php if($defaultlanguage==$lang->locale){echo ' selected';}?>><?php echo $lang->name; ?></option>
		    <?php
            $row_idx = 1 - $row_idx;
        }
		}
    ?>	
			
			
			</select>
			</td>
        </tr>-->
    </tbody>
</table>
<?php
$admin_addlang_form->close();
//$running_msg = __('Saving language...');
$custom_js = <<<JS
//$("#adminartfrm_stat").css({"display":"block"});
//$("#adminartfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$admin_addlang_form->addCustValidationJs($custom_js);
$admin_addlang_form->writeValidateJs();
?>