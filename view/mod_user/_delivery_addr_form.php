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
//-->
</script>
<!--<div class="content_title">
	<h3><?php _e($content_title); ?></h3>
</div>
<?php if ($success_action != 'close') { ?>
<div class="space"></div>
<div class="content_toolbar">
	<a href="<?php echo Html::uriquery2('mod_user', 'deliveryaddrlst'); ?>" title=""><?php _e('Back'); ?></a>
</div>
<?php } else { ?>
<div class="space4"></div>
<?php } ?>-->
<div class="space"></div>
<div class="status_bar">
	<span id="deliveryaddrfrm_stat" class="status" style="background-color:#FFF;padding:0;border:none;display:none;"></span>
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
            /*if ($success_action == 'close') {
                echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.parent.tb_remove();"');
            } else {
                echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            }*/
			$curr_addr_id='';
			if(isset($curr_addr->id)){
				$curr_addr_id=$curr_addr->id;
			}
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'addrinfo[id]', $curr_addr_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
	    <tr>
            <td class="label"><?php _e('Customer Name'); ?></td>
            <td class="entry">
            <?php
			$curr_addr_reciever_name='';
			if(isset($curr_addr->reciever_name)){
				$curr_addr_reciever_name=$curr_addr->reciever_name;
			}
            echo Html::input('text', 'addrinfo[reciever_name]', $curr_addr_reciever_name, 
                'class="textinput"', $deliveryaddr_form, 'RequiredTextbox', 
                __('Please input customer name!'));
            ?>
            </td>
        </tr>
        <tr <?php  if (trim(SessionHolder::get('_LOCALE')) != 'zh_CN'){echo 'style="display:none;"';} ?>>
            <td class="label"><?php _e('Province, City'); ?></td>
            <td class="entry">
                <select name="addrinfo[prov_id]" id="addrinfo[prov_id]" onchange="clearSelect('addrinfo[dist_id]');fillCity('addrinfo[city_id]', this.options[this.selectedIndex].value, '');">
                </select>
                <select name="addrinfo[city_id]" id="addrinfo[city_id]" onchange="fillDist('addrinfo[dist_id]', this.options[this.selectedIndex].value, '');">
                </select>
                <select name="addrinfo[dist_id]" id="addrinfo[dist_id]">
                </select>
            <?php
            if (trim(SessionHolder::get('_LOCALE')) == 'zh_CN'){
            $deliveryaddr_form->genRequiredSelect('addrinfo[prov_id]', __('Please select province!')) ;
            $deliveryaddr_form->genRequiredSelect('addrinfo[city_id]', __('Please select city!')) ;
            $deliveryaddr_form->genRequiredSelect('addrinfo[dist_id]', __('Please select disctrict!')) ;
            } ?>
            </td>
        </tr>
	    <tr valign="top">
            <td class="label"><?php _e('Address'); ?></td>
            <td class="entry">
            <?php
			$curr_addr_detailed_addr='';
			if(isset($curr_addr->detailed_addr)){
				$curr_addr_detailed_addr=$curr_addr->detailed_addr;
			}
            echo Html::textarea('addrinfo[detailed_addr]', $curr_addr_detailed_addr, 
                'cols="40" rows="3"', $deliveryaddr_form, 'RequiredTextbox', 
                __('Please input address!'));
            ?>
            </td>
        </tr>
	    <tr <?php  if (trim(SessionHolder::get('_LOCALE')) != 'zh_CN'){echo 'style="display:none;"';} ?>>
            <td class="label"><?php _e('Postal'); ?></td>
            <td class="entry">
            <?php
			$curr_addr_postal='';
			if(isset($curr_addr->postal)){
				$curr_addr_postal=$curr_addr->postal;
			}
            echo Html::input('text', 'addrinfo[postal]', $curr_addr_postal, 
                'class="textinput"');
            ?>
            </td>
        </tr>
	    <tr>
            <td class="label"><?php _e('Phone'); ?></td>
            <td class="entry">
            <?php
			$curr_addr_phone='';
			if(isset($curr_addr->phone)){
				$curr_addr_phone=$curr_addr->phone;
			}
            echo Html::input('text', 'addrinfo[phone]', $curr_addr_phone, 
                'class="textinput"');
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

$curr_addr_prov_id='';
if(isset($curr_addr->prov_id)){
	$curr_addr_prov_id=$curr_addr->prov_id;
}
?>
<script type="text/javascript" language="javascript">
<!--
fillProvince("addrinfo[prov_id]", "<?php echo $curr_addr_prov_id; ?>")
<?php if ($curr_addr_prov_id) { ?>
fillCity("addrinfo[city_id]", "<?php echo $curr_addr_prov_id ; ?>", "<?php echo $curr_addr->city_id ; ?>");
<?php } ?>
<?php if ($curr_addr_prov_id && $curr_addr->dist_id) { ?>
fillDist("addrinfo[dist_id]", "<?php echo $curr_addr->city_id ; ?>", "<?php echo $curr_addr->dist_id ; ?>");
<?php } ?>
//-->
</script>
