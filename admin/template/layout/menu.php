<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="fl">
	<?php 
		$module = R_MOD;
		$action = R_ACT;
		$o_adminMenuCategory = new AdminMenuCategory();
		$module = ($module == 'mod_category_a') ? 'mod_article' : $module;
		$module = ($module == 'mod_category_p') ? 'mod_product' : $module;
		$adminMenuCategory_items = $o_adminMenuCategory->findAll("module='$module' AND action='$action'");
		if(empty($adminMenuCategory_items[0]->category_name)) {
			$module = ($module == 'mod_static') ? 'mod_menu_item' : $module;
			$o_adminMenuItem = new AdminMenuItem();
			$adminMenuItem_items = $o_adminMenuItem->findAll("module='$module'");
			if(!empty($adminMenuItem_items)) {
				$adminMenuCategory_items1 = $o_adminMenuCategory->findAll("id={$adminMenuItem_items[0]->category_id}");
				$str1 = "<h1><span class='icon2'>".__($adminMenuCategory_items1[0]->category_name)."</span></h1><ul>";
				$adminMenuItem_items1 = $o_adminMenuItem->findAll("category_id={$adminMenuItem_items[0]->category_id} and level <='".EZSITE_LEVEL."' ORDER BY priority");
				if(!(empty($adminMenuItem_items1))) {
					foreach($adminMenuItem_items1 as $v) {
						$str1 .= "<li class='icon3'><a href='".Html::uriquery($v->module, $v->action)."'>".__($v->text)."</a></li>";
					}
				}
				$str1 .= '</ul>';
			}
		} else {
			$category_name = $adminMenuCategory_items[0]->category_name;
			$category_id = $adminMenuCategory_items[0]->id;
			$str1 = "<h1><span class='icon2'>".__($category_name)."</span></h1>";
			$o_adminMenuItem = new AdminMenuItem();
			$adminMenuItem_items = $o_adminMenuItem->findAll("category_id=$category_id ORDER BY priority");
			if(!empty($adminMenuItem_items)) {
				$str1 .= "<ul>";
				foreach($adminMenuItem_items as $v) {
					$str1 .= "<li class='icon3'><a href='".Html::uriquery($v->module, $v->action)."'>".__($v->text)."</a></li>";
				}
				$str1 .= "</ul>";
			} else {
				if($module == 'mod_filemanager' && $action == 'admin_list') {
					$str1 .= "<ul>";
					$str1 .= "<li class='icon3'><a target='_blank' href='browser/tinybrowser.php?type=image'>".__('Image Manager')."</a></li><li class='icon3'><a target='_blank' href='browser/tinybrowser.php?type=flash'>".__('Flash Manager')."</a></li>";
					$str1 .= "</ul>";
				}
			}
		}
		echo $str1;
	?>
</div>