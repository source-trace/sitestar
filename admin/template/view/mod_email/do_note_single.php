<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
.form_table_list {margin:15px;width:96%;background-color:#99BBE8;}
.form_table_list tr {background-color:#D2E0F1;color:#4372B0;}
.form_table_list td { border:1px #D2E0F1 solid; padding:5px;  border-collapse:collapse; background-color:#FFF;text-align:center;}
.form_table_list td img {vertical-align:middle;}
#form_note {}
</style>
<br />
<table class="form_table_list" id="form_note" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;margin-top:0;">
	<tr><td></td></tr>
		<tr>
            <td width="74%"><div><font color="#FF0000"><?php echo $ok;?></font>&nbsp;&nbsp;<?php _e('Sended');?>&nbsp;&nbsp;<font color="#FF0000"><?php echo $err_count;?></font>&nbsp;&nbsp;&nbsp;<?php _e('Send fail');?>
			<br />
			<?php _e('Send fail users');?>：<?php echo $e_eml;?>
			<br />
			<a href="javascript:history.go(-1)"><?php _e('Back');?></a>
			</div>
			
			</td>
        </tr>
	<tr><td></td></tr>
</table>

