<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<SCRIPT type="text/javascript" LANGUAGE="JavaScript">
<!--
	function on_failure(response) {
		document.forms["downloadform"].reset();
		
		document.getElementById("admindownfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
		return false;
	}
	function on_quick_add_cate_a_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("admindownfrm_stat");
    if (o_result.result == "ERROR") {
        $("#new_cate_D").val("");
        
        stat.innerHTML = o_result.errmsg;
        stat.style.display = "block";
        return false;
    } else if (o_result.result == "OK") {
        var cate_select = document.getElementById("download_download_category_id_");
        var after_idx = cate_select.selectedIndex;
        var new_id = o_result.id;
        var new_text = $("#new_cate_d").val();
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

function add_cate_d() {
    _ajax_request("mod_category_d", 
        "admin_quick_create", 
        {
            name: $("#new_cate_d").val(),
            parent: $("#download_download_category_id_").val(),
            locale: $("#download_s_locale_").val()
        }, 
        on_quick_add_cate_a_success, 
        on_failure);
}
//-->

$(function(){
	$("#d_type").click(function(){
		$("#t_file").show();
		$("#t_link").hide();
	});
	$("#d_type2").click(function(){
		$("#t_file").hide();
		$("#t_link").show();
	})

});
function check_download_info(){
	var val=$('input:radio[name="d_type"]:checked').val();
	if(val==1){
		if($("#download_file").val()==''){
			alert("<?php _e('Please select a download file to upload!');?>");
			return false;
		}
	}else if(val==2){
		if($("#url").val()==''){
			alert("<?php _e('Please input download address!');?>");
			return false;
		}
	}else{
		
	}
	if($("#download_description_").val()==''){
		alert("<?php _e('Please input description!');?>");
		return false;
	}
	return true;
}
</SCRIPT>
<div class="status_bar">
<?php if (Notice::get('mod_download/msg')) { ?>
	<span id="admindownfrm_stat" class="status"><?php echo Notice::get('mod_download/msg'); ?></span>
<?php } ?>
</div>
<div class="space"></div>
<form name="downloadform" id="downloadform" enctype="multipart/form-data" onsubmit="javascript:return check_download_info();" action="index.php" method="post">
<input type="hidden" name="_m" id="_m" value="mod_download"  />
<input type="hidden" name="_a" id="_a" value="admin_create"  />
<input type="hidden" name="_r" id="_r" value="_page"  />
<table id="downloadform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'download[id]', '');
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="10%"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText($mod_locale, Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'download[s_locale]', $mod_locale);
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Download type'); ?></td>
            <td class="entry">
            <input type="radio" name="d_type" value="1" id="d_type" checked="checked" /><?php _e('Local upload'); ?>
			<input type="radio" name="d_type" value="2" id="d_type2" /><?php _e('Link file'); ?>
            </td>
        </tr>
        <tr id="t_file">
            <td class="label"><?php _e('File'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('file', 'download_file', '', 
                '', $download_form);echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo FILE_ALLOW_EXT;?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
		<tr id="t_link" style="display:none;">
            <td class="label"><?php _e('Link Addr'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'url', '', 
                '', $download_form);echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Category'); ?></td>
            <td class="entry">
            <?php
			$curr_crticle_id ='';
			if(isset($curr_download->download_category_id)){
				$curr_crticle_id=$curr_download->download_category_id;
			}
            echo Html::select('download[download_category_id]', 
                $select_categories, 
                $curr_crticle_id, 'class="textselect"');
            ?>
		  <a href="#" onclick="add_cate_d(); return false;"><?php _e('Add Category'); ?></a>
            &nbsp;<?php echo Html::input('text', 'new_cate_d', '', 'class="textinput" style="width:190px;"'); ?>
		<a href="<?php echo Html::uriquery('mod_category_d', 'admin_list'); ?>"><?php _e('Manage Categories'); ?></a>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Description'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('download[description]', '', 
                'rows="8" cols="76" class="textinput" style="width:450px;"', $download_form);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1');
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
</form>