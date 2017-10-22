<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="upFolder"><?php _e('Upload path');?>：
<select name="curdir" id="gtcurdir" onchange="uploadifyset(this.value)">
<?php
foreach($dirs as $dir) {
?>
<option value="<?php echo $dir;?>"><?php echo $dir;?></option>
<?php }?></select>&nbsp;<a href="#" style="color:#FF0000;" onclick="addSort(this)"><?php _e('New Folder');?></a>&nbsp;<span style="display:none;"><input autocomplete="off" type="text" onblur="hidSort(this)" onkeyup="value=value.replace(/[^\w\)\(\- ]/g,'')" size="10" /><input type="button" onclick="newDir(this)" name="btnSubmit" value=" <?php _e('New');?> " /></span></div>
<div id="fileQueue"></div>
<div class="upBtn"><input type="file" name="uploadify" id="uploadify" />
<a class="upSubmit" href="javascript:void(0);" onclick="javascript:$('#uploadify').uploadifyUpload();"><?php _e('Upload Files');?></a>
<a class="upCancel" href="javascript:void(0);" onclick="javascript:$('#uploadify').uploadifyClearQueue();"><?php _e('Cancel All Uploads');?></a>
<a class="upCancel" href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>$ftype)); ?>"><?php _e('Back');?></a></div>