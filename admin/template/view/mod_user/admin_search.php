<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
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
   // if(ACL::isAdminActionHasPermission('mod_roles', 'admin_list')&&Toolkit::isSiteStarAuthorized()){
?>   
    <li><a class="usercont nopngfilter_spec" href="<?php echo Html::uriquery('mod_roles', 'admin_list'); ?>" title=""><?php _e('Role Manage');?></a></li>
<?php
//}
?>
</ul>

<form action="<?php  echo Html::uriquery("mod_user","search_list"); ?>" method="post" name="theform" id="theform">
        <table width="90%" cellspacing="0" cellpadding="0" border="0" align="left" style="margin:15px;width:96%;">
                        <tr>
                          <td height="22" align="center" valign="middle" nowrap="nowrap" width="180"><?php _e("Username");?></td>
                          <td valign="middle" align="left">
						  <input type="text" class="spcinput" value="" size="15" name="user_name" />
						  <input type="radio" value="1" name="name_c" />
                            <?php _e("Accuracy");?>
                            <input type="radio" value="0" name="name_c" checked="checked" />
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
