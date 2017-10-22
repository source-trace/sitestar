<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	parent.window.location.reload();
	$('#answer1').cluetip({splitTitle: '|',width: '240px',height:'60px'});
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
	    stat.innerHTML = "<?php _e('Site information saved!'); ?>";
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
//-->
</script>
<div class="status_bar">
	<span id="adminartfrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$backup_form = new Form('index.php', 'backupform', 'check_login_info');
$backup_form->p_open('mod_backup', 'admin_backup', '_ajax');
?>
<table id="backupform_table" class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
	<tbody>
    	<tr>
    		<td width="10%"><?php _e('Backup File')?>:</td>
    		<td width="25%">
    		<?php
    		$current_time = date("YmdHis");
		    $random = rand(100,999);
	    	$file_name = 'backup_'.$current_time.".sql";
            echo Html::input('text', 'backup[file_name]', $file_name, '', $backup_form, 'RequiredTextbox', 
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
<table class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
	<tbody>
		<tr>
			<td><?php _e('Name');?></td>
			<td><?php _e('Size');?></td>
			<td><?php _e('Create Time');?></td>
			<td><?php _e('Operation');?></td>
		</tr>
		<?php
		foreach( $list as $key => $value )
		{	
		?>
		<tr>
			<td><?php echo $value['fname'];?></td>
			<td><?php echo round( $value['fsize']/1024, 2 );?> KB</td>
			<td><?php echo date( 'Y-m-d H:i', $value['ftime'] );?></td>
			<td><a href="<?php echo Html::uriquery('mod_backup', 'admin_load',array('_fid'=>$key)); ?>"><img style="border:none;" title="<?php _e('Downloads');?>" src="<?php echo P_TPL_WEB;?>/images/download.gif"></a></td>
		</tr>
		<?php
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
$running_msg = __('正在处理...');
$custom_js = <<<JS
$("#adminartfrm_stat").css({"display":"block"});
$("#adminartfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;
JS;
$backup_form->addCustValidationJs($custom_js);
$backup_form->writeValidateJs();

$import_form = new Form('index.php', 'importform', 'check_login_info1');
$import_form->setEncType('multipart/form-data');
$import_form->p_open('mod_backup', 'import');
?>
<table id="importform_table" class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
	<tbody>
		<tr>
			<td width="10%"><?php _e('Import File');?>:</td>
    		<td width="25%">
    		<?php
            echo Html::input('file', 'import_file', '', 
                '', $import_form);
            ?>
            </td>
            <td><?php echo Html::input('submit', 'submit', __('Import'));?></td>
        </tr>
	</tbody>
</table>
<?php
$import_form->close();
$import_form->writeValidateJs();

// 4/8/2010 Jane Add >>
$act = & ParamHolder::get('_a', array());
if ( $act == 'import' ) {
	$import_msg = __('import file complete!');
	echo '<script language="javascript">alert("',$import_msg,'");</script>';
}
// 4/8/2010 Jane Add <<
?>
