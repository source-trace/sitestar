<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$backparams='';
if(preg_match('/goto=[^&]+/', $_SERVER['HTTP_REFERER'], $matches)){
	$backparams='&'.$matches[0];
}
?>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("admincatepfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["categorypform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
//	    window.location.href = o_result.forward;
		window.location.href ="index.php?_m=mod_category_p&_a=admin_list<?php  echo $backparams;?>";
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["categorypform"].reset();
    
    document.getElementById("admincatepfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="status_bar">
	<span id="admincatepfrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$ca_p_form = new Form('index.php', 'categorypform', 'check_category_p_info');
$ca_p_form->p_open('mod_category_p', $next_action, '_ajax');
?>
<table id="categorypform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_category_p_id='';
			if(isset($curr_category_p->id)){
				$curr_category_p_id=$curr_category_p->id;
			}
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'cap[id]', $curr_category_p_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="12%"><?php _e('Name'); ?></td>
            <td class="entry" width="88%">
            <?php
			$curr_category_p_name='';
			if(isset($curr_category_p->name)){
				$curr_category_p_name=$curr_category_p->name;
			}
            echo Html::input('text', 'cap[name]', $curr_category_p_name, 
                'class="textinput"', $ca_p_form, 'RequiredTextbox', 
                __('Please input category name!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Superior Category'); ?></td>
            <td class="entry">
            <?php
			$curr_category_p_product_category_id='';
			if(isset($curr_category_p->product_category_id)){
				$curr_category_p_product_category_id=$curr_category_p->product_category_id;
			}
            echo Html::select('cap[product_category_id]', 
            	$select_categories, 
            	$curr_category_p_product_category_id, 'class="textselect"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText((isset($curr_category_p->s_locale)&&$curr_category_p->s_locale)?$curr_category_p->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'cap[s_locale]', 
           		(isset($curr_category_p->s_locale)&&$curr_category_p->s_locale)?$curr_category_p->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'cap[published]', '1', 
                Toolkit::switchText($curr_category_p->published, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Publish'); ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
			$curr_category_p_for_roles='';
			if(isset($curr_category_p->for_roles)){
				$curr_category_p_for_roles=$curr_category_p->for_roles;
			}
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_category_p_for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$ca_p_form->close();
$running_msg = __('Saving category...');
$custom_js = <<<JS
$("#admincatepfrm_stat").css({"display":"block"});
$("#admincatepfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$ca_p_form->addCustValidationJs($custom_js);
$ca_p_form->writeValidateJs();
?>
