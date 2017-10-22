<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
<title>System Management</title>
<script type="text/javascript" src="<?php echo P_SCP;?>/mtree/default.js"></script>
<script type="text/javascript" src="<?php echo P_SCP;?>/mtree/drag-tree.js?v=2"></script>
<link rel="stylesheet" href="<?php echo P_SCP;?>/mtree/theme/default.css" type="text/css" />
<script type="text/javascript">
var ajaxObjects = new Array();
function saveMyTree() {
	saveString = treeObj.getNodeOrders();
	var ajaxIndex = ajaxObjects.length;
	ajaxObjects[ajaxIndex] = new sack();
	var url = 'index.php?_m=mod_menu_item&_a=menu_sort&_r=_ajax&tree=' + saveString;
	ajaxObjects[ajaxIndex].requestFile = url;
	ajaxObjects[ajaxIndex].onCompletion = function() { /*alert(ajaxObjects[index].response);*/ };
	ajaxObjects[ajaxIndex].runAJAX();
}
</script>
</head>

<body>
<?php include_once($_content_);?>
</body>
</html>