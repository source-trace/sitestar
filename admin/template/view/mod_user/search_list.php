<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$m_role = SessionHolder::get('user/s_role');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<script language="javascript">
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

</script>
</head>

<body>
<ul style="margin-left:1px;">
	<li><h3><?php _e('Search user');?></h3></li>
</ul>
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
    <th width="14%"><?php _e('Last Login IP'); ?></th>
    <th width="8%"><?php _e('Active'); ?></th>
    <th width="8%"><?php _e('Verify'); ?></th>
    <th width="16%"><?php _e('Operation'); ?></th>
  </tr>
  <?php
  
    if (sizeof($users) > 0) {
        $row_idx = 0;
        foreach ($users as $user) {
			if($m_role!='{admin}'){
				if (intval($user->id) == 1 || $user->s_role=="{admin}") {
					continue;
				}
			}
            if (intval($user->id) == 1 || $user->s_role=="{admin}") {
                continue;
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
        	<td><span id="uactive_<?php echo $user->id; ?>"></span>
        		<script type="text/javascript" language="javascript">
        		<!--
        			show_active("<?php echo $user->id; ?>", "<?php echo $user->active; ?>","<?php echo $user->s_role; ?>");
        		//-->
        		</script>
        	</td>
			<td>
			
			<?php
			
			if($user->s_role == '{admin}'){
			
			}else{
            $needchange=true;
            if(!ACL::isAdminActionHasPermission('mod_user', 'admin_pic')) $needchange=false;
            echo Toolkit::validateYesOrNo($user->member_verify,$user->id,"index.php?_m=mod_user&_a=admin_pic&_r=ajax&_id=".$user->id,$needchange);
			}
            ?>
        	</td>
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
                    <a href="#" onClick="delete_user(<?php echo $user->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
        			&nbsp;
                    <?php
                            }
                          ?>
					<?php if(EZSITE_LEVEL=='2'&&ACL::isAdminActionHasPermission('mod_user', 'admin_finance')){?>
        			<a href="<?php echo Html::uriquery('mod_user', 'admin_finance', array('u_id' => $user->id)); ?>" title="<?php _e('Finance'); ?>"><img style="border:none;position:relative;top:3px;" alt="<?php _e('Finance');?>" src="<?php echo P_TPL_WEB; ?>/images/money.png"/></a>
					<?php }
					}
					?>
					<!--?php }?-->
        		</span>
        	</td>
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
<form action="<?php  echo Html::uriquery("mod_user","search_list"); ?>" method="post" name="theform" id="theform">
        <table width="90%" cellspacing="0" cellpadding="0" border="0" align="left" style="margin:15px;width:96%;">
                        <tr>
                          <td height="22" align="center" valign="middle" nowrap="nowrap" width="180"><?php _e("Username");?></td>
                          <td valign="middle" align="left">
						  <input type="text" class="spcinput" value="" size="15" name="user_name" />
						  <input type="radio" value="1" name="name_c" />
                            <?php _e("Accuracy");?>
                            <input type="radio" value="0" checked="checked" name="name_c" />
                            <?php _e("Fuzzy");?>
                          </td>
						  <td align="center"><?php _e("E-mail");?></td>
						  <td><input type="text" value="" size="15" id="email" name="email" /></td>
                        </tr>
                        <tr>
                          <td height="22" align="center" valign="middle" nowrap="nowrap" width="180"><?php _e("Active");?></td>
                          <td valign="middle" align="left">
                            <input type="radio" value="1" name="active" />
                            <?php _e("Normal");?>
                            <input type="radio" value="0" name="active" />
                            <?php _e("Locked");?> </td>
                          <td valign="middle" align="center"><?php _e('Role'); ?></td>
                          <td valign="middle" align="left">
						  <select class="textselect" id="role" name="role">
						  <option value=""><?php _e('Select'); ?></option>
						  <?php foreach($roles as $role){ 
						  ?>
						  <option  value="<?php echo $role->name;?>"><?php echo $role->desc;?></option>
						  <?php } ?>
						  </select>
						  </td>
                        </tr>
                        <tr>
                          <td height="15" colspan="4" valign="middle" nowrap="nowrap">&nbsp;</td>
                        </tr>
						<tfoot>
                        <tr>
                          <td colspan="3" align="center" valign="middle" nowrap="nowrap" class="search_td">
						 <?php  echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Search'));?></td><td></td>
                        </tr>
						</tfoot>
                  </table>
          </form>
</body>
</html>
