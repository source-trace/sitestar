<?php
if(!defined('IN_CONTEXT')) die('access violation error!');
$checked1 = $checked2 = '';
if(isset($atype) && ($atype == 'yes')) $checked2 = 'checked';
else $checked1 = 'checked';
?>
<style type="text/css">
#wp-bshare_checklogn {margin:8px;overflow:hidden;}
#wp-bshare_checklogn td.title {color:#D00;}
#wp-bshare_checklogn td.ltitle {text-align:right;font-weight:bold;width:80px;}
#wp-bshare_checklogn td.rcontent {padding-left:5px;}
#wp-bshare_checklogn td input.bshareput {) repeat-x scroll 0 0 #FFFFFF;border:1px solid #B5B5B5;color:#444;height:22px;line-height:22px;-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;width:180px;}
#wp-bshare_checklogn td input.sendput {color:#FFF;) repeat scroll 0 0 #EFF7D0;border:none;cursor:pointer;height:22px;line-height:22px;padding:0 10px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;-moz-box-shadow:0 1px 1px 0 rgba(0,0,0,.4);-webkit-box-shadow:0 1px 1px 0 rgba(0,0,0,.4);box-shadow:0 1px 1px 0 rgba(0,0,0,.4);}
</style>
<div id="wp-bshare_checklogn"><form name="bshare_chkform" method="post" action="index.php?_m=mod_bshare&_a=create_bshare" onSubmit="return checkForm(this);">
  <table name="bshare_logntbl" cellpadding="3" cellspacing="1" border="0">
	<tr>
	  <td colspan="2" class="title"><?php _e('To open bShare services');?>:</td>
	</tr>
	<tr>
	  <td class="ltitle"><?php _e('Your domain');?>: </td>
	  <td class="rcontent"><input type="text" class="bshareput" name="bshare_domain" value="<?php echo $domain;?>" />(<?php _e('Your domain must right');?>)</td>
	</tr>
	<tr>
	  <td class="ltitle"><?php _e('Account type');?>: </td>
	  <td class="rcontent">
	    <input type="radio" name="account_type" id="no_account" value="no" <?php echo $checked1;?> /> <label for="no_account"><?php _e('Newly registered');?></label>&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="radio" name="account_type" id="has_account" value="yes" <?php echo $checked2;?> /> <label for="has_account"><?php _e('Already registered');?></label>
	  </td>
	</tr>
	<tr>
	  <td class="ltitle"><?php _e('Account');?>: </td>
	  <td class="rcontent">
	    <input type="text" class="bshareput" name="bshare_mail" value="<?php echo $account;?>" /> (<?php _e('E-mail address');?>)
	  </td>
	</tr>
	<tr>
	  <td class="ltitle"><?php _e('Password');?>: </td>
	  <td class="rcontent">
	    <input type="password" class="bshareput" name="bshare_pwd" value="" />
	  </td>
	</tr>
	<tr class="specialtr" <?php if(!empty($checked2)) echo 'style="display:none;"';?>>
	  <td class="ltitle"><?php _e('Confirm password');?>: </td>
	  <td class="rcontent">
	    <input type="password" class="bshareput" name="bshare_pwd2" value="" />
	  </td>
	</tr>
	<tr>
	  <td colspan="2" style="color:#D00;height:15px;line-height:15px;" align="center"><?php echo (isset($errmsg)&&!empty($errmsg))?$errmsg:'&nbsp;&nbsp;';?></td>
	</tr>
	<tr>
	  <td class="ltitle">&nbsp;&nbsp;</td>
	  <td class="rcontent">
		<input type="hidden" name="btnsubmit" value="sendBshare" />
		<input type="submit" name="send" id="submit" value=" <?php _e('Registered bShare');?> " />
	  </td>	
	</tr>
  </table>
</form></div>
<script language="javascript">
$(function(){
	var $chklogn = $('#wp-bshare_checklogn');
	// Radio
	$chklogn.find(':radio').click(function(e){
		var ent = '',id = $(this).attr("id");
		ent = (id=='has_account')?'hide':'show';
		$chklogn.find('tr.specialtr')[ent]();
	});
});
function checkForm(self){
	var $form = $(self),$alltxt = $form.find(':text'),$allpwd = $form.find(':password'),$domain = $alltxt.filter('.bshareput[name="bshare_domain"]'),
	$email = $alltxt.filter('.bshareput[name="bshare_mail"]'),$pwd1 = $allpwd.filter('.bshareput[name="bshare_pwd"]');
	// Domain
	var domain = $.trim($domain.val());
	if (domain.length == 0) {
		alert(parent.bshare_translate('Enter domain'));
		$domain.focus();
		return false;
	} else if (!/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(domain)) {
		alert(parent.bshare_translate('Invalid domain'));
		$domain.select();
		return false;
	}
	// E-mail
	var email = $.trim($email.val());
	if (email.length == 0) {
		alert(parent.bshare_translate('Enter email'));
		$email.focus();
		return false;
	} else if (!/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/.test(email)) {
		alert(parent.bshare_translate('Invalid email'));
		$email.select();
		return false;
	}
	// Password
	var pwd1 = $.trim($pwd1.val());
	if (pwd1.length == 0) {
		alert(parent.bshare_translate('Enter password'));
		$pwd1.focus();
		return false;
	}
	var atype = $form.find(':radio:checked').val();
	if (atype == 'no') {
		var $pwd2 = $allpwd.filter('.bshareput[name="bshare_pwd2"]'),
		pwd2 = $.trim($pwd2.val());
		if ((pwd2.length == 0) || (pwd2 != pwd1)) {
			alert(parent.bshare_translate('Check password'));
			$pwd2.select();
			return false;
		}
	}
	return true;
}
</script>