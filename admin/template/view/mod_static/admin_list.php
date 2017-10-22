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
function delete_scontents(){
	var arr = document.getElementsByName("scontent");
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
		delete_scontent(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("scontent");
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
    <li><a href="<?php echo Html::uriquery('mod_static', 'admin_add'); ?>" title="" class="icontj"><?php _e('New Static Content'); ?></a></li>
    <li><a href="javascript:void(0)" onclick="delete_scontents();" class="iconsc"><?php _e('Delete Selected'); ?></a></li>
    <li style="padding-top:15px;"><?php include_once(P_TPL.'/common/language_switch.php'); if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:0;_margin-top:16px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Language note');?>"/>
	<?php } ?></li>
</ul>
<span id="adminsclst_stat" class="status" style="display:none;"></span>
<table class="form_table_list" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;" id="admin_scontent_list">
	<thead>
		<tr>
		    <th width="20"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th><?php _e('Title'); ?></th>
            <th><?php _e('Status'); ?></th>
            <th><?php _e('Create Time'); ?></th>
            <th width="6%"><?php _e('Edit'); ?></th>
            <th width="6%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($scontents) > 0) {
        $row_idx = 0;
        foreach ($scontents as $scontent) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo Html::input('checkbox', 'scontent', $scontent->id); ?></td>
        	<td><a href="../<?php echo Html::uriquery('mod_static', 'view',array('sc_id'=>$scontent->id)); ?>" target="_blank"><?php echo $scontent->title; ?></a></td>
        	<td><?php echo Toolkit::switchText($scontent->published, array('0' => __('Unpublished'), '1' => __('Published'))); ?></td>
        	<td><?php echo date('y-n-j g:i', $scontent->create_time); ?></td>
        	<td width="10%">
        		<a href="<?php echo Html::uriquery('mod_static', 'admin_edit', array('sc_id' => $scontent->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" src="<?php echo P_TPL_WEB; ?>/images/edit.gif" alt="<?php _e('Edit'); ?>"/></a>
        	</td>
        	<td width="10%">
        		<a href="#" onclick="delete_scontent(<?php echo $scontent->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" src="<?php echo P_TPL_WEB; ?>/images/cross.gif" alt="<?php _e('Delete'); ?>"/></a>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="6"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<?php
include_once(P_TPL.'/common/pager.php');
?>
