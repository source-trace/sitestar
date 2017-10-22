<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$toogleactivestr=(ACL::isAdminActionHasPermission('mod_user','admin_toggle_active'))?'true':'false';
$curUserid= SessionHolder::get('user/id', 0);
$m_role = SessionHolder::get('user/s_role');
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
	if (confirm("<?php _e('Delete the selected user?'); ?>")) {
	    var stat = document.getElementById("adminusrlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected user...'); ?>";
		_ajax_request("mod_user", 
			"admin_delete", 
	        {
	            u_id:user_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}

function on_tog_success(response) {
	// 10:47 2010-3-11 Jane Edit >>>
    //var o_result = _eval_json(response.responseText);
    var o_result = _eval_json(response);
    // <<< 10:47 2010-3-11 Jane Edit
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminusrlst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
    	show_active(o_result.u_id, o_result.u_acti);
	    stat.style.display = "none";
    } else {
        return on_failure(response);
    }
}

function on_tog_failure(response) {
    on_del_failure(response);
}

function toggle_active(user_id, active) {
     var needtoogle=<?php echo $toogleactivestr; ?>;
     if(!needtoogle) return;
    var stat = document.getElementById("adminusrlst_stat");
    stat.style.display = "block";
    stat.innerHTML = "<?php _e('Changing user status...'); ?>";
    _ajax_request("mod_user", 
    	"admin_toggle_active", 
    	{
    	    u_id:user_id,
    	    u_acti:active
    	}, 
    	on_tog_success, 
    	on_tog_failure);
}

function show_active(user_id, active,rolename) {
    var active_label_id = "uactive_" + user_id;
    var active_html = "";
    if(rolename!='{admin}'){
			if (active == "1") {
					active_html = "<a href=\"#\" onclick=\"toggle_active('" + user_id + "', '0');return false;\" title=\"\">Yes</a>";
			} else {
					active_html = "<a href=\"#\" onclick=\"toggle_active('" + user_id + "', '1');return false;\" title=\"\">No</a>";
			}
	}else{
		if (active == "1") {
					active_html = "<a href=\"#\" onclick=\"return false;\" title=\"\">Yes</a>";
			} else {
					active_html = "<a href=\"#\" onclick=\"return false;\" title=\"\">No</a>";
			}
	}
    
    document.getElementById(active_label_id).innerHTML = active_html;
}
//-->
</script>

<ul style="margin-left:1px;min-height: 20px;">
 <?php
    if(ACL::isAdminActionHasPermission('mod_user', 'admin_add')){
?>
	<li><a class="usercont nopngfilter_spec" href="<?php echo Html::uriquery('mod_user', 'admin_add'); ?>" title=""><?php _e('Add User');?></a></li>
<?php
}
?>
 <?php
    if(ACL::isAdminActionHasPermission('mod_roles', 'admin_list')&&Toolkit::isSiteStarAuthorized()){
?>   
    <li><a class="usercont nopngfilter_spec" href="<?php echo Html::uriquery('mod_roles', 'admin_list'); ?>" title=""><?php _e('Role Manage');?></a></li>
<?php
}
?>
 <?php
    if(ACL::isAdminActionHasPermission('mod_user', 'admin_search')){
?>   
    <li><a class="usercont nopngfilter_spec" href="<?php echo Html::uriquery('mod_user', 'admin_search'); ?>" title=""><?php _e('Search user');?></a></li>
<?php
}
?>
<?php
    if(ACL::isAdminActionHasPermission('mod_user_field', 'admin_list')){
?>   
    <li><a class="usercont nopngfilter_spec" href="<?php echo Html::uriquery('mod_user_field', 'admin_list'); ?>" title=""><?php _e('Custom field'); ?></a></li>
<?php
}
?>
<?php
    if(ACL::isAdminActionHasPermission('mod_third_account', 'admin_edit')){
?>   
    <li><a class="usercont nopngfilter_spec" href="<?php echo Html::uriquery('mod_third_account', 'admin_edit',array('type'=>'qq')); ?>" title=""><?php _e('QQ'); ?></a></li>
<?php
}
?>
</ul>

<div class="status_bar">
	<span id="adminusrlst_stat" class="status" style="display:none;"></span>
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="form_table_list" style="line-height:24px;margin-top:0;">
  <tr>
    <th width="6%" bgcolor="#f6f6f4">ID</th>
    <th width="11%"><?php _e('Login Name'); ?></th>
	<?php if(ACL::isAdminActionHasPermission('mod_roles', 'admin_list')&&Toolkit::isSiteStarAuthorized()){?>
    <th width="11%"><?php _e('Role'); ?></th>
	<?php } ?>
    <th width="14%"><?php _e('E-mail'); ?></th>
    <!--td width="14%">电话号码</td-->
    <th width="14%"><?php _e('Last Login Time'); ?></th>
    <th width="10%"><?php _e('Last Login IP'); ?></th>
	<th width="8%"><?php _e('QQ'); ?></th>
    <th width="8%"><?php _e('Active'); ?></th>
    <th width="8%"><?php _e('Verify'); ?></th>
	<?php 
	if(ACL::isAdminActionHasPermission('mod_user', 'admin_edit')||ACL::isAdminActionHasPermission('mod_user', 'admin_delete')){
                        ?>
    <th width="12%"><?php _e('Operation'); ?></th>
	<?php } ?>
  </tr>
  <?php
    if (sizeof($users) > 0) {
        $row_idx = 0;
        foreach ($users as $user) {
			if($m_role!='{admin}'){
				if (intval($user->id) == 1) {
					continue;
				}
			}
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo $user->id; ?></td>
        	<td><?php echo $user->login; ?></td>
			<?php if(ACL::isAdminActionHasPermission('mod_roles', 'admin_list')&&Toolkit::isSiteStarAuthorized()){?>
        	<td>
					<?php
					echo Role::getRoleDesc($user->s_role); 
					?>
		</td>
		<?php } ?>
        	<td><?php echo $user->email; ?></td>
        	<!--td>021-58356483</td-->
        	<td><?php echo date('Y-m-d H:i:s', $user->lastlog_time); ?></td>
        	<td><?php echo $user->lastlog_ip; ?></td>
		<td><?php echo empty($user->oauth['qq']['nickname'])?'':$user->oauth['qq']['nickname']; ?></td>
        	<td><span id="uactive_<?php echo $user->id; ?>"></span>
        		<script type="text/javascript" language="javascript">
        		<!--
        			show_active("<?php echo $user->id; ?>", "<?php echo $user->active; ?>","<?php echo $user->s_role; ?>");
        		//-->
        		</script>
        	</td>
			<td>
			<?php 
            $needchange=true;
            if(!ACL::isAdminActionHasPermission('mod_user', 'admin_pic')&&$user->s_role != '{admin}') $needchange=false;
            echo Toolkit::validateYesOrNo($user->member_verify,$user->id,"index.php?_m=mod_user&_a=admin_pic&_r=ajax&_id=".$user->id,$needchange);
            ?>
        	</td>
			<?php 
			if(ACL::isAdminActionHasPermission('mod_user', 'admin_edit')||ACL::isAdminActionHasPermission('mod_user', 'admin_delete')){
                        ?>
        	<td>
        		<span class="small">
                        <?php
					if(ACL::isRoleSuperAdmin()|| !ACL::isRoleSuperAdmin($user->s_role)){
                            if(ACL::isAdminActionHasPermission('mod_user', 'admin_edit')){
                        ?>
        			<a href="<?php echo Html::uriquery('mod_user', 'admin_edit', array('u_id' => $user->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
        			&nbsp;
                        <?php
                            }
                          ?>
					<!--?php if ($user->s_role != '{admin}') {?-->
        		 <?php
                            if(ACL::isAdminActionHasPermission('mod_user', 'admin_delete')&&$user->s_role != '{admin}'){
                        ?>	
                    <a href="#" onclick="delete_user(<?php echo $user->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
        			&nbsp;
                    <?php
                            }
                          ?>
					<?php if(EZSITE_LEVEL=='2'&&ACL::isAdminActionHasPermission('mod_user', 'admin_finance')){?>
        			<a href="<?php echo Html::uriquery('mod_user', 'admin_finance', array('u_id' => $user->id)); ?>" title="<?php _e('Finance'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Finance');?>" src="<?php echo P_TPL_WEB; ?>/images/money.png"/></a>
					<?php }
					}
					?>
					
        		</span>
        	</td><?php } ?>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="9"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
</table>
<?php
include_once(P_TPL.'/common/pager.php');
?>