<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("admincateafrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["categoryaform"].reset();
        
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
    document.forms["categoryaform"].reset();
    
    document.getElementById("admincateafrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="status_bar">
	<span id="admincateafrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$ca_d_form = new Form('index.php', 'categoryaform', 'check_category_d_info');
$ca_d_form->p_open('mod_category_d', $next_action, '_ajax');
?>
<table id="categoryaform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_category_d_id='';
			if(isset($curr_category_d->id)){
				$curr_category_d_id=$curr_category_d->id;
			}
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'cad[id]', $curr_category_d_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="12%"><?php _e('Name'); ?></td>
            <td class="entry" width="88%">
            <?php
			$curr_category_d_name='';
			if(isset($curr_category_d->name)){
				$curr_category_d_name=$curr_category_d->name;
			}
            echo Html::input('text', 'cad[name]', $curr_category_d_name, 
                'class="textinput"', $ca_d_form, 'RequiredTextbox', 
                __('Please input category name!'));
            ?>
            </td>
        </tr>
       
        <tr>
            <td class="label"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText((isset($curr_category_d->s_locale)&&$curr_category_d->s_locale)?$curr_category_d->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'cad[s_locale]', 
           		(isset($curr_category_d->s_locale)&&$curr_category_d->s_locale)?$curr_category_d->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'cad[published]', '1', 
                Toolkit::switchText($curr_category_d->published, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Publish'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
			$curr_category_d_for_roles='';
			if(isset($curr_category_d->for_roles)){
				$curr_category_d_for_roles=$curr_category_d->for_roles;
			}
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_category_d_for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$ca_d_form->close();
$running_msg = __('Saving category...');
$custom_js = <<<JS
$("#admincateafrm_stat").css({"display":"block"});
$("#admincateafrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$ca_d_form->addCustValidationJs($custom_js);
$ca_d_form->writeValidateJs();
?>
