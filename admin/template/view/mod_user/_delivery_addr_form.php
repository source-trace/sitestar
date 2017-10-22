<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript" src="<?php echo P_SCP; ?>/china_ds_data.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo P_SCP; ?>/china_ds_switch.js"></script>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("deliveryaddrfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["deliveryaddrform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
<?php if ($success_action == 'close') { ?>
        reloadParent();
<?php } else { ?>
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    window.location.href = o_result.forward;
<?php } ?>
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["deliveryaddrform"].reset();
    
    document.getElementById("deliveryaddrfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function backPrv(){
	window.location.href="index.php?_m=mod_friendlink&_a=admin_list";	
}
//-->
</script>
<div class="content_title">
	<h3><?php _e($content_title); ?></h3>
</div>
<?php if ($success_action != 'close') { ?>
<div class="space"></div>
<div class="content_toolbar">
	<a href="<?php echo Html::uriquery('mod_user', 'deliveryaddrlst'); ?>" title=""><?php _e('Back'); ?></a>
</div>
<?php } else { ?>
<div class="space4"></div>
<?php } ?>
<div class="space"></div>
<div class="status_bar">
	<span id="deliveryaddrfrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$deliveryaddr_form = new Form('index.php', 'deliveryaddrform', 'check_addr_info');
$deliveryaddr_form->p_open('mod_user', $next_action, '_ajax');
?>
<table id="deliveryaddrform_table" class="form_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            if ($success_action == 'close') {
                echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.parent.tb_remove();"');
            } else {
                echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv();"');
            }
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'addrinfo[id]', $curr_addr->id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
	    <tr>
            <td class="label"><?php _e('Customer Name'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'addrinfo[reciever_name]', $curr_addr->reciever_name, 
                '', $deliveryaddr_form, 'RequiredTextbox', 
                __('Please input customer name!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Province, City'); ?></td>
            <td class="entry">
                <select name="addrinfo[prov_id]" id="addrinfo[prov_id]" onchange="clearSelect('addrinfo[dist_id]');fillCity('addrinfo[city_id]', this.options[this.selectedIndex].value, '');">
                </select>
                <select name="addrinfo[city_id]" id="addrinfo[city_id]" onchange="fillDist('addrinfo[dist_id]', this.options[this.selectedIndex].value, '');">
                </select>
                <select name="addrinfo[dist_id]" id="addrinfo[dist_id]">
                </select>
            <?php
            $deliveryaddr_form->genRequiredSelect('addrinfo[prov_id]', __('Please select province!')) ;
            $deliveryaddr_form->genRequiredSelect('addrinfo[city_id]', __('Please select city!')) ;
            $deliveryaddr_form->genRequiredSelect('addrinfo[dist_id]', __('Please select disctrict!')) ;
            ?>
            </td>
        </tr>
	    <tr valign="top">
            <td class="label"><?php _e('Address'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('addrinfo[detailed_addr]', $curr_addr->detailed_addr, 
                'cols="48" rows="4"', $deliveryaddr_form, 'RequiredTextbox', 
                __('Please input address!'));
            ?>
            </td>
        </tr>
	    <tr>
            <td class="label"><?php _e('Postal'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'addrinfo[postal]', $curr_addr->postal, 
                'size="8"');
            ?>
            </td>
        </tr>
	    <tr>
            <td class="label"><?php _e('Phone'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'addrinfo[phone]', $curr_addr->phone, 
                'size="14"');
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$deliveryaddr_form->close();
$running_msg = __('Saving delivery address...');
$custom_js = <<<JS
$("#deliveryaddrfrm_stat").css({"display":"block"});
$("#deliveryaddrfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$deliveryaddr_form->addCustValidationJs($custom_js);
$deliveryaddr_form->writeValidateJs();
?>
<script type="text/javascript" language="javascript">
<!--
fillProvince("addrinfo[prov_id]", "<?php echo $curr_addr->prov_id ; ?>")
<?php if ($curr_addr->prov_id) { ?>
fillCity("addrinfo[city_id]", "<?php echo $curr_addr->prov_id ; ?>", "<?php echo $curr_addr->city_id ; ?>");
<?php } ?>
<?php if ($curr_addr->prov_id && $curr_addr->dist_id) { ?>
fillDist("addrinfo[dist_id]", "<?php echo $curr_addr->city_id ; ?>", "<?php echo $curr_addr->dist_id ; ?>");
<?php } ?>
//-->
</script>
