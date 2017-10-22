<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
if(empty($userparams)) $userparams=array();
?>
<script src="script/jquery.validate.js"></script>
<script src="script/popup/datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="script/popup/theme/datepicker.css"/>
<style>
	#regform_table .fieldtype3,#regform_table .fieldtype4{width:auto;}
	#regform_table .fieldtype2{width:200px;}
	#regform_table label.error{color:red;}
	#regform_table span.required {color:red;}
</style>
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
    
    var stat = document.getElementById("regform_stat");
    if (o_result.result == "ERROR") {
        //document.forms["regform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
		if(o_result.verify=="1"){
			stat.innerHTML = "<?php _e('verifying...'); ?>";
			alert("<?php _e('verifying...'); ?>");
			window.location.href = o_result.forward;
		}else{
			stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
			// for bugfree#398
			window.location.href = o_result.forward;
			//reloadParent();
		}
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["regform"].reset();
    
    document.getElementById("regform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
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
	
	 $('#regform').validate({
			rules: { 
				'user[passwd]':{required: true,minlength: 5} ,	
				'user[re_passwd]':{required: true,minlength: 5} ,
				'user[email]':{required: true,email:true} ,
				'user[login]':{required: true} 
			},
			messages: { 
				'user[email]':{
					required: '<?php _e('Please input e-mail address!');?>',
					email: '<?php _e('Invalid Input!');?>'
				},
				'user[passwd]':{required:'<?php  echo __('Please input password!');?>',minlength:'<?php _e('Invalid Password!') ?>'},
				'user[re_passwd]':{required:'<?php  echo  __('Please retype your password for confirmation!');?>',minlength:'<?php _e('Invalid Password!') ?>'},
				'user[login]':{required:'<?php  echo   __('Please input login name!');?>'}
			},
			errorPlacement: function(error, element) { 
				error.appendTo(element.parent()); 
			} ,
			submitHandler: function() {
				 var param=$('#regform').serialize();
//				 addLoadingDiv();
				 var pass=$('#user_passwd_').val();
				 var repass=$('#user_re_passwd_').val();
				 if(pass !=repass ){
					 alert('<?php _e('Password Mismatch!');?>');
					 return false;
				 }
				 $("#regform_stat").css({"display":"block"});
				$("#regform_stat").html("<?php _e('Registring user...');?>");
				 $.post(parseToAdminURL('mod_user','do_reg'),param ,function(o_result) {
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
$reg_form = new Form('index.php', 'regform');
$reg_form->open('mod_user', 'do_reg', '_ajax');
?>
<?php if(!empty($auth_type)){ ?>
<input type="hidden" name="auth_type" value="<?php  echo $auth_type;?>">
<?php } ?>
<input type="hidden" name="_r" id="_r" value="_ajax">
<div class="art_list">
	<div class="art_list_title"><?php echo $mod_title;?></div>
</div>
<div id="regform_table">
<table width="100%"  align="center" cellspacing="1" class="regform_table_s" id="">
    <tfoot>
        <tr>
            <td colspan="2" align="center" class="td_sub">
            <?php
            echo Html::input('submit', 'submit', $regtitle);
            ?>
		  <?php if(!empty($auth_type)){ ?>&nbsp;<a href="index.php?_m=mod_user&_a=auth_adduser&auth_type=<?php  echo $auth_type;?>"><?php _e('Do it later'); ?></a> <?php } ?>
            <span id="regform_stat" class="status" style="display:none;"></span>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Login Name'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[login]',$userparams['login'], 
                '', $reg_form, 'RequiredTextbox', 
                __('Please input login name!'));
            ?>
            <a href="#" onclick="check_login_name();return false;"><?php _e('Check'); ?></a>
            <span id="chk_login_rs"></span>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'user[passwd]', '', 
                '', $reg_form, 'RequiredTextbox', 
                __('Please input password!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Confirm Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'user[re_passwd]', '', 
                '', $reg_form, 'RequiredTextbox', 
                __('Please retype your password for confirmation!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('E-mail'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[email]',$userparams['email'], 
                '', $reg_form, 'RequiredTextbox', 
                __('Please input e-mail address!'));
            ?>
            </td>
        </tr>
<?php
foreach($user_fields as $fieldinfo){ 
?>
		 <tr>
		   <td class="label"><?php echo UserField::getUserDefineLabel($fieldinfo); ?></td>
            <td class="entry">
            <?php echo UserField::getUserCustomComponent($fieldinfo,array(),$userparams);?>
            </td>
        </tr>
<?php }?>
        
    </tbody>
</table>
<table width="598" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="332" valign="top" class="reg_bg">&nbsp;</td>
  </tr>
</table>
</div>
<?php
$reg_form->close();
//$reg_form->genCompareValidate('user[passwd]', 'user[re_passwd]', 
//    '=', __('Passwords mismatch!'));
//$running_msg = __('Registring user...');
//$custom_js = <<<JS
//$("#regform_stat").css({"display":"block"});
//$("#regform_stat").html("$running_msg");
//_ajax_submit(thisForm, on_success, on_failure);
//return false;
//
//JS;
//$reg_form->addCustValidationJs($custom_js);
//$reg_form->writeValidateJs();
?>