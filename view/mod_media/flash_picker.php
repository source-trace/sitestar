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
		var rel_path = "upload/flash/" + fname;
		var parent_doc = window.parent.document;
	    var parent_flash_id = "<?php echo Html::escSpecialChars($flvid); ?>";
	    parent_doc.getElementById(parent_flash_id).value = rel_path;

	    alert("<?php _e("Add flash success");?>");
	    window.parent.tb_remove();
	}
}
//-->
</script>
<div class="picker_body">
<form name="frmpicker" enctype="multipart/form-data" method="post">
	<table cellspacing="1" class="list_table">
		<tbody>
			<tr>
				<td>
				<?php _e("Select Flash");?>：<input type="file" name="localfile" onchange="document.frmpicker.submit()" />
				</td>
			</tr>
		</tbody>
	</table>
</form>
</div>
