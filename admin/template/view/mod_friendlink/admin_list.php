<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer').cluetip({splitTitle: '|',width: '300px',height:'68px'});
});
	
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminfllst_stat");
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
    document.getElementById("adminfllst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_friendlink(friendlink_id) {
	if (confirm("<?php _e("Delete the selected friendlink?");?>")) {
	    var stat = document.getElementById("adminfllst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected friendlink...'); ?>";
		_ajax_request("mod_friendlink", 
			"admin_delete", 
	        {
	            friendlink_id:friendlink_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
function delete_friendlinks(){
	var arr = document.getElementsByName("friendlink");
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
		delete_friendlink(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("friendlink");
		if(el.checked){
			for(i=0;i<arr.length;i++){arr[i].checked=true;}
		}else{
			for(i=0;i<arr.length;i++){arr[i].checked=false;}
		}
	}catch(e){
		return false;
	}
}
//-->
</script>
<ul style="margin-left:1px;">
	<li><a class="icontj" href="<?php echo Html::uriquery('mod_friendlink', 'admin_add'); ?>" title=""><?php _e('Add Friendlink'); ?></a></li>
    <li><a class="iconsc" href="javascript:void(0)" onclick="delete_friendlinks();"><?php _e('Delete Selected'); ?></a></li>
    <li style="padding-top:15px;"><?php include_once(P_TPL.'/common/language_switch.php'); if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:0;_margin-top:16px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Language note');?>"/>
	<?php } ?></li>
	<li><form id="articleorderform" method="post" action="index.php" name="articleorderform">
<input style="_margin-top:10px;margin-top:10px;" type="submit" value="<?php _e('Save Order');?>" id="submit" name="submit"/></li>
</ul>
<div class="status_bar">
	<span id="adminfllst_stat" class="status" style="display:none;"></span>
</div>


<input id="_m" type="hidden" value="mod_friendlink" name="_m">
<input id="_a" type="hidden" value="admin_order" name="_a">
<input id="_r" type="hidden" value="_ajax" name="_r">
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="form_table_list" id="admin_lang_list" style="line-height:24px;margin-top:0;">
	<thead>
		<tr>
		    <th width="6%"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th width="20%"><?php _e('Name'); ?></th>
            <th width="10%"><?php _e('Type'); ?></th>
			<th width="13%"><?php _e('Order');?></th>
            <th width="15%"><?php _e('Publish'); ?></th>
            <th width="24%"><?php _e('Edit'); ?></th>
            <th width="25%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($friendlinks) > 0) {
        $row_idx = 0;
        foreach ($friendlinks as $friendlink) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td bgcolor="#f6f6f4"><?php echo Html::input('checkbox', 'friendlink', $friendlink->id); ?></td>
        	<td><?php echo $friendlink->fl_name; ?></td>
        	<td><?php if($friendlink->fl_type=="1"){_e("image link");}else if($friendlink->fl_type=="2"){_e("text link");}; ?></td>
			<td><?php echo Html::input('text', 'i_order['.$friendlink->id.']', $friendlink->i_order, 'class="textinput" style="width:20px;"'); ?></td>
        	<td><?php echo Toolkit::validateYesOrNo($friendlink->published,$friendlink->id,Html::uriquery('mod_friendlink', 'admin_pic', array('_id' => $friendlink->id)));?></td>
        	<td><a href="<?php echo Html::uriquery('mod_friendlink', 'admin_edit', array('friendlink_id' => $friendlink->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" src="template/images/edit.gif" alt="edit"/></a></td>
        	<td><a href="#" onclick="delete_friendlink(<?php echo $friendlink->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" src="template/images/cross.gif" alt="delete"></a></td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="5"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
</form>
<?php
//分页加入
include_once(P_TPL.'/common/pager.php');
?>