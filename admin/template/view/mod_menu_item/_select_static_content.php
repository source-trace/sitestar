<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<style type="text/css">
@import "template/css/popup.css";
.list_table th {padding-left:3px;}
</style>
<script type="text/javascript">
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminsclst_stat");
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
    document.getElementById("adminsclst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_scontent(sc_id) {
	if (confirm("<?php _e('Delete the selected content?'); ?>")) {
	    var stat = document.getElementById("adminsclst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected content...'); ?>";
		_ajax_request("mod_static", 
			"admin_delete", 
	        {
	            sc_id:sc_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
</script>
<div id="adminsclst_stat" style="display:none;"></div>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<!--td><div class="title"><?php _e('Please select content to display'); ?></div></td-->
				<td>
                    <a href="index.php?<?php echo Html::xuriquery('mod_static', 'admin_mi_quick_add', array('txt' => $type_text)); ?>" style="color:#4372b0;font-weight:bold;"><?php _e('New Static Content'); ?></a>
				</td>
				<td><a href="#" title="" onclick="parent.$('#showContents').show();parent.$('#showContents1').remove();" style="color:#4372b0;font-weight:bold;"><?php _e('Back'); ?></a></td>
			</tr>
		</tbody>
	</table>
</div>
<!--div class="space"></div-->
<table cellspacing="0" class="list_table" id="admin_scontent_list">
	<thead>
		<tr>
		    <th></th>
            <th><?php _e('Title'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($scontents) > 0) {
        $row_idx = 0;
        foreach ($scontents as $scontent) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td></td>
        	<td><?php echo $scontent->title; ?></td>
        	<td style="text-align:right;padding-right:12px;">
				<a href="../index.php?_m=mod_static&_a=view&sc_id=<?php echo $scontent->id;?>" title="<?php _e('View');?>" target="_blank"><img alt="<?php _e('View');?>" src="../images/preview.gif" style="border: medium none;"/></a>
        		<a href="index.php?_m=mod_static&_a=admin_edit&sc_id=<?php echo $scontent->id;?>" title="<?php _e('Edit');?>"><img alt="<?php _e('Edit');?>" src="template/images/edit.gif" style="border: medium none;"/></a>
        		<a onclick="delete_scontent(<?php echo $scontent->id;?>);return false;" href="index.php?_m=mod_static&_a=admin_edit" title="<?php _e('Delete');?>"><img alt="<?php _e('Delete');?>" src="template/images/cross.gif" style="border: medium none;"/></a>
        		<a title="<?php _e('Select');?>" href="#" onclick="select_for_menu_item('<?php echo $type_text.' - '.$scontent->title; ?>', '<?php echo $scontent->id; ?>'); return false;"><img alt="<?php _e('Select');?>" src="template/images/select.gif" style="border: medium none;"/></a>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="3"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<div class="space"></div>
<?php include_once(P_TPL.'/common/pager.php'); ?>
<style type="text/css">
#pagerwrapper {padding:0;}
</style>