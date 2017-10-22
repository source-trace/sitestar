<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function changeName() {
	if($("#qq_category_").val() == 0)
	{
		$("#QQName").html('QQ Name');
	}
	else if($("#qq_category_").val() == 1)
	{
		$("#QQName").html('MSN Name');
	}
	else if($("#qq_category_").val() == 2)
	{
		$("#QQName").html('WangWang Name');
	}
	// for sitestarv1.3
	else if($("#qq_category_").val() == 3)
	{
		$("#QQName").html('Skype Name');
	}else if($("#qq_category_").val() == 4)
	{
		$("#QQName").html('ICQ Name');
	}else if($("#qq_category_").val() == 5)
	{
		$("#QQName").html('Yahoo! Name');
	}else
	{
		$("#QQName").html('QQ Name');
	}
	
	
}

function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminqqfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["qqform"].reset();
        
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
    document.forms["qqform"].reset();
    
    document.getElementById("adminqqfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
function backPrv(){
	window.location.href="index.php?_m=mod_qq&_a=admin_list";	
}
//-->
</script>
<div class="status_bar">
	<span id="adminqqfrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$qq_form = new Form('index.php', 'qqform', 'check_qq_info');
$qq_form->p_open('mod_qq', $next_action, '_ajax');
?>
<table id="qqform_table" class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_qq_id='';
			if(isset($curr_qq->id)){
				$curr_qq_id=$curr_qq->id;
			}
    		echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv();"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'qq[id]', $curr_qq_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
	     <tr>
            <td width="12%" class="label"><?php _e('Category'); ?></td>
            <td class="entry">
            <?php
			$curr_qq_category='';
			if(isset($curr_qq->category)){
				$curr_qq_category=$curr_qq->category;
			}
            echo Html::select('qq[category]', 
                $select_categories, 
                $curr_qq_category,'class="textselect" onchange=changeName();');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Account'); ?></td>
            <td class="entry">
            <?php
			$curr_qq_account='';
			if(isset($curr_qq->account)){
				$curr_qq_account=$curr_qq->account;
			}
            echo Html::input('text', 'qq[account]', $curr_qq_account, 
                'class="textinput"', $qq_form, 'RequiredTextbox', 
                __('Please input account!'));
            ?>
            </td>
        </tr>
        <tr>
        	<td class="label"><span id="QQName">
        	<?php
        	if(isset($curr_qq->category)&& $curr_qq->category == 0) {
        		echo 'QQ';_e('Nickname');
        	} elseif(isset($curr_qq->category)&&$curr_qq->category == 1) {
        		echo 'MSN';_e('Nickname');
        	} elseif(isset($curr_qq->category)&&$curr_qq->category == 2) {
        		echo 'WangWang';_e('Nickname');
        	} elseif(isset($curr_qq->category)&&$curr_qq->category == 3) {
        		echo 'Skype';_e('Nickname');
        	}elseif(isset($curr_qq->category)&&$curr_qq->category == 4) {
        		echo 'ICQ';_e('Nickname');
        	}elseif(isset($curr_qq->category)&&$curr_qq->category == 5) {
        		echo 'Yahoo! MSG';
        	}else{
				echo 'QQ';_e('Nickname');
			}
        	 ?>
        	</span></td>
        	<td class="entry">
            <?php
			$curr_qq_qqname='';
			if(isset($curr_qq->qqname)){
				$curr_qq_qqname=$curr_qq->qqname;
			}
            echo Html::input('text', 'qq[qqname]', $curr_qq_qqname, 
                'class="textinput"', $qq_form, 'RequiredTextbox', 
                __('Please input name!'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$qq_form->close();
$running_msg = __('Saving IM Account Message...');
$custom_js = <<<JS
$("#adminqqfrm_stat").css({"display":"block"});
$("#adminqqfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$qq_form->addCustValidationJs($custom_js);
$qq_form->writeValidateJs();
?>
