<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--


function changePic(isTrue,id,url)
{
	var originSrc=$("#"+id).attr('src');
	if(!originSrc.match(/(?:yes|no)\.gif$/)) return;
	$.ajax({
		type:"POST",
		url:url,
		beforeSend:function(data){
			$("#"+id).attr('src','<?php echo P_TPL_WEB;?>/images/loader.gif');
		},
		success:function(response){
			 var data = $.parseJSON(response);
			if (!data) {
					alert('<?php _e('Request failed!'); ?>'); 
			}
			 if (data.result == "ERROR") {
				 $("#"+id).attr('src',originSrc);
				 alert(data.errmsg); 
			} else if (data.result == "OK") {
				if(data.activeval == 1)
				{
					$("#"+id).attr('src','<?php echo P_TPL_WEB;?>/images/yes.gif').attr('alt','Yes');
				}
				else
				{
					$("#"+id).attr('src','<?php echo P_TPL_WEB;?>/images/no.gif').attr('alt','No');
				}
			} else {
				alert('<?php _e('Request failed!'); ?>'); 
			}
		},
		error:function(data){
			$("#"+id).attr('src','template/images/warning.gif');
		}
	});
}



//-->
</script>
<ul style="margin-left:1px;min-height: 20px;">
 <?php
    if(ACL::isAdminActionHasPermission('mod_user', 'admin_list')){
?>
	<li><a class="iconbk nopngfilter_spec" href="<?php echo Html::uriquery('mod_user', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a></li>
<?php
}
?>
</ul>
<div class="status_bar">
	<span id="adminusrlst_stat" class="status" style="display:none;"></span>
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="form_table_list" style="line-height:24px;margin-top:0;">
	<thead>
		<tr>
            <th ><?php _e('Account name'); ?></td>
		  <th ><?php _e('Is activated'); ?></th>
		 <th >App ID</th>
		  <th >App Secret</th>
            <th ><?php _e('Edit'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($third_accounts) > 0) {
        $row_idx = 0;
		$cur_lang=SessionHolder::get('_LOCALE');
        foreach ($third_accounts as $third_account) {
					$addclass = $row_idx ? ' wp-new-member-form-tr' : '';
    ?>
        <tr class="row_style_<?php echo $row_idx.$addclass; ?>">
        	
        	<td><?php echo $third_account['constant']['name'] ?></td>
        	 <td>
		 <?php echo Toolkit::validateYesOrNo($third_account['active'],"img_active_".$third_account['account_type'],Html::uriquery('mod_third_account', 'admin_pic', array('type'=>$third_account['account_type'])));  ?>
		 </td>       
		<td><?php echo $third_account['appid'];?></td>
		<td><?php echo $third_account['appsecret']; ?></td>
        	<td>
			<a href="<?php echo Html::uriquery('mod_third_account', 'admin_edit',array('type'=>$third_account['account_type']));?>" title="<?php _e('Edit'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
        	</td>
        
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="6"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
