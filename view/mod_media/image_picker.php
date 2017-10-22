<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$cols = 4;
?>
<script type="text/javascript" language="javascript">
<!--
window.onload = function() {
	var err = "<?php echo $err; ?>";
	var fname = "<?php echo $fname; ?>";
	var wincls = "<?php echo $wincls; ?>";
	
	if ( err.length > 0 ) {
		alert( err );
		return false;
	}
	if ( wincls == 'OK' ) {
		var rel_path = "upload/image/" + fname;
		var parent_doc = window.parent.document;
	    var parent_image_id = "<?php echo Html::escSpecialChars($imgid); ?>";
	    parent_doc.getElementById(parent_image_id).value = rel_path;
	    
	    parent_doc.getElementById('TB_iframeContent').style.visibility = 'hidden';
	    alert("<?php _e('Add image success');?>");
	    window.parent.tb_remove();
	}
}
//-->
</script>
<?php if (Notice::get('mod_media/msg')) { ?>
	<span id="adminprdfrm_stat" style="color:#FFA32B;"><?php echo Notice::get('mod_media/msg'); ?></span>
<?php } ?>
<div class="picker_body">
<form name="frmpicker" enctype="multipart/form-data" method="post">
	<table cellspacing="1" class="list_table">
		<tbody>
			<tr>
				<td>
				<?php _e("Select Image");?>：<input type="file" name="localfile" onchange="document.frmpicker.submit()" />
				</td>
			</tr>
		</tbody>
	</table>
</form>
</div>
