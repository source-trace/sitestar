<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

?>
<script src="../script/jquery.validate.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(function(){	
	
	function parseToAdminURL(module,action,anotherparams){
		var defaultparams={'_m':module,'_a':action}
		var urlparams=$.extend({}, anotherparams, defaultparams);
		var paramstr=$.param(urlparams);
		return "index.php?"+paramstr;
	}

	 jQuery.extend(jQuery.validator.messages, { 
			required: "<?php _e('The field cannot be empty!'); ?>"
	 });
	 
	 $('#fieldaform').validate({
			rules: { 
				'account[appid]':{required: true},
				'account[appsecret]':{required: true}
			},

			submitHandler: function() {
				 var param=$('#fieldaform').serialize();
//				 addLoadingDiv();
				 $.post(parseToAdminURL('mod_third_account','admin_save'),param ,function(o_result) {
//						 $('#wp-ajaxsend_loading2').remove();
						 o_result=$.parseJSON(o_result);
						 if (o_result.result == "ERROR") {
								alert(o_result.errmsg); 
								return false;
						} else if (o_result.result == "OK") {
							location.href="<?php echo Html::uriquery('mod_user', 'admin_list');?>";

						} else {
							alert('<?php _e('Request failed!'); ?>'); 
						}
						
				}).error(function() { 
//					$('#wp-ajaxsend_loading2').remove();
					alert('<?php _e('Request failed!'); ?>'); 
				});
			}
		})
	 
});
function backPrv(){
	window.location.href="index.php?_m=mod_user&_a=admin_list";	
}
//-->
</script>
<div class="wp-new-member-outside">
<div class="status_bar">
	<span id="admincateafrm_stat" class="status" style="display:none;"></span>
</div>
<div class="wp-new-member-adduser-form">
<form id="fieldaform" action="#" > 
<table width="100%" border="0" cellspacing="0" class="form_table" cellpadding="0" style="line-height:24px;">
	<tfoot>
        <tr>
            <td colspan="2">
	<?php
			 echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv()"');
			 echo Html::input('reset', 'reset', __('Reset'));
			 echo Html::input('submit', 'submit', __('Save'));	
			echo Html::input('hidden', 'account[account_type]', $curr_account['account_type']);
      ?>
            </td>
        </tr>
    </tfoot>
	  <tbody>
	<tr>
      <td class="label"><?php _e('Account name'); ?></td>
      <td class="entry"><?php echo $curr_account['constant']['name']; ?></td>
    </tr>
    <tr>
      <td class="label"><?php _e('Is activated'); ?></td>
      <td class="entry"><?php echo Html::input('checkbox', 'account[active]', '1', $curr_account['active']==1?'checked="checked"':'');?></td>
    </tr>
   	<tr>
      <td class="label">App ID<span class="required">*</span></td>
      <td class="entry"><?php echo Html::input('textbox', 'account[appid]', $curr_account['appid'], 'class="textinput"');?></td>
    </tr>
   	<tr>
      <td class="label">App Secret<span class="required">*</span></td>
      <td class="entry"><?php echo Html::input('textbox', 'account[appsecret]', $curr_account['appsecret'], 'class="textinput"');?></td>
    </tr>
	<tr>
      <td class="label"><?php _e('Third-account application links'); ?></td>
      <td class="entry"><a href="<?php echo $curr_account['constant']['dev_url']; ?>" target="_blank"><?php echo $curr_account['constant']['dev_url']; ?></a></td>
    </tr>
	
</tbody>
</table>
</form>

</div><!--wp-new-member-adduser-form end-->
</div><!--wp-new-member-outside end-->