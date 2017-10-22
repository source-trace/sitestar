<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_failure(response) {
    document.forms["productform"].reset();
    
    document.getElementById("adminprdfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function on_quick_add_cate_p_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminprdfrm_stat");
    if (o_result.result == "ERROR") {
        $("#new_cate_p").val("");
        
        stat.innerHTML = o_result.errmsg;
        stat.style.display = "block";
        return false;
    } else if (o_result.result == "OK") {
        var cate_select = document.getElementById("prd_product_category_id_");
        var after_idx = cate_select.selectedIndex;
        var new_id = o_result.id;
        var new_text = $("#new_cate_p").val();
        var parent_id = cate_select.options[after_idx].value;
        var level_count = cate_select.options[after_idx].text.count("--");

        for (var i = cate_select.length - 1; i > after_idx; i--) {
            cate_select.options[i + 1] = new Option();
            cate_select.options[i + 1].value = cate_select.options[i].value;
            cate_select.options[i + 1].text = cate_select.options[i].text;
        }
        if (typeof(cate_select.options[i + 1]) == "undefined") {
            cate_select.options[i + 1] = new Option();
        }
        cate_select.options[i + 1].value = new_id;
        if (parent_id == "0") {
            cate_select.options[i + 1].text = " " + new_text;
        } else {
            cate_select.options[i + 1].text = " " + "-- ".repeat(level_count + 1) + new_text;
        }
        cate_select.options[i + 1].selected = "selected";
    } else {
        return on_failure(response);
    }
}

function on_remove_extpic_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminprdfrm_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        stat.style.display = "block";
        return false;
    } else if (o_result.result == "OK") {
        var img_id = "#exp_pic_" + o_result.id;
        $(img_id).hide();
    } else {
        return on_failure(response);
    }
}

function add_cate_p() {
    _ajax_request("mod_category_p", 
        "admin_quick_create", 
        {
            name: $("#new_cate_p").val(),
            parent: $("#prd_product_category_id_").val(),
            locale: $("#prd_s_locale_").val()
        }, 
        on_quick_add_cate_p_success, 
        on_failure);
}

function add_pic_upload() {
    $("#prd_gala_pic_uploader").append("<input type=\"file\" name=\"prd_extpic[]\" /><br />");
}

function remove_extpic(pic_id) {
    if (confirm("<?php _e('Delete selected picture?'); ?>")) {
        _ajax_request("mod_product", 
            "admin_delete_extpic", 
            { p_id: pic_id }, 
            on_remove_extpic_success, 
            on_failure);
    }
}
//-->
</script>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e($content_title); ?></div></td>
				<td><a href="#" title="" onclick="window.history.go(-1); return false;"><?php _e('Back'); ?></a></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="space"></div>
<div class="status_bar">
<?php if (Notice::get('mod_product/msg')) { ?>
	<span id="adminprdfrm_stat" class="status"><?php echo Notice::get('mod_product/msg'); ?></span>
<?php } else { ?>
    <span id="adminprdfrm_stat" class="status" style="display:none;"></span>
<?php } ?>
</div>
<div class="space"></div>
<?php
$prd_form = new Form('index.php', 'productform', 'check_product_info');
$prd_form->setEncType('multipart/form-data');
$prd_form->p_open('mod_product', $next_action);
?>
<table id="productform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'prd[id]', $curr_product->id);
            echo Html::input('hidden', 'txt', $type_text);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText($curr_product->s_locale?$curr_product->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'prd[s_locale]', 
           		$curr_product->s_locale?$curr_product->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Category'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('prd[product_category_id]', 
            	$select_categories, 
            	$curr_product->product_category_id);
            ?>
            &nbsp;<?php echo Html::input('text', 'new_cate_p'); ?>
            &nbsp;<a href="#" onclick="add_cate_p(); return false;"><?php _e('Add Category'); ?></a>
            &nbsp;<a href="<?php echo Html::uriquery('mod_category_p', 'admin_list'); ?>" title=""><?php _e('Manage Categories'); ?></a>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Name'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'prd[name]', $curr_product->name, 
                '', $prd_form, 'RequiredTextbox', 
                __('Please input name!'));
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Introduction'); ?></td>
            <td class="entry">
			<?php
            echo Html::textarea('prd[introduction]', $curr_product->introduction, 'rows="8" cols="108"', 
            	$prd_form, 'RequiredTextbox', __('Please input introduction!'))
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Description'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('prd[description]', $curr_product->description, 'rows="24" cols="108"')."\n";
            $o_fck = new RichTextbox('prd[description]');

            $o_fck->height = 420;
            echo $o_fck->create();
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Full Image'); ?></td>
            <td class="entry">
            <?php
			if($p_id) {
			?>
			<img src="../<?php echo $curr_product->feature_img?>"><br />
			<?php
				echo Html::input('file', 'prd_file', '', 
                '', $prd_form);
			}else{
				echo Html::input('file', 'prd_file', '', 
                '', $prd_form, 'RequiredTextbox', 
                __('Please select a product big image to upload!'));
			}
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Thumbnail'); ?></td>
            <td class="entry">
            <?php
			if($p_id) {
			?>
			<img src="../<?php echo $curr_product->feature_smallimg?>"><br />
			<?php
				echo Html::input('file', 'prd_small_file', '', 
                '', $prd_form);
			}else{
				echo Html::input('file', 'prd_small_file', '', 
                '', $prd_form, 'RequiredTextbox', 
                __('Please select a product small image to upload!'));
			}
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('More Pics'); ?></td>
            <td class="entry">
                <?php _e("Click picture to remove from product gallary!"); ?><br />
                <div id="prd_gala_pics">
            <?php
            // Now get all extra pictures
            if ($curr_product) {
                $curr_product->loadRelatedObjects(REL_CHILDREN, array('ProductPic'));
                $ext_pics = $curr_product->slaves['ProductPic'];
                if (sizeof($ext_pics) > 0) {
                    foreach ($ext_pics as $pic) {
            ?>
                <div  onclick="remove_extpic(<?php echo $pic->id; ?>);"  style="width:auto;height:auto;">
                <img class="prd_gala_pic" src="../<?php echo $pic->pic; ?>" id="exp_pic_<?php echo $pic->id; ?>" />
                </div>
            <?php
                    }
                }
            }
            ?>
                </div>
                <div id="prd_gala_pic_uploader">
                    <input type="file" name="prd_extpic[]" /><br />
                    <input type="file" name="prd_extpic[]" /><br />
                    <input type="file" name="prd_extpic[]" /><br />
                </div>
                <a href="#" onclick="add_pic_upload();return false;"><?php _e('More'); ?></a><br />
                <?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?><br />
                <?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
        <?php if (intval(EZSITE_LEVEL) > 1) { ?>
        <tr>
            <td class="label"><?php _e('Price'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'prd[price]', $curr_product->price?$curr_product->price:'0.00', 
                'size="10"', $prd_form, 'RequiredTextbox', 
                __('Please input price!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Discount Price'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'prd[discount_price]', $curr_product->discount_price?$curr_product->discount_price:'0.00', 
                'size="10"', $prd_form);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Delivery Fee'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'prd[delivery_fee]', $curr_product->delivery_fee?$curr_product->delivery_fee:'0.00', 
                'size="10"', $prd_form, 'RequiredTextbox', 
                __('Please input delivery fee!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'prd[online_orderable]', '1', 
                Toolkit::switchText($curr_product->online_orderable, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Online Orderable'); ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'prd[recommended]', '1', 
                Toolkit::switchText($curr_product->recommended, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Recommend'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_product->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$prd_form->close();
$prd_form->writeValidateJs();
?>
