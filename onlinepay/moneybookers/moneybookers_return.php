<?php
define('IN_CONTEXT', 1);
if($_REQUEST['st'] == 'Pending')
{
	//TODO:进一步处理:	
	echo "Auto-Redirecting...";
	sleep(5);
	@header("Location:http://{$_SERVER['HTTP_HOST']}/index.php?_m=mod_order&_a=userlistorder");
	die;
}
else
{
	die('Unknown Order!');
}
?>