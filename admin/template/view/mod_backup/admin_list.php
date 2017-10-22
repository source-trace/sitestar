<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer1').cluetip({splitTitle: '|',width: '250px',height:'35px'});
});
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminartfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["backupform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        alert("<?php _e('Backup Complete!'); ?>");
	    stat.innerHTML = "<?php _e('Backup Complete!'); ?>";
//	    parent.window.location.reload();
//	    window.parent.tb_remove();
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["backupform"].reset();
    document.getElementById("adminartfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function submitDownForm( dir ) {
	document.downfrm.dfile.value = dir;
	document.downfrm.submit();
}

function import_backup(key) {
	if (confirm("<?php _e('Import the selected file?'); ?>")) {
		var stat = document.getElementById("adminartfrm_stat");
		stat.style.display = "block";
		stat.innerHTML = "<?php _e('Import selected backup file...'); ?>";
		_ajax_request("mod_backup", 
				"import", 
		        {
		            '_fid':key
		        }, 
				on_import_success, 
				on_import_failure);
	}
}

function delete_backup(key) {
	if (confirm("<?php _e('Delete the selected backup file?'); ?>")) {
	    var stat = document.getElementById("adminartfrm_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected backup file...'); ?>";
		_ajax_request("mod_backup", 
			"admin_delete", 
	        {
	            '_fid':key
	        }, 
			on_del_success, 
			on_del_failure);
	}
}

function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminartfrm_stat");
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
    document.getElementById("adminartfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function on_import_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminartfrm_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        alert("<?php _e('Backup successfully!')?>");
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    //parent.window.location.reload();
		reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_import_failure(response) {
    document.getElementById("adminartfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
function byPage(page) {
	$('#pagefrm input:last').val(page);
	document.getElementById('pagefrm').submit();
}
//-->
</script>
<style type="text/css">
/* Page */
.pager {margin-top:15px;text-align:center;}
.page_square {margin:0 3px;padding:3px 5px;display:inline-block;}
.phover {color:#FFF;background-color:#0468B4;}
</style>
<div class="status_bar">
	<span id="adminartfrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$backup_form = new Form('index.php', 'backupform', 'check_login_info');
$backup_form->p_open('mod_backup', $next_action, '_ajax');
?>
<table id="backupform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
	<tbody>
    	<tr>
    		<td class="label" style="width:15%;"><?php _e('Backup File')?>:</td>
    		<td width="25%">
    		<?php
            echo Html::input('text', 'backup[file_name]', $file_name, 'class="textinput"', $backup_form, 'RequiredTextbox', 
                __('Please input file name!'));
            ?>
            </td>
            <?php
            // 25/03/2010 Jane Add >>
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            { ?>
            <td width="8%"><?php echo Html::input('submit', 'submit', __('Backup'));?></td>
            <td><img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="tips" title="<?php _e('Set backup note');?>" /></td>
            <?php
            // 25/03/2010 Jane Add <<
            }else{
            ?>
            <td><?php echo Html::input('submit', 'submit', __('Backup'));?></td>
            <?php } ?>
    	</tr>
    </tbody>
</table>
<!-- 2010/03/16 Jane Add>> -->
<?php
$sum=sizeof($list);
$pagenum = 5;// 1 | 2 | 3 | 4 ...| 10
$post_page = trim(ParamHolder::get('page'));
$page = (!isset($post_page) || (intval($post_page) <= 0)) ? 1 : $post_page;
$pstart = ($page - 1)*PAGE_SIZE;
$pend = $page*PAGE_SIZE;
$pagesum = ceil($sum/PAGE_SIZE);


function pagerLinks($page, $pagesum, $pagenum) {
    $prev_page = ($page == 1) ? 1: $page - 1;
    $next_page = ($page == $pagesum) ? $pagesum : $page + 1;
    
    // set pages
	$page_list='';
    $page_list .= '<a class="page_square" href="#" onclick="byPage(1)">'.__('First').'</a>';
	$page_list .= '<a class="page_square" href="#" onclick="byPage('.$next_page.')">'.__('Next').'</a>';
	
	$startPage = ($page - ceil($pagenum/2) + 1 > 0) ? $page - ceil($pagenum/2) + 1 : 1;
	$endPage = $startPage + $pagenum;
	if ($endPage > $pagesum + 1) $endPage = $pagesum + 1;
	if ($startPage + 1 >= $pagenum) {
		$page_list .= '&nbsp;<a href="#" class="page_square" onclick="byPage(1)">1</a>...&nbsp;';
	}
   for($i=$startPage; $i<$endPage; $i++) {			  
		if ($i == $page) {
			$page_list .= '<a href="#" class="page_square phover" onclick="byPage('.$i.')">'.$i.'</a>';
		} else {
			$page_list .= '<a href="#" class="page_square" onclick="byPage('.$i.')">'.$i.'</a>';
		}
    }
    if ($endPage < $pagesum + 1) {
		$page_list .= '...';
        $page_list .= '<a href="#" class="page_square" onclick="byPage('.$pagesum.')">'.$pagesum.'</a>';
	}
    $page_list .= '<a class="page_square" href="#" onclick="byPage('.$prev_page.')">'.__('Previous').'</a>';
    $page_list .= '<a class="page_square" href="#" onclick="byPage('.$pagesum.')">'.__('Last').'</a>';
	
	return $page_list;
}
?>
<table class="form_table_list" border="0" cellspacing="1" cellpadding="0" style="line-height:24px;">
	<tbody>
		<tr>
			<th><?php _e('Name');?></th>
			<th><?php _e('Size');?></th>
			<th><?php _e('Create Time');?></th>
			<th><?php _e('Operation');?></th>
		</tr>
		<?php
		$i=0;
		if(sizeof($list)>0){
		foreach( $list as $key => $value )
		{	
			$v_tag = 1;
			if(($i >= $pstart) && ($i < $pend)){
				$i++;
				$v_count = count($value);
				if($v_count>1){//存在分卷的备份
					for($v_i=0;$v_i<$v_count;$v_i++){
						$v_f_size += $value[$v_i]['fsize'];
					}
					if($v_tag==1){
					$v_name = explode ( "_v", $value[0]['fname'] );
					$v_name = $v_name[0];
					$v_name_t = $v_name."_v1.sql";
		?>
				<tr>
					<td style="padding-left:15px;text-align:left;"><?php echo $v_name;?></td>
					<td><?php echo $v_count;?><?php _e('Volume');?> <?php echo round( $v_f_size/1024, 2 );?> KB</td>
					<td><?php echo date( 'Y-m-d H:i', $value[0]['ftime'] );?></td>
					<td>
						<a href="#" onclick="import_backup('<?php echo $v_name_t; ?>');return false;" title="<?php _e('Import'); ?>"><img src="<?php echo P_TPL_WEB;?>/images/import.gif" style="border:none;cursor:pointer" title="<?php _e('Import');?>"></a>&nbsp;&nbsp;
						<a href="index.php?_m=mod_backup&_a=admin_load&_fid=<?php echo $v_name_t;?>"><img style="border:none;" title="<?php _e('Click to download a database backup file');?>" src="<?php echo P_TPL_WEB;?>/images/download.gif"></a>&nbsp;&nbsp;
						<a href="#" onclick="delete_backup('<?php echo $v_name_t; ?>');return false;" title="<?php _e('Delete'); ?>"><img src="<?php echo P_TPL_WEB;?>/images/cross.gif" style="border:none;cursor:pointer" title="<?php _e('Delete');?>"></a>
					</td>
				</tr>
		<?php		
						$v_tag++;
					}else{
						continue;
					}
					$v_f_size = 0;
				}else{
					$v_name = explode ( "_v", $value[0]['fname'] );
					$v_name = $v_name[0];
					$d_file_name = $value[0]['fname'];
		?>
				<tr>
					<td style="padding-left:15px;text-align:left;"><?php echo $value[0]['fname'];?></td>
					<td><?php echo round( $value[0]['fsize']/1024, 2 );?> KB</td>
					<td><?php echo date( 'Y-m-d H:i', $value[0]['ftime'] );?></td>
					<td>
						<a href="#" onclick="import_backup('<?php echo $d_file_name;?>');return false;" title="<?php _e('Import'); ?>"><img src="<?php echo P_TPL_WEB;?>/images/import.gif" style="border:none;cursor:pointer" title="<?php _e('Import');?>"></a>&nbsp;&nbsp;
						<a href="index.php?_m=mod_backup&_a=admin_load&_fid=<?php echo $d_file_name;?>"><img style="border:none;" title="<?php _e('Click to download a database backup file');?>" src="<?php echo P_TPL_WEB;?>/images/download.gif"></a>&nbsp;&nbsp;
						<a href="#" onclick="delete_backup('<?php echo $value[0]['fname'];?>');return false;" title="<?php _e('Delete'); ?>"><img src="<?php echo P_TPL_WEB;?>/images/cross.gif" style="border:none;cursor:pointer" title="<?php _e('Delete');?>"></a>
					</td>
				</tr>
		<?php
				}
			}else{
				$i++;
				continue;
			}
		}
		}
		if ( !sizeof($list) ) {
		?>
		<tr><td colspan="4"><?php _e('No Records!');?></td></tr>
		<?php } ?>
	</tbody>
</table>

<!-- 2010/03/16 Jane Add<< -->
<?php
$backup_form->close();
$running_msg = __('Being processed');
$custom_js = <<<JS
$("#adminartfrm_stat").css({"display":"block"});
$("#adminartfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;
JS;
$backup_form->addCustValidationJs($custom_js);
$backup_form->writeValidateJs();
?>
<form name="pagefrm" id="pagefrm" method="post" action="">
<input type="hidden" name="page" value="" />
<div class="pager">
<?php 
if($pagesum > 1) echo pagerLinks($page, $pagesum, $pagenum);?>
</div>
</form>
<?php
$import_form = new Form('index.php', 'importform', 'check_login_info1');
$import_form->setEncType('multipart/form-data');
$import_form->p_open('mod_backup', 'import_file');
?>
<br />
<table id="importform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
	<tbody>
		<tr>
			<td class="label" style="width:15%;"><?php _e('Import File');?>:</td>
    		<td width="25%">
    		<?php
            echo Html::input('file', 'import_file', '', 
                '', $import_form);
            ?>
            </td>
            <td><?php echo Html::input('submit', 'submit', __('Import'), 'style="float:left;"');?></td>
        </tr>
	</tbody>
</table>
<?php
$import_form->close();
$import_form->writeValidateJs();
?>
