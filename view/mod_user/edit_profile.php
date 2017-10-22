<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$paramarr=array();
$userarr=$curr_user->to_hash();
if(!empty($userarr['params'])) $paramarr= json_decode($userarr['params'],true);
?>
<style>
	#regform_table .fieldtype3,#regform_table .fieldtype4{width:auto;}
	#regform_table .fieldtype2{width:200px;}
	#regform_table label.error{color:red;}
	#regform_table span.required {color:red;}
</style>
<script src="script/jquery.validate.js"></script>
<script src="script/popup/datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="script/popup/theme/datepicker.css">
<script>
	window._addDatePicker=function(dom){
		dom.jdPicker({
			start_of_week:0,
			date_format: "YYYY-mm-dd"
		});
	 }
</script>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("edtprofform_stat");
    if (o_result.result == "ERROR") {
        document.forms["edtprofform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        if (o_result.forward) {
            parent_goto_d(o_result.forward);
        } else {
            stat.innerHTML = "<?php _e('OK! Profile saved!'); ?>";
        }
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["edtprofform"].reset();
    
    document.getElementById("edtprofform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}


$(function(){
	function parseToAdminURL(module,action,anotherparams){
		var defaultparams={'_m':module,'_a':action}
		var urlparams=$.extend({}, anotherparams, defaultparams);
		var paramstr=$.param(urlparams);
		return "index.php?"+paramstr;
	}

	jQuery.extend(jQuery.validator.messages, { 
			required: "<?php _e('The field cannot be empty!'); ?>",
			date: '<?php _e('Invalid Input!');?>'
	 });
	
	 $('#edtprofform').validate({
			rules: { 
				'user[email]':{required: true,email:true} 
			},
			messages: { 
				'user[email]':{
					required: '<?php _e('Please input e-mail address!');?>',
					email: '<?php _e('Invalid Input!');?>'
				}
			},
			errorPlacement: function(error, element) { 
				error.appendTo(element.parent()); 
			} ,
			submitHandler: function() {
				 var param=$('#edtprofform').serialize();
//				 addLoadingDiv();
				if($('#user_passwd_').length){
					 var pass=$('#user_passwd_').val();
					 var repass=$('#user_re_passwd_').val();
					 if(pass !=repass ){
						 alert('<?php _e('Password Mismatch!');?>');
						 return false;
					 }
				 }
				 $("#edtprofform_stat").css({"display":"block"});
				$("#edtprofform_stat").html("<?php  _e('Saving profile...') ;?>");
				 $.post(parseToAdminURL('mod_user','save_profile'),param ,function(o_result) {
						on_success(o_result);
						
				}).error(function() { 
				 //   $('#wp-ajaxsend_loading2').remove();
					on_failure();
				});
				return false;
			}
		})
})
//-->
</script>

<?php
$edtprof_form = new Form('index.php', 'edtprofform');
$edtprof_form->open('mod_user', 'save_profile', '_ajax');
?>
<input type="hidden" name="_r" id="_r" value="_ajax">
<div class="art_list">
	<div class="art_list_title"><?php _e('Edit Profile');?></div>
</div>
<div id="regform_table">
<table cellspacing="1" class="regform_table_s" align="center" width="100%">
    <tfoot>
        <tr>
            <td colspan="2" align="center" class="td_sub">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            ?>
            <span id="edtprofform_stat" class="status" style="display:none;"></span>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Login Name'); ?></td>
            <td class="entry"><?php echo $curr_user->login; ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'passwd[passwd]');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Confirm Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'passwd[re_passwd]');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('E-mail'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[email]', $curr_user->email, 
                '', $edtprof_form, 'RequiredTextbox', 
                __('Please input e-mail address!'));
            ?>
            </td>
        </tr>
 	<?php
foreach($user_fields as $fieldinfo){ 
?>
		 <tr>
			 <td class="label"><?php echo UserField::getUserDefineLabel($fieldinfo); ?> <?php if($fieldinfo['required']==1){ ?><span class="required">*</span><?php } ?></td>
            <td class="entry">
            <?php echo UserField::getUserCustomComponent($fieldinfo,$paramarr,$userarr);?>
            </td>
        </tr>
<?php }?>      
    </tbody>
</table>
</div>
<?php
$edtprof_form->close();
//$edtprof_form->genCompareValidate('passwd[passwd]', 'passwd[re_passwd]', 
//    '=', __('Passwords mismatch!'));
//$running_msg = __('Saving profile...');
//$custom_js = <<<JS
//$("#edtprofform_stat").css({"display":"block"});
//$("#edtprofform_stat").html("$running_msg");
//_ajax_submit(thisForm, on_success, on_failure);
//return false;
//
//JS;
//$edtprof_form->addCustValidationJs($custom_js);
//$edtprof_form->writeValidateJs();
?>
