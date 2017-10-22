<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<style type="text/css">
#content .fr li {margin:0;}
#submit {float:left;}
</style>
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer').cluetip({splitTitle: '|',width: '300px',height:'68px'});
});	

function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_del_failure(response);
    }
    
    var stat = document.getElementById("adminartlst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadPage();
    } else {
        return on_del_failure(response);
    }
}

function on_del_failure(response) {
    document.getElementById("adminartlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_bulletin(bulletin_id) {
	if (confirm("<?php _e('Delete the selected bulletin?'); ?>")) {
	    var stat = document.getElementById("adminartlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected bulletin...'); ?>";
		_ajax_request("mod_bulletin", 
			"admin_delete", 
	        {
	            bulletin_id:bulletin_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
function delete_bulletins(){
	var arr = document.getElementsByName("bulletin");
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
		delete_bulletin(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("bulletin");
		if(el.checked){
			for(i=0;i<arr.length;i++){arr[i].checked=true;}
		}else{
			for(i=0;i<arr.length;i++){arr[i].checked=false;}
		}
	}catch(e){
		return false;
	}
}

/*function bulletin_published(bulletin_id) {
	_ajax_request('mod_bulletin', 
		'admin_published', 
		{'bulletin_id': bulletin_id}, 
		on_published_success, 
		on_published_failure);
}*/

function on_published_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_published_failure(response);
    }
    
    if (o_result.result == "ERROR") {
        alert(o_result.errmsg);
        return false;
    } else if (o_result.result == "OK") {
	    alert("<?php _e('Published successful!'); ?>");
    } else {
        return on_published_failure(response);
    }
}

function on_published_failure(response) {
    alert("<?php _e('Request failed!'); ?>");
    return false;
}
//-->
</script>
<div class="status_bar">
	<span id="adminartlst_stat" class="status" style="display:none;"></span>
</div>

<ul style="margin-left:15px;">
<?php
    if(ACL::isAdminActionHasPermission('mod_bulletin', 'admin_delete')){
?>
	<li><a class="iconsc" href="javascript:void(0)" onclick="delete_bulletins();"><?php _e('Delete Selected'); ?></a></li>
<?php
}
?>	
<?php
    if(ACL::isAdminActionHasPermission('mod_bulletin', 'admin_add')){
?>
    <li><a class="icontj" href="<?php echo Html::uriquery('mod_bulletin', 'admin_add'); ?>" title=""><?php _e('Add Bulletin'); ?></a></li>
<?php
}
?>	
    <li style="margin-top:14px;margin-right:5px;"><?php include_once(P_TPL.'/common/language_switch.php');if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:0;_margin-top:16px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Language note');?>"/>
	<?php } ?></li>
</ul>

<table class="form_table_list" id="admin_bulletin_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;margin-top:0;">
	<thead>
		<tr>
		    <th width="5%" bgcolor="#f6f6f4"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th width="74%"><?php _e('Title'); ?></th>
            <th width="7%"><?php _e('Publish'); ?></th>
            <th width="7%"><?php _e('Edit'); ?></th>
            <th width="7%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($bulletins) > 0) {
        $row_idx = 0;
        foreach ($bulletins as $bulletin) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo Html::input('checkbox', 'bulletin', $bulletin->id); ?></td>
        	<td style="text-align:left;padding-left:20px;"><a href="<?php echo Html::uriquery('mod_bulletin', 'admin_detail',array('bulletin_id'=>$bulletin->id)); ?>"><?php echo $bulletin->title; ?></a></td>
        	<td>
            <?php 
            $needchange=true;
            if(!ACL::isAdminActionHasPermission('mod_bulletin', 'admin_pic')) $needchange=false;
            echo Toolkit::validateYesOrNo($bulletin->published,$bulletin->id,"index.php?_m=mod_bulletin&_a=admin_pic&_r=ajax&_id=".$bulletin->id,$needchange);
            ?>
            <!--?php
            $extra = '';
            if ($bulletin->published == '1') $extra = ' checked="true"';
        	echo Html::input('radio', 'published', $bulletin->id, 'onclick="bulletin_published(this.value)"'.$extra);
            ?-->
            </td>
        	<td>
                    <?php
                            if(ACL::isAdminActionHasPermission('mod_bulletin', 'admin_edit')){
                        ?>
            	<a href="<?php echo Html::uriquery('mod_bulletin', 'admin_edit', array('bulletin_id' => $bulletin->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
                    <?php
                            }
                       ?>
          </td>
        	<td>
                    <?php
                            if(ACL::isAdminActionHasPermission('mod_bulletin', 'admin_delete')){
                        ?>	
        		<a href="#" onclick="delete_bulletin(<?php echo $bulletin->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
                      <?php
                            }
                       ?>
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
