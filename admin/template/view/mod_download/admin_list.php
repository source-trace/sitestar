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
    
    var stat = document.getElementById("admindownlst_stat");
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
    document.getElementById("admindownlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_download(download_id) {
	if (confirm("<?php _e('Delete the selected download?'); ?>")) {
	    var stat = document.getElementById("admindownlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected download...'); ?>";
		_ajax_request("mod_download", 
			"admin_delete", 
	        {
	            download_id:download_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
function delete_downloads(){
	var arr = document.getElementsByName("download");
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
		delete_download(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("download");
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
	<li><a class="iconfl" href="<?php echo Html::uriquery('mod_category_d', 'admin_list', array('goto' => '10000')); ?>"><?php _e('Manage Categories'); ?></a></li>
	<li><a class="icontj" href="<?php echo Html::uriquery('mod_download', 'admin_add'); ?>" title=""><?php _e('Add Download'); ?></a></li>
	<li><a class="iconsc" href="javascript:void(0)" onclick="delete_downloads();"><?php _e('Delete Selected'); ?></a></li>
	<li style="margin-top:14px;margin-right:15px;"><?php //include_once(dirname(__FILE__).'/_category_switch.php');?></li>
	<li style="margin-top:14px;"><?php include_once(P_TPL.'/common/language_switch.php'); if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:0;_margin-top:16px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Language note');?>"/>
	<?php } ?></li>
</ul>
<div class="status_bar">
	<span id="admindownlst_stat" class="status" style="display:none;"></span>
</div>
<table class="form_table_list" id="admin_lang_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="margin-top:0;line-height:24px;">
	<thead>
		<tr>
		    <th width="5%"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th width="36%"><?php _e('Name'); ?></th>
			<th width="12%"><?php _e('Category'); ?></th>
            <th width="11%"><?php _e('Publish'); ?></th>
            <th width="18%"><?php _e('Edit'); ?></th>
            <th width="18%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($downloads) > 0) {
        $row_idx = 0;
        foreach ($downloads as $download) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo Html::input('checkbox', 'download', $download->id); ?></td>
        	<td><?php echo $download->name; ?></td>
        	<td><?php 
			$download->loadRelatedObjects(REL_PARENT, array('DownloadCategory')); 
			if(isset($download->masters['DownloadCategory']->name)){echo $download->masters['DownloadCategory']->name;} ?></td>
        	<td><?php echo Toolkit::validateYesOrNo($download->published,$download->id,Html::uriquery('mod_download', 'admin_pic', array('_id' => $download->id)));?></td>
        	<td>
                <a href="<?php echo Html::uriquery('mod_download', 'admin_edit', array('download_id' => $download->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
        	</td>
        	<td>
        		<a href="#" onclick="delete_download(<?php echo $download->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
        	</td>
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
<?php
include_once(P_TPL.'/common/pager.php');
?>
