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
<br />
<table class="form_table_list" id="form_note" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;margin-top:0;">
    <tbody>
        <tr>
            <td class="label" width="100"><?php _e('Title'); ?>:</td>
            <td class="entry"><?php echo $note->title;?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Content');?>:</td>
            <td class="entry"><?php echo $note->content;?></td>
        </tr>
    </tbody>
</table>