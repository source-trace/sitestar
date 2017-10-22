<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminmesslst_stat");
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
    document.getElementById("adminmesslst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_message(mess_id) {
	if (confirm("<?php _e('Delete selected messages?'); ?>")) {
	    var stat = document.getElementById("adminmesslst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected messages...'); ?>";
		_ajax_request("mod_message", 
			"admin_delete", 
	        {
	            mess_id:mess_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
function delete_messages(){
	var arr = document.getElementsByName("message");
	var str="";
	for (var i = 0; i < arr.length; i++){
		var e = arr[i];
		if (e.checked){
			str = e.value + "_" + str;
		}
	}
	if(str.length < 1) {
		alert("<?php _e('Please select items to be deleted!'); ?>");
	} else {
		delete_message(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("message");
		if(el.checked){
			for(i=0;i<arr.length;i++){arr[i].checked=true;}
		}else{
			for(i=0;i<arr.length;i++){arr[i].checked=false;}
		}
	}catch(e){
		return false;
	}
}

function siteGuid() 
{  	
    show_iframe_win('index.php?<?php echo Html::xuriquery('mod_wizard', 'admin_index'); ?>', '', 732, 503);
}
//-->
</script>
<ul style="margin-left:1px;">
	<li><a class="iconsc" href="javascript:void(0)" onclick="delete_messages();"><?php _e('Delete Selected'); ?></a></li>
	<?php
    if(ACL::isAdminActionHasPermission('mod_msg_field', 'admin_list')){
?>   
    <li><a class="msgcont nopngfilter_spec" href="<?php echo Html::uriquery('mod_msg_field', 'admin_list'); ?>" title=""><?php _e('Message field'); ?></a></li>
<?php
}
?>
</ul>
<div class="status_bar">
	<span id="adminmesslst_stat" class="status" style="display:none;"></span>
</div>
<table class="form_table_list" id="admin_message_list" width="100%" border="0" cellspacing="1" cellpadding="0" style="line-height:24px;margin-top:0;">
	<thead>
		<tr>
		    <th width="10%"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th><?php _e('Nickname'); ?></th>
            <th><?php _e('E-mail'); ?></th>
            <th><?php _e('Telephone'); ?></th>
            <th><?php _e('Create Time'); ?></th>
			<th width="10%"><?php _e('View'); ?></th>
			<th width="10%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($messages) > 0) {
        $row_idx = 0;
        foreach ($messages as $message) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo Html::input('checkbox', 'message', $message->id); ?></td>
        	<td class="left"><?php echo $message->username; ?></td>
        	<td><?php echo $message->email; ?></td>
        	<td><?php echo $message->tele; ?></td>
        	<td><?php $create_time = date("Y-m-d H:i:s", $message->create_time);echo $create_time; ?></td>
			<td>
            	<a href="<?php echo Html::uriquery('mod_message', 'admin_view', array('mess_id' => $message->id));?>" title="<?php _e('View'); ?>"><img style="border:none;" alt="<?php _e('View');?>" src="<?php echo P_TPL_WEB; ?>/images/view.gif"/></a>
        	</td>
        	<td>
        		<a href="#" onclick="delete_message(<?php echo $message->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="7"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<?php
include_once(P_TPL.'/common/pager.php');
?>
