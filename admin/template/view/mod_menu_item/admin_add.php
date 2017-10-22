<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
var popup_win = false;

function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminmifrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["miform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["miform"].reset();
    
    document.getElementById("adminmifrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function show_selected(text) {
    $("#menu_link_content_title").html(text);
    $("#mi_selected_content_").attr("value", 
        $("#menu_link_content_title").html());
}
function set_tmp_id(id) {
    $("#tmp_id").attr("value", id);
}

$(document).ready(function() {
    $(".menu_link_type_selector").click(function() {
            var my_value = $(this).attr("value");
            var value_parts = my_value.split(/\|/);
            if (value_parts[1] == "1") {
                show_iframe_win(
		        'index.php?<?php echo Html::xuriquery('mod_menu_item', 'admin_link_content_select'); ?>&pt=' + value_parts[0] + '&txt=' + value_parts[2], 
		        '', 720, 520);
            } else {
                show_selected(value_parts[2]);
            }
    });
});

//-->
</script>
<div class="status_bar">
	<span id="adminmifrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$mi_form = new Form('index.php', 'miform', 'check_mi_info');
$mi_form->p_open('mod_menu_item', $next_action, '_ajax');
?>
<table id="miform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="4">
            <?php
			echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'mi[id]', $curr_mi->id);
            echo Html::input('hidden', 'mi[menu_item_id]', 0);
            echo Html::input('hidden', 'mi[menu_id]', $menu_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="17%"><?php _e('Language'); ?></td>
            <td class="entry" colspan="3">
            <?php
            echo Toolkit::switchText($curr_mi->s_locale?$curr_mi->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'mi[s_locale]', 
           		$curr_mi->s_locale?$curr_mi->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Name'); ?></td>
            <td colspan="3" class="entry">
            <?php
            echo Html::input('text', 'mi[name]', $curr_mi->name, 
                'class="textinput"', $mi_form, 'RequiredTextbox', 
                __('Please input menu item name!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Column Property');?></td>
            <?php if (sizeof($menus) > 0) {
                foreach ($menus as $section => $list_items) { ?>
                <td width="27%">
                <span class="menu_link_type_section">
                    <h3><?php //_e($section); ?></h3>
                    <!-- TODO:: Add "link_type" field in menu_items table -->
                    <?php echo Html::groupradio('mi[link_type]', $list_items, $curr_mi->link_type, 'class="menu_link_type_selector"', 1); ?>
                </span></td>
            <?php }} ?>
            <?php /* echo Html::input('hidden', 'mi[link]', $curr_mi->link); */ ?>
            <?php echo Html::input('hidden', 'tmp_id'); ?>
        </tr>
        <tr>
            <td class="label"><?php _e('Selected Content'); ?></td>
            <td class="entry" colspan="3">
                <!-- TODO:: Add "selected_content" field in menu_items table -->
                <span id="menu_link_content_title"><?php echo $curr_mi->selected_content; ?></span>
                <?php echo Html::input('hidden', 'mi[selected_content]', $curr_mi->selected_content); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry" colspan="3">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_mi->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$mi_form->close();
$running_msg = __('Saving menu item...');
$custom_js = <<<JS
$("#adminmifrm_stat").css({"display":"block"});
$("#adminmifrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$mi_form->addCustValidationJs($custom_js);
$mi_form->writeValidateJs();
?>
