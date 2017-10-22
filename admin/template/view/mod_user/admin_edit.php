<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$paramarr=array();
$userarr=$curr_user->to_hash();
if(!empty($userarr['params'])) $paramarr= json_decode($userarr['params'],true);
?>
<script src="../script/jquery.validate.js"></script>
<script src="../script/popup/datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../script/popup/theme/datepicker.css">
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
    document.getElementById("adminedtusrform_stat").style.display='block';
    var stat = document.getElementById("adminedtusrform_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        if (o_result.selfpwd) {
            parent_goto_d(o_result.forward);
        } else {
	        stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	        parent.window.location.reload();
//	        window.location.href = o_result.forward;
        }
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    
    document.getElementById("adminedtusrform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function backPrv(){
	window.location.href="index.php?_m=mod_user&_a=admin_list";	
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
	
	 $('#adminedtusrform').validate({
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
				 var param=$('#adminedtusrform').serialize();
//				 addLoadingDiv();
				if($('#user_passwd_').length){
					 var pass=$('#user_passwd_').val();
					 var repass=$('#user_re_passwd_').val();
					 if(pass !=repass ){
						 alert('<?php _e('Password Mismatch!');?>');
						 return false;
					 }
				 }
				 $.post(parseToAdminURL('mod_user','admin_update'),param ,function(o_result) {
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
<span id="adminedtusrform_stat" class="status" style="display:none;"></span>
<?php
$admin_edtusr_form = new Form('index.php', 'adminedtusrform');
$admin_edtusr_form->open('mod_user', 'admin_update', '_ajax');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form_table" style="line-height:24px;">
	<tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv();"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'user[id]', $curr_user->id);
            ?>
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
            echo Html::input('password', 'passwd[passwd]', '', 'class="textinput"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Confirm Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'passwd[re_passwd]', '', 'class="textinput"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('E-mail'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[email]', $curr_user->email, 
                'class="textinput"', $admin_edtusr_form, 'RequiredTextbox', 
                __('Please input e-mail address!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Active'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'user[active]', '1', 
				Toolkit::switchText($curr_user->active, array('0' => '', '1' => 'checked="checked"')));
            ?>
            </td>
        </tr>
	  <?php  if($issuperadmin){ ?>
        <tr>
            <td class="label"><?php _e('Role'); ?></td>
            <td class="entry">
            <?php
			if($curr_user->s_role == '{admin}') {
					echo Html::select('user[s_role]', 
									array('admin'=>__('Admin')), 
									str_replace(array('{', '}'), array('', ''), $curr_user->s_role), 'class="textselect"');
			} else{
				if(!Toolkit::isSiteStarAuthorized()){				
					 echo Html::select('user[s_role]',  array( 'member'=>__('Member')),str_replace(array('{', '}'), array('', ''), $curr_user->s_role), 'class="textselect"');
				} else {
						echo Html::select('user[s_role]', 
										Toolkit::toSelectArray($roles, 'name', 'desc', array(), true), 
										str_replace(array('{', '}'), array('', ''), $curr_user->s_role), 'class="textselect"');
					}
			}
            ?>
            </td>
        </tr>
	<?php } ?>
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
<?php
$admin_edtusr_form->close();
//if($ismyself ||$issuperadmin){
//$admin_edtusr_form->genCompareValidate('passwd[passwd]', 'passwd[re_passwd]', 
//    '=', __('Passwords mismatch!'));
//}
//$running_msg = __('Saving profile...');
//$custom_js = <<<JS
//$("#adminedtusrform_stat").css({"display":"block"});
//$("#adminedtusrform_stat").html("$running_msg");
//_ajax_submit(thisForm, on_success, on_failure);
//return false;
//
//JS;
//$admin_edtusr_form->addCustValidationJs($custom_js);
//$admin_edtusr_form->writeValidateJs();
?>
