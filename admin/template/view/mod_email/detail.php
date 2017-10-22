<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
/*table list*/
.form_table_list {margin:15px;width:96%;background-color:#99BBE8;}
.form_table_list tr {background-color:#D2E0F1;color:#4372B0;}
.form_table_list td { border:1px #D2E0F1 solid; padding:5px;  border-collapse:collapse; background-color:#FFF;text-align:center;}
.form_table_list td img {vertical-align:middle;}
</style>
<style type="text/css">
#cate_new{background-color:#F8F8F8;}
#cate_new .selected{ background:url(images/selected_bg.jpg);color:#fff ;}
#cate_new .selected a{color:#fff !Important;}
#cate_new ul{height:33px;line-height:33px;list-style:none; margin:0 0 0 30px !Important; padding:5px 0; font-size:12px;}
#cate_new li{float:left;text-align:center;cursor:pointer;margin:0 !Important; padding:0; width:135px; height:33px; line-height:33px;background:url(images/selectli_bg.jpg)}
#cate_new li a{color:#000 !Important;display:block;width:135px; padding:0px !Important;}
#cate_new li a:hover{color:#fff !Important;}
#cate_new li:hover{background:url(images/selected_bg.jpg);}
</style>
<div id="cate_new"><ul><li <?php if($type_get=='note'){ ?>class="selected" <?php } ?>><a href="index.php?_m=mod_email&_a=admin_list"><?php _e("Site note");?></a></li>

<li <?php if($type_get=='email'){ ?>class="selected" <?php } ?>><a href="index.php?_m=mod_email&_a=email_list"><?php _e("E-mail");?></a></li></ul></div>
<ul style="margin-left:1px;min-height: 5px;">
 <?php
    if(ACL::isAdminActionHasPermission('mod_user', 'admin_list')){
?>
	<li><a class="iconbk nopngfilter_spec" href="javascript:;" onClick="history.go(-1);" title=""><?php _e('Back'); ?></a></li>
<?php
}
?>
</ul>
<table class="form_table_list" id="form_note" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;margin-top:0;">
    <tbody>
        <tr>
            <td class="label" width="100"><?php _e('Title'); ?>:</td>
            <td class="entry"  style="word-break:break-all;word-wrap:break-word; text-align:left"><?php echo $note->title;?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Content');?>:</td>
            <td class="entry"  style="word-break:break-all;word-wrap:break-word; text-align:left"><?php echo $note->content;?></td>
        </tr>
    </tbody>
</table>