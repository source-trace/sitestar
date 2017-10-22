<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<style type="text/css">
.label {width:25%;}
.form_table12 tfoot td {text-align:right;height:40px;margin:0;paddding:0;background:url(../images/b_bg.gif) repeat-x;border:none;}
</style>	
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminpayacctfrm_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "Save Completed.";
		parent.window.location.reload();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.getElementById("adminpayacctfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function choose_payments()
{
	var str;
	$('.form_table12').css('display','none');
	$('#adminpayacctfrm_stat').css('display','none');
	str = $('#choose_payment').val();
	str = 'payacctform_table_' + str;
	$('#'+str).css({display:""});
	
	var ifm1= parent.document.getElementById("showContents");
	var subWeb = parent.document.frames ? parent.document.frames["showContents"].document : ifm1.contentDocument;
	if(ifm1 != null && subWeb != null) {
		$(ifm1).attr('height',250);
		var objDialog1 = $(parent.document.getElementById("showContents")).parent();
		objDialog1.css('height',255);
	}
}
//-->
</script>
<div class="status_bar">
	<span id="adminpayacctfrm_stat" class="status" style="display:none;"></span>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
	<tr>
		<td width="20%"><span class="label" style="padding-left:10px;"><?php _e('Choose Payment Gateway'); ?></span></td>
		<td width="80%">
			<select onchange="choose_payments();" style="width:130px;" class="textselect" id="choose_payment" name="choose_payment">
				<option value="noValue" selected="selected"><?php _e('Please Choose payment');?></option>
				<?php
				$head='';
				$center='';
				$foot='';
				foreach ($providers as $provider) {
					if($provider->id == '7')
					{
						$head.= "<option value='{$provider->name}'>$provider->disp_name</option>";
					}
					elseif($provider->id == '1')
					{
						$head.= "<option value='{$provider->name}'>支付宝双接口账号</option>";
					}
					elseif($provider->id == '4')
					{
						$center.= "<optgroup label='财付通接口'><option value='{$provider->name}'>$provider->disp_name</option>";
					}
					elseif($provider->id == '5')
					{
						$center.= "<option value='{$provider->name}'>$provider->disp_name</option></optgroup>";
					}elseif($provider->id == '8')
					{
						$foot.= "<optgroup label='国外货币'><option value='{$provider->name}'>$provider->disp_name</option></optgroup>";
					}elseif($provider->id == '2'||$provider->id == '3'||$provider->id == '6')
					{
						$head.= "<option value='{$provider->name}'>$provider->disp_name</option>";
					}else{
						$foot.= "<option value='{$provider->name}'>$provider->disp_name</option>";
					}
				}
				echo $head;
				echo $center;
				echo $foot;
				?>
			</select>
		</td>
	</tr>
</table>
<?php
foreach ($providers as $provider) {
    $provider->loadRelatedObjects(REL_CHILDREN, array('PaymentAccount'));
    $curr_payacct = $provider->slaves['PaymentAccount'];
?>

    <?php
	$curr_payacct_id='';
	if(isset($curr_payacct->id)){
		$curr_payacct_id=$curr_payacct->id;
	}
    $payacct_form = new Form('index.php', 'payacctform_'.$curr_payacct_id, 'check_acct_info_'.$curr_payacct_id);
    $payacct_form->p_open('mod_payaccount', 'admin_update', '_ajax');
    ?>
    <table id="payacctform_table_<?php echo $provider->name; ?>" class="form_table12" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;display:none;">
        <tfoot>
            <tr>
                <td colspan="2">
                <?php
        		echo Html::input('reset', 'reset', __('Reset'),'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
				echo Html::input('submit', 'submit', __('Save'));
                echo Html::input('hidden', 'payacct[id]', $curr_payacct_id);
                if ($provider->name == '99bill') {
                    echo Html::input('hidden', 'payacct[seller_account]', '');
                    echo Html::input('hidden', 'payacct[seller_site_url]', '');
                }
                ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td width="20%"><span class="label" style="padding-left:10px;"><?php _e('Payment Gateway'); ?></span></td>
                <td width="80%"><span class="entry"><?php _e($provider->disp_name); ?></span></td>
            </tr>
            <tr>
	           <td class="label">
	            	<?php 
	            		if($provider->name == 'alipay')
	            		{
	            			_e('Alipay Account');
	            		}
	            		elseif($provider->name == '99bill')
	            		{
	            			_e('99bill Account');
	            		}
	            		elseif($provider->name == 'paypal')
	            		{
	            			_e('Paypal Account');
	            		}
	            		elseif($provider->name == 'tencentmed')
	            		{
	            			_e('Tencent Medium Security Account');	
	            		}
	            		elseif($provider->name == 'tencentimd')
	            		{
	            			_e('Tencent Prompt Arrival Account');	
	            		}
						elseif($provider->name == 'alipaymed')
	            		{
	            			_e('Alipay Medium Security Account');	
	            		}
	            		elseif($provider->name == 'alipayimd')
	            		{
	            			_e('Alipay Prompt Arrival Account');	
	            		}elseif($provider->name == 'paypalen')
	            		{
	            			_e($provider->disp_name);	
	            		}
						elseif($provider->name == 'moneybookers')
	            		{
	            			_e($provider->disp_name);	
	            		}
						elseif($provider->name == 'paydollar')
	            		{
	            			_e($provider->disp_name);	
	            		}
						elseif($provider->name == '2checkout')
	            		{
	            			_e($provider->disp_name);	
	            		}
						elseif($provider->name == 'nps')
	            		{
	            			_e($provider->disp_name);	
	            		}
	            	?>
	            </td>   
                <td class="entry">
                <?php
				$curr_payacct_seller_account='';
				if(isset($curr_payacct->seller_account)){
					$curr_payacct_seller_account=$curr_payacct->seller_account;
				}
                echo Html::input('text', 'payacct[seller_account]', $curr_payacct_seller_account, 
                    'class="textinput"', $payacct_form, 'RequiredTextbox', 
                    __('Please input account!'));
                    
                    if ($provider->name == 'paypal')
                    {
                    	$str12 = __('You should apply a chinese paypal account.');
                    	$str13 = __('Paypal');	
                    }
				if ($provider->name == 'paypalen')
                    {
                    	$str12 = __('You should apply a paypal account.');
                    }
                ?><?php if($provider->name == 'paypal'){?>&nbsp;&nbsp;<span style='font-size:10px;'><a style='font-size:11px;' href='http://www.paypal.com' target='_blank'><?php echo $str13; ?></a></span>
                <img id="paypal" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php echo __('You should apply a chinese paypal account.'); ?>"/>
				<?php }?>
				<?php if($provider->name == 'paypalen'){?>
                <img id="paypal" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php echo __('You should apply a paypal account.'); ?>"/>
				<?php }?>
                </td>
            </tr>
            <?php if ($provider->name != '99bill' && $provider->name != 'tencentmed' && $provider->name != 'tencentimd') { ?>
            <tr>
                <td class="label"><?php _e('Seller Site URL'); ?></td>
                <td class="entry">
                <?php
				$curr_payacct_seller_site_url='';
				if(isset($curr_payacct->seller_site_url)){
					$curr_payacct_seller_site_url=$curr_payacct->seller_site_url;
				}
                echo Html::input('text', 'payacct[seller_site_url]', $curr_payacct_seller_site_url, 
                    'class="textinput"', $payacct_form, 'RequiredTextbox', 
                    __('Please input seller site URL!'));
                ?>
                <img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set pay note');?>"/>
                </td>
            </tr>
            <?php } ?>
            <?php if(!in_array($provider->name,array('paypal','tencentmed','tencentimd','paypalen','moneybookers'))){?>
            <tr>
                <td class="label"><?php _e('Partner ID'); ?></td>
                <td class="entry">
                <?php
				$curr_payacct_partner_id='';
				if(isset($curr_payacct->partner_id)){
					$curr_payacct_partner_id=$curr_payacct->partner_id;
				}
                echo Html::input('text', 'payacct[partner_id]', $curr_payacct_partner_id, 
                    'class="textinput"', $payacct_form, 'RequiredTextbox', 
                    __('Please input partner ID!'));
                ?>
                </td>
            </tr>
            <?php }?>
            <?php if(!in_array($provider->name,array('paypal','paypalen','moneybookers'))){?>
            <tr>
                <td class="label"><?php _e('Partner Key'); ?></td>
                <td class="entry">
                <?php
				$curr_payacct_partner_key='';
				if(isset($curr_payacct->partner_key)){
					$curr_payacct_partner_key=$curr_payacct->partner_key;
				}
                echo Html::input('password', 'payacct[partner_key]', $curr_payacct_partner_key, 
                    'class="textinput"', $payacct_form, 'RequiredTextbox', 
                    __('Please input partner key!'));
                ?>
                </td>
            </tr>
            
            <tr>
                <td class="label"><?php _e('Confirm Key'); ?></td>
                <td class="entry">
                <?php
                echo Html::input('password', 'payacct[re_partner_key]', $curr_payacct_partner_key, 
                    'class="textinput"', $payacct_form, 'RequiredTextbox', 
                    __('Please retype your partner key for confirmation!'));
                ?>
                </td>
            </tr>
            <?php }?>
            <tr>
                <td class="label"></td>
                <td class="entry">
                <?php
				$curr_payacct_enabled='';
				if(isset($curr_payacct->enabled)){
					$curr_payacct_enabled=$curr_payacct->enabled;
				}
                echo Html::input('checkbox', 'payacct[enabled]', '1', 
                    Toolkit::switchText($curr_payacct_enabled, 
                        array('0' => '', '1' => 'checked="checked"')));
                ?>
                &nbsp;<?php _e('Enable'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php

    $payacct_form->close();
    if($provider->name != 'paypal'&&$provider->name != 'paypalen'&&$provider->name != 'moneybookers'){
	    $payacct_form->genCompareValidate('payacct[partner_key]', 'payacct[re_partner_key]', 
	        '=', __('Partner keys mismatch!'));
    }
    $running_msg = __('Saving account...');
    $custom_js = <<<JS
    $("#adminpayacctfrm_stat").css({"display":"block"});
    $("#adminpayacctfrm_stat").html("$running_msg");
    _ajax_submit(thisForm, on_success, on_failure);
    return false;
    
JS;
    $payacct_form->addCustValidationJs($custom_js);
    $payacct_form->writeValidateJs();
    ?>

<?php } ?>
