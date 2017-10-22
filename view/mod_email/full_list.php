<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
#pagerwrapper {padding:0 15px;width:700px;}
#pagerwrapper table td {padding-right:10px;}
#pagerwrapper .pageinput {width:18px;_width:18px}
#pagerwrapper .page_square{ width:15px;height:13px;_width:15px;_height:13px;background-color:#FAFDFC; border:1px #F6F4F2 solid;padding:0 3px;_padding:0 3px;margin:0 3px;_margin:0 3px;}
#pagerwrapper .page_square_bg{ width:15px;height:13px;_width:15px;_height:13px;background-color:#0468B4; border:1px #F6F4F2 solid;padding:0 3px;_padding:0 3px;}
#pagerwrapper .page_word{ width:50px;height:13px;_width:50px;_height:13px;background-color:#FAFDFC; border:1px #F6F4F2 solid;padding:0 3px;_padding:0 3px;margin:0 3px;_margin:0 3px;}
#pagerwrapper a{color:#0089D1}
.pageinput{border:1px #CCCCCC solid;}
.page_sure{width:50px;_width:50px; border-left:#CCCCCC 1px solid; border-top: #CCCCCC 1px solid; border-right:#999999 1px solid; border-bottom:#999999 1px solid; background-color:#00CCFF;} 
/*table list*/
.form_table_list {margin:15px;width:96%;background-color:#99BBE8;}
.form_table_list tr {background-color:#D2E0F1;color:#4372B0;}
.form_table_list td { border:1px #D2E0F1 solid; padding:5px;  border-collapse:collapse; background-color:#FFF;text-align:center;}
.form_table_list td img {vertical-align:middle;}
#form_note {}
</style>
<br />
<table class="form_table_list" id="form_note" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;margin-top:0;">
		<tr>
            <th width="74%"><?php _e('Title'); ?></th>
			<th width="74%"><?php _e('identifying'); ?></th>
        </tr>
    <?php
    if (sizeof($bulletins) > 0) {
        $row_idx = 0;
        foreach ($bulletins as $bulletin) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td style="text-align:center;"><a href="<?php echo Html::uriquery2('mod_email', 'detail',array('id'=>$bulletin->id)); ?>"><?php echo $bulletin->title; ?></a>
			</td>
			<td><?php if($bulletin->is_read==0){?>
			<font style="font-weight:bold"><?php _e('unread'); ?></font>
			<?php }else{ ?>
			<?php _e('read'); ?>
			<?php } ?>
			</td>
            
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="2"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
	<tr><td colspan="2"><div id="pagerwrapper"><?php echo $pager ?></div></td></tr>
</table>

