<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script language="javascript">
$(document).ready(function(){
	$("#eml").click(function(){
		$("#email_single").hide();
		$("#server").hide();
		$("#email").show();
	})
	$("#s_eml").click(function(){
		$("#email").hide();
		$("#server").hide();
		$("#email_single").show();
	})
	$("#server_c").click(function(){
		$("#email_single").hide();
		$("#email").hide();
		$("#server").show();
	})
})
</script>
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
<div id="cate_new"><ul><li><a href="index.php?_m=mod_email&_a=admin_list"><?php _e("Site note");?></a></li>

<li class="selected"><a href="index.php?_m=mod_email&_a=email_list"><?php _e("E-mail");?></a></li></ul></div>

<div style="height:10px; text-align:left; padding:20px;">
	<span style="margin-left:10px;"></span>
	&nbsp;&nbsp;&nbsp;
	<font><input type="radio" name="c_type" id="s_eml" checked="checked">&nbsp;&nbsp;&nbsp;<?php _e("Single email");?>&nbsp;&nbsp;&nbsp;<input type="radio" name="c_type" id="eml">&nbsp;&nbsp;&nbsp;<?php _e("Massive e-mail");?>&nbsp;&nbsp;&nbsp;
	
	<a href="javascript:;" id="server_c"><?php _e("Mail server set");?></a>&nbsp;&nbsp;&nbsp;
	<a href="index.php?_m=mod_email&_a=send_list"><?php _e("Sended list");?></a>
	</font>
</div>
<form name="email" id="email_single" method="post" action="../index.php?_m=mod_email&_a=do_mail&type=single" <?php if($t=='s'){?> style="display:none"<?php } ?>>
<table class="form_table_list2" id="" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;margin-top:0;">

        <tr>
        	<td><?php _e("Username");?>:</td>
        	<td align="left" colspan="2">&nbsp;&nbsp;&nbsp;<input type="text" name="users" size="40">&nbsp;&nbsp;&nbsp;<?php _e("Multiple users with '|' (English half-width) separated");?></td>
        </tr>
        <tr>
        	<td><?php _e("Title");?>:</td>
        	<td align="left" colspan="2">&nbsp;&nbsp;&nbsp;<input type="text" name="title" size="40"> </td>
        </tr>
        <tr>
        	<td><?php _e("Content");?>:</td>
        	<td colspan="2">
			<?php
            echo Html::textarea('email_s', '', 'rows="24" cols="108"')."\n";
            $o_fck = new RichTextbox('email_s');
            $o_fck->height = 220;
			$o_fck->width = 320;
            echo $o_fck->create();
            ?> 
			</td>
        </tr>
        <tr>
        	<td></td>
        	<td colspan="2">
			<div style="display:block; position:relative; text-align:left; margin-right:300px;">
			<?php
            echo Html::input('submit', 'submit', __('Send')," class=''");
            ?>
           </div>
			</td>
        </tr>    	

</table>
</form>

<form name="email" id="email" method="post" action="../index.php?_m=mod_email&_a=do_mail" style="display:none;">
<table class="form_table_list2" id="" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;margin-top:0;">

        <tr>
        	<td><?php _e("Receive member");?>:</td>
        	<td align="left" colspan="2">&nbsp;&nbsp;&nbsp;<?php 
        	foreach ($roles as $role){
        	?>	
        	<input type="checkbox" name="role[]" value="<?php echo $role->name;?>"><?php echo $role->desc;?>&nbsp;&nbsp;&nbsp;	
        	<?php 
        	}
        	?></td>
        </tr>
        <tr>
        	<td><?php _e("Title");?>:</td>
        	<td align="left" colspan="2">&nbsp;&nbsp;&nbsp;<input type="text" name="title" size="40"> </td>
        </tr>
        <tr>
        	<td><?php _e("Content");?>:</td>
        	<td colspan="2">
			<?php
            echo Html::textarea('email_m', '', 'rows="24" cols="108"')."\n";
            $o_fck = new RichTextbox('email_m');
            $o_fck->height = 220;
			$o_fck->width = 320;
            echo $o_fck->create();
            ?> 
			</td>
        </tr>
        <tr>
        	<td></td>
        	<td colspan="2">
			<div style="display:block; position:relative; text-align:left; margin-right:300px;">
			<?php
            echo Html::input('submit', 'submit', __('Send')," class=''");
            ?>
           </div>
			 </td>
        </tr>    	

</table>
</form>

<form id="server" method="post" action="index.php?_m=mod_param&_a=save_mail_server"  name="sparamform" <?php if($t=='s'){?> style="display:block;"<?php }else{ ?>style="display:none;" <?php } ?>>

<table width="100%" border="0" cellspacing="1" cellpadding="0" class="form_table_list2" id="admin_lang_list" style="line-height:24px;margin-top:0;">
       
    <tbody>
	
       <tr height="50">
            <td class="label"><?php _e('Mail server set'); ?>
            </td>
			<td class="entry" colspan="2"><?php _e("Can generally recommended to use QQ mailbox, 163 mailboxes only apply before 2006");?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr height="50">
            <td class="label"><?php _e('SMTP Server'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_SERVER]', defined("SMTP_SERVER")?SMTP_SERVER:'');
            ?>
            </td>
        </tr>
		<tr height="50">
            <td class="label"><?php _e('SMTP Port'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_PORT]', defined("SMTP_PORT")?SMTP_PORT:25);
            ?>
            </td>
        </tr>
        <tr height="50">
            <td class="label"><?php _e('SMTP User'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[SMTP_USER]', defined("SMTP_USER")?SMTP_USER:'');
            ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Please fill in your full email address'); ?>
            </td>
        </tr>

        <tr height="50">
            <td class="label"><?php _e('SMTP Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'sparam[SMTP_PASS]', defined("SMTP_PASS")?SMTP_PASS:'');
            ?>
            </td>
        </tr>	
		 <tr height="50">
		 <td class="label"></td>
            <td class="entry" align="left">
			<div style="display:block; position:relative; text-align:left; margin-right:300px;">
			<?php
            echo Html::input('submit', 'submit', __('Save')," class=''");
            ?>
           </div>
            </td>
        </tr>	
    </tbody>
</table>
<div style="height:20px;">&nbsp;</div>
</form>


