<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script language="javascript">
$(document).ready(function(){
	$("#m_note").click(function(){
		$("#note_single").hide();
		$("#note").show();
	})
	$("#s_note").click(function(){
		$("#note").hide();
		$("#note_single").show();
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
<div id="cate_new"><ul><li class="selected"><a href="index.php?_m=mod_email&_a=admin_list"><?php _e("Site note");?></a></li>

<li><a href="index.php?_m=mod_email&_a=email_list"><?php _e("E-mail");?></a></li></ul></div>

<div style="height:10px; text-align:left; padding:20px;">
	&nbsp;&nbsp;&nbsp;
	<font>
	<input type="radio" name="c_type" id="s_note" checked="checked">&nbsp;&nbsp;&nbsp;<?php _e("texting");?>&nbsp;&nbsp;&nbsp;<input type="radio" name="c_type" id="m_note">&nbsp;&nbsp;&nbsp;<?php _e("mass texting");?> &nbsp;&nbsp;&nbsp;<a href="index.php?_m=mod_email&_a=note_list"><?php _e("Sended list");?></a></font>
</div>
<form name="note_single" id="note_single" method="post" action="index.php?_m=mod_email&_a=do_note_single">
<table class="form_table_list2" id="" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;margin-top:0;">
        <tr>
        	<td><?php _e("Username");?>:</td>
        	<td align="left">&nbsp;&nbsp;&nbsp;<input type="text" name="user" size="40">&nbsp;&nbsp;&nbsp;<?php _e("Multiple users with '|' (English half-width) separated");?>
        	</td>
        </tr>
        <tr>
        	<td><?php _e("Title");?>:</td>
        	<td align="left">&nbsp;&nbsp;&nbsp;<input type="text" name="title" size="40"> </td>
        </tr>
        <tr>
        	<td><?php _e("Content");?>:</td>
        	<td><?php
            echo Html::textarea('message2', '', 'rows="24" cols="108"')."\n";
            $o_fck = new RichTextbox('message2');
            $o_fck->height = 220;
			$o_fck->width = 320;
            echo $o_fck->create();
            ?> </td>
        </tr>
        <tr>
        	<td width=""></td>
        	<td>
			<div style="display:block; position:relative; text-align:left; margin-right:300px;">
			<?php
            echo Html::input('submit', 'submit', __('Send')," class=''");
            ?>
           </div>
			</td>
        </tr>    	

</table>
</form>

<form name="note" id="note" method="post" action="index.php?_m=mod_email&_a=do_note" style="display:none;">
<table class="form_table_list2" id="" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;margin-top:0;">
        <tr>
        	<td><?php _e("Receive member");?>:</td>
        	<td align="left">&nbsp;&nbsp;&nbsp;<?php 
        	foreach ($roles as $role){
        	?>	
        	<input type="checkbox" name="role[]" value="<?php echo $role->name;?>"><?php echo $role->desc;?>&nbsp;&nbsp;&nbsp;	
        	<?php 
        	}
        	?></td>
        </tr>
        <tr>
        	<td><?php _e("Title");?>:</td>
        	<td align="left">&nbsp;&nbsp;&nbsp;<input type="text" name="title" size="40"> </td>
        </tr>
        <tr>
        	<td><?php _e("Content");?>:</td>
        	<td><?php
            echo Html::textarea('message', '', 'rows="24" cols="108"')."\n";
            $o_fck = new RichTextbox('message');
            $o_fck->height = 220;
            echo $o_fck->create();
            ?> </td>
        </tr>
        <tr>
        	<td></td>
        	<td>
			<div style="display:block; position:relative; text-align:left; margin-right:300px;">
			<?php
            echo Html::input('submit', 'submit', __('Send')," class=''");
            ?>
           </div>			
			</td>
        </tr>    	

</table>
</form>


