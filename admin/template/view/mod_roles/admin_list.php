<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminusrlst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_del_failure(response) {
    document.getElementById("adminusrlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_user(user_id) {
	if (confirm("<?php _e('Delete the selected role?'); ?>")) {
	    var stat = document.getElementById("adminusrlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected role...'); ?>";
		_ajax_request("mod_roles", 
			"admin_delete", 
	        {
	            u_id:user_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}




//-->
</script>
<ul style="margin-left:1px;">
 <?php
    if(ACL::isAdminActionHasPermission('mod_roles', 'admin_add')&&Toolkit::isSiteStarAuthorized()){
?> 
	<li><a class="usercont  nopngfilter_spec" href="<?php echo Html::uriquery('mod_roles', 'admin_add'); ?>" title=""><?php _e('Add Role');?></a></li>
	<?php } ?>
	<li><a class="usercont  nopngfilter_spec" href="<?php echo Html::uriquery('mod_user', 'admin_list'); ?>" title=""><?php _e('Member Manage');?></a></li>
</ul>
<div class="status_bar">
	<span id="adminusrlst_stat" class="status" style="display:none;"></span>
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="form_table_list" style="line-height:24px;margin-top:0;">
  <tr>
    <th width="6%" bgcolor="#f6f6f4">ID</th>
    <th width="45%"><?php _e('Name'); ?></th>
    <th width="45%"><?php _e('Operation'); ?></th>
  </tr>
  <?php
    if (sizeof($roles) > 0) {
        $row_idx = 0;
        foreach ($roles as $role) {
            //if (intval($user->id) == 1) {
                //continue;
            //}
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo $role->id; ?></td>
        	<td><?php echo $role->desc; ?></td>
        	<td>
        		<span class="small">
				 <?php
    if(ACL::isAdminActionHasPermission('mod_roles', 'admin_edit')&&Toolkit::isSiteStarAuthorized()){
?> 
        			<a href="<?php echo Html::uriquery('mod_roles', 'admin_edit', array('u_id' => $role->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
					<?php } 
    if(ACL::isAdminActionHasPermission('mod_roles', 'admin_delete')&&Toolkit::isSiteStarAuthorized()){
?> 
        			&nbsp;
        			<a href="#" onclick="delete_user(<?php echo $role->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
        			
					<?php } ?>
        		</span>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="8"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
</table>
<?php
include_once(P_TPL.'/common/pager.php');
?>