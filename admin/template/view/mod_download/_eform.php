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
</SCRIPT>
<div class="status_bar">
<?php if (Notice::get('mod_download/msg')) { ?>
	<span id="admindownfrm_stat" class="status"><?php echo Notice::get('mod_download/msg'); ?></span>
<?php } ?>
</div>
<div class="space"></div>
<?php
$download_form = new Form('index.php', 'downloadform', 'check_download_info');
$download_form->setEncType('multipart/form-data');
$download_form->p_open('mod_download', $next_action, '_ajax');
?>
<table id="downloadform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'download[id]', $curr_download->id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="10%"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText($curr_download->s_locale?$curr_download->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'download[s_locale]', 
           		$curr_download->s_locale?$curr_download->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
		
		<?php if(!strstr($curr_download->name,'http://')){ ?>
	    <tr>
            <td class="label"><?php _e('File'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('file', 'download_file', '', 
                '', $download_form);
            ?>
			<BR />
			<?php _e('Supported file format'); ?>:<?php echo FILE_ALLOW_EXT;?>
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
			<input type="hidden" name="download[name]" value="<?php echo  $curr_download->name ?>" />
        </tr>
		<?php }else{ ?>
		<input type="hidden" name="d_type" value="2" />
		<tr id="t_link">
            <td class="label"><?php _e('Link Addr'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'url', $curr_download->name, 
                '', $download_form);echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            ?>
            </td>
        </tr>
		<?php } ?>
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
            echo Html::textarea('download[description]', $curr_download->description, 
                'rows="8" cols="108" class="textinput" style="width:450px;"', $download_form, 'RequiredTextbox', 
                __('Please input description!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_download->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$download_form->close();
$download_form->writeValidateJs();
?>