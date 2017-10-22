<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

function treeMenu(&$mi_tree) {
	$tree = '';
    foreach ($mi_tree as $mi) {    
        if (sizeof($mi->slaves['MenuItem']) > 0) {
			$tree .= '<li id="nd'.$mi->id.'"><a href="#">'.$mi->name."</a>\n<ul>".treeMenu($mi->slaves['MenuItem'])."</ul>\n</li>";
        } else {
        	$tree .= '<li id="nd'.$mi->id.'"><a href="#">'.$mi->name."</a></li>\n";
        }
    }
    return $tree;
}
?>
<style type="text/css">
body{font-size:12px;}
.langbox {margin-bottom:10px;overflow:hidden;height:22px;line-height:22px;}
.langbox #langswform, .langbox span {display:inline-block;float:left;}
.langbox span a {text-decoration:none;font-weight:bold;}
.langbox img {cursor:help;}
.mtree_notice {color:#FF0000;font-size:12px;}
.mtree_notice span {display:inline-block;float:left;}
</style>
<div class="langbox">
	<?php
            if(ACL::isAdminActionHasPermission('mod_menu_item', 'add_page')){
      ?>
	<span><!--a href="<?php echo Html::uriquery('mod_menu_item', 'admin_add', array('menu_id' => ParamHolder::get('menu_id', 0))); ?>" title=""-->
	<a href="<?php echo Html::uriquery('mod_menu_item', 'add_page', array('top_menu_id' => '999')); ?>" title="" style="font-size:12px;"><?php _e('Add Top Column');?></a>&nbsp;&nbsp;</span>
	 <?php
            }
       ?>
	<?php include_once(P_TPL.'/common/language_switch.php'); if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?>
	&nbsp;<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Language note');?>"/>
	<?php } ?>
</div>
<!--div class="status_bar">
	<span id="adminmilst_stat" class="status" style="display:none;"></span>
</div-->
<div class="mtree_notice">
<span><?php _e('Notice: ');?></span>
<span>1.<?php _e('Reorder the menu items by drag and drop.');?><br />
2.<?php _e("Right-click the current menu item to its 'Delete', 'Edit Node', 'Add Node'.");?></span>
</div>
<div style="clear:both;margin-bottom:10px;"></div>
<ul id="menu_tree" class="dhtmlgoodies_tree">
<?php echo treeMenu($menuitems);?>
</ul>
<script type="text/javascript">	
	treeObj = new JSDragDropTree();
	treeObj.setTreeId('menu_tree');
	treeObj.setMessageMaximumDepthReached("<?php _e('Maximum depth reached!');?>");
	<?php if(!ACL::isAdminActionHasPermission('mod_menu_item', 'add_page')){?>
	treeObj.setCustomParam("<?php _e('Delete');?>", "<?php _e('Edit');?>");
	<?php }else {?>
		treeObj.setCustomParam("<?php _e('Delete');?>", "<?php _e('Edit');?>", "<?php _e('Add Child');?>");
	<?php }?>
	/*treeObj.setFileNameRename("index.php?<?php echo Html::xuriquery('mod_menu_item', 'menu_rename', array(), '_ajax');?>");
	treeObj.__renameComplete = function(ajaxIndex) {
		var json = eval("(" + this.ajaxObjects[ajaxIndex].response + ")");
		if (json.result == "ERROR") {
			switch (json.errmsg) {
				case '-1':
					alert("<?php _e('Invalid ID!');?>");
					return false;
					break;
			    case '-2':
			    	alert("<?php _e('Rename failed!');?>");
					return false;
			    	break;
			}
		}
	};*/
	
	treeObj.deleteItem = function(obj1,obj2) {
		var message = "<?php _e('Click OK to delete ');?>" + obj2.innerHTML;
		if(this.hasSubNodes(obj2.parentNode)) message = message + "<?php _e(' and its sub nodes');?>";
		if(confirm(message)){
			if(obj2.parentNode.getAttribute("id")!="menu_tree"){
				treeObj.setFileNameDelete("index.php?<?php echo Html::xuriquery('mod_menu_item', 'menu_del', array(), '_ajax');?>");
				
				this.__deleteItem_step2(obj2.parentNode);// Sending <LI> tag to the __deleteItem_step2 method	
			}
		}
	}
	treeObj.__deleteComplete = function(ajaxIndex,obj) {
		var json = eval("(" + this.ajaxObjects[ajaxIndex].response + ")");
		if (json.result == "ERROR") {
			switch (json.errmsg) {
				case '-1':
					alert("<?php _e('Invalid ID!');?>");
					return false;
					break;
			    case '-2':
			    	alert("<?php _e('Delete failed!');?>");
					return false;
			    	break;
			}
		} else {
			var parentRef = obj.parentNode.parentNode;
			obj.parentNode.removeChild(obj);
			this.__refreshDisplay(parentRef);
		}
	};
	treeObj.setFileNameEdit("<?php echo Html::uriquery('mod_menu_item', 'admin_edit');?>", "<?php echo MOD_REWRITE;?>");
	treeObj.setFileNameAdd("<?php echo Html::uriquery('mod_menu_item', 'admin_addsub');?>", "<?php echo MOD_REWRITE;?>");
	treeObj.initTree();
	treeObj.expandAll();
</script>