<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Show category list recursively
 * 
 */
function showCategoryList(&$category_tree, $level = 0) {
	$row_idx = 0;
	
    foreach ($category_tree as $category) {
?>
        <tr class="row_style_<?php echo $row_idx; ?>">
			<td><?php echo Html::input('checkbox', 'category_p', $category->id); ?></td>
        	<td style="text-align:left;padding-left:20px;"><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level).$category->name; ?></td>
        	<td>
        		<a href="#" onclick="move('<?php echo $category->id; ?>', '<?php echo $category->siblings['prev']; ?>');return false;" title="<?php _e('Move Up'); ?>"><img style="border:none;" src="<?php echo P_TPL_WEB; ?>/images/up.gif" alt="<?php _e('Move Up');?>"/></a>
        		<a href="#" onclick="move('<?php echo $category->id; ?>', '<?php echo $category->siblings['next']; ?>');return false;" title="<?php _e('Move Down'); ?>"><img style="border:none;" src="<?php echo P_TPL_WEB; ?>/images/down.gif" alt="<?php _e('Move Down');?>"/></a>
        	</td>
        	<td><?php echo Toolkit::validateYesOrNo($category->published,$category->id,Html::uriquery('mod_category_p', 'admin_pic', array('_id' => $category->id)));?></td>
        	<td>
        		<a href="<?php echo Html::uriquery('mod_category_p', 'admin_edit', array('cap_id' => $category->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" alt="<?php _e('Edit'); ?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
        	</td>
        	<td>
        		<a href="#" onclick="delete_category_p(<?php echo $category->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete'); ?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
        	</td>
        </tr>
<?php
        $row_idx = 1 - $row_idx;
        
        if (sizeof($category->slaves['ProductCategory']) > 0) {
            $level++;
            showCategoryList($category->slaves['ProductCategory'], $level);
            $level--;
        }
    }
}
?>
<script type="text/javascript" language="javascript">
<!--
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("admincateplst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_del_failure(response) {
    document.getElementById("admincateplst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_category_p(cap_id) {
	if (confirm("<?php _e('Delete the selected category?'); ?>")) {
	    var stat = document.getElementById("admincateplst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected category...'); ?>";
		_ajax_request("mod_category_p", 
			"admin_delete", 
	        {
	            cap_id:cap_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}

function on_mov_success(response) {
    on_del_success(response);
}

function on_mov_failure(response) {
    on_del_failure(response);
}

function move(cap_id, sib_cap_id) {
	if (sib_cap_id == "0") {
	    return false;
	}
	
	var stat = document.getElementById("admincateplst_stat");
	stat.style.display = "block";
	stat.innerHTML = "<?php _e('Moving category...'); ?>";
	_ajax_request("mod_category_p", 
		"admin_move", 
	    {
	        cap_id:cap_id,
	        sib_cap_id:sib_cap_id
	    }, 
		on_mov_success, 
		on_mov_failure);
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("category_p");
		if(el.checked){
			for(i=0;i<arr.length;i++){arr[i].checked=true;}
		}else{
			for(i=0;i<arr.length;i++){arr[i].checked=false;}
		}
	}catch(e){
		return false;
	}
}
function delete_category(){
	var arr = document.getElementsByName("category_p");
	var str="";
	for (var i = 0; i < arr.length; i++){
		var e = arr[i];
		if (e.checked){
			str = e.value + "_" + str;
		}
	}
	if(str.length < 1) {
		alert("<?php _e('Please selected!'); ?>");
	} else {
		delete_category_p(str);
	}
}
//-->
</script>
<div class="status_bar">
	<span id="admincateplst_stat" class="status" style="display:none;"></span>
</div>
<ul>
	<li><a class="iconfl" href="<?php echo Html::uriquery('mod_category_p', 'admin_add'); ?>" title=""><?php _e('New Category'); ?></a></li>
	<li><a class="iconsc" href="javascript:void(0)" onclick="delete_category();"><?php _e('Delete Selected'); ?></a></li>
	<?php
	if (trim(ParamHolder::get('goto',''))) {
	?>
	<li><a class="iconbk" href="<?php echo Html::uriquery('mod_product', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a></li>
	<?php }?>
	<li style="margin-top:14px;"><?php include_once(P_TPL.'/common/language_switch.php'); ?></li>
</ul>

<table class="form_table_list" id="admin_category_p_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="margin-top:0;line-height:24px;">
	<thead>
		<tr>
			<th width="11%"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th width="32%"><?php _e('Name'); ?></th>
            <th width="13%"><?php _e('Order'); ?></th>
            <th width="13%"><?php _e('Publish'); ?></th>
            <th width="13%"><?php _e('Edit'); ?></th>
            <th width="13%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($categories) > 0) {
    	showCategoryList($categories);
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="6"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
