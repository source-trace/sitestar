<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Show category list recursively
 * 
 */
function showCategoryList(&$category_tree, $type_text, $level = 0) {
	$row_idx = 1;
	
    foreach ($category_tree as $category) {
?>
        <tr class="row_style_<?php echo $row_idx; ?>">
			<td></td>
        	<td class="left"><?php echo str_repeat('&nbsp;--', $level).'&nbsp;'.$category->name; ?></td>
        	<td>
        	       <a href="#" onclick="select_for_menu_item('<?php echo $type_text.' - '.$category->name; ?>', '<?php echo $category->id; ?>'); return false;">
        	           <?php _e('Select'); ?></a>
        	</td>
        </tr>
<?php
        $row_idx = 1 - $row_idx;
        
        if (sizeof($category->slaves['ProductCategory']) > 0) {
            $level++;
            showCategoryList($category->slaves['ProductCategory'], $type_text, $level);
            $level--;
        }
    }
}
?>
<script type="text/javascript" language="javascript">
<!--
function on_failure(response) {
    document.getElementById("adminmis_cate_p_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function on_quick_add_cate_p_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminmis_cate_p_stat");
    if (o_result.result == "ERROR") {
        $("#new_cate_p").val("");
        
        stat.innerHTML = o_result.errmsg;
        stat.style.display = "block";
        return false;
    } else if (o_result.result == "OK") {
        var new_id = o_result.id;
        var new_text = $("#new_cate_p").val();
        select_for_menu_item('<?php echo $type_text.' - '; ?>' + new_text, new_id);
    } else {
        return on_failure(response);
    }
}

function add_cate_p() {
    _ajax_request("mod_category_p", 
        "admin_quick_create", 
        {
            name: $("#new_cate_p").val(),
            parent: $("#parent_cate_p").val(),
            locale: "<?php echo $mod_locale; ?>"
        }, 
        on_quick_add_cate_p_success, 
        on_failure);
}
//-->
</script>
<style type="text/css">
@import "template/css/popup.css";
</style>
<!--div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php  //_e('Please select product category to list'); ?></div></td>
			</tr>
		</tbody>
	</table>
</div-->
<!--div class="space"></div-->
<div class="status_bar">
	<span id="adminmis_cate_p_stat" class="status" style="display:none;"></span>
</div>
<!--table cellspacing="0" class="form_table">
    <tbody>
        <tr>
            <td class="label"><?php //_e('New Category'); ?></td>
            <td class="entry" width="200">
            <?php
//            echo Html::select('parent_cate_p', 
//                $select_categories);
            ?>
            </td>
            <td class="entry" width="140"><?php //echo Html::input('text', 'new_cate_p'); ?></td>
            <td class="entry" width="100"><a href="#" onclick="add_cate_p(); return false;"><?php //_e('Add Category'); ?></a></td>
            <td></td>
        </tr>
    </tbody>
</table-->
<!--div class="space"></div-->
<table cellspacing="0" class="list_table" id="admin_category_p_list">
	<thead>
		<tr>
			<th width="20"></th>
            <th><?php _e('Product Categories'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    		<a href="#" title="" onclick="parent.$('#showContents').show();parent.$('#showContents1').remove();" style="color:#4372b0;font-weight:bold;"><?php _e('Back'); ?></a></th>
            <th width="48"></th>
        </tr>
    </thead>
    <tbody>
        <tr class="row_style_0">
			<td></td>
        	<td class="left"><?php _e('All Products'); ?></td>
        	<td>
        	       <a href="#" onclick="select_for_menu_item('<?php echo $type_text.' - '.__('All Products'); ?>', '0'); return false;"><?php _e('Select'); ?></a>
        	</td>
        </tr>
    <?php if (sizeof($categories) > 0) {
        showCategoryList($categories, $type_text);
    } ?>
    </tbody>
</table>
