<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

?>
<?php 
if(empty($error)){
?>
<script type="text/javascript">
$(document).ready(function(){
	parent.window.location.reload();	
});
</script>
<?php } else {?>
<script type="text/javascript">
$(document).ready(function(){
	alert("<?php echo $error;?>");
	parent.window.location.reload();
});
</script>
<?php	
}
?>