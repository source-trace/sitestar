<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
ob_start();
if($json == 'ok')
{
	if($flag == 2)
	{
	echo <<<JS
<script type="text/javascript">
parent.window.location.reload();
</script>
JS;
	}else{
		$uri_ = $_SERVER['PHP_SELF'];
		echo "<script language='javascript'> 
		parent.window.location.href='".$uri_."';
		</script>"; 			
	}
}
else
{
	echo $json;
}
?>