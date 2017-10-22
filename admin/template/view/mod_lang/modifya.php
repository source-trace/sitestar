<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$local_list = array(__('Please select language'),'en'=>__('English'),'zh_CN'=>__('Simplified Chinese'),'ja_JP'=>__('Japanese'),
                    'ko_KR'=>__('Korean'),'fr_CH'=>__('French'),'de_CH'=>__('German'),'it_IT'=>__('Italian'),'es_ES'=>__('Spanish'),
					'tr_TR'=>__('Turkish'),'pt_PT'=>__('Portuguese'),'nl_NL'=>__('Dutch'),'other'=>__('Other'));
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
	    alert("<?php _e('Operate successful!');?>");
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
$admin_addlang_form->p_open('mod_lang', 'file_savea', '_ajax');
?>
<table id="adminaddlangform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td >
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
           
            <td align="center" > <?php
			  echo Html::input('hidden', 'filename', $filename, 
                'class="textinput" style="width:0px;" autocomplete="off" ');		
            ?>
            <?php
			$filename = "locale/".$filename."/lang.php";
		$defaultlanguage = file_get_contents($filename);
            echo Html::textarea('filecontent',$defaultlanguage,' rows="10" cols="72" ');
            ?>
          </td>
        </tr>
    </tbody>
</table>
<?php
$admin_addlang_form->close();

$custom_js = <<<JS

_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$admin_addlang_form->addCustValidationJs($custom_js);
$admin_addlang_form->writeValidateJs();
?>