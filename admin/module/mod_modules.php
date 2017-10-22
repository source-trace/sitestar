<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModModules extends Module
{
	public function index()
	{
		header("Location: ../index.php");
		die;
	}
}
?>