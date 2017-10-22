<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_msg_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("messfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["messform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.style.display = "none";
        alert("<?php _e('Thank you! Your message has been submitted successfully!'); ?>");
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["messform"].reset();
    
    document.getElementById("messfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>

<div class="status_bar">
	<span id="messfrm_stat" class="status" style="display:none;"></span>
</div>
<div class="contenttoolbar">
    <div class="contenttitle alignleft"><?php echo $page_title; ?></div>
</div>
<div class="contentbody">
<?php
$mess_form = new Form('index.php', 'messform', 'check_mess_info');
$mess_form->p_open('mod_message', 'messInsert', '_ajax');
?>
<table id="messform_table" class="front_form_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2" class="normal">
            <?php echo Html::input('submit', 'submit', __('Submit')); ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Nickname'); ?></td>
            <td class="entry">
            <?php echo Html::input('text', 'mess[username]', '', '', $mess_form, 'RequiredTextbox', __('Please input your username!')); ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('E-mail'); ?></td>
            <td class="entry">
            <?php echo Html::input('text', 'mess[email]', '', '', $mess_form, 'RequiredTextbox',  __('Please input your email!')); ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Telephone'); ?></td>
            <td class="entry">
            <?php echo Html::input('text', 'mess[tele]', '', '', $mess_form, 'RequiredTextbox',  __('Please input your telephone!')); ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Content'); ?></td>
            <td class="entry">
            <?php echo Html::textarea('mess[message]', '', 'rows="6" cols="36"', $mess_form, 'RequiredTextbox', __('Please input your content!')); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$mess_form->close();
$running_msg = __('Saving message...');
$custom_js = <<<JS
$("#messfrm_stat").css({"display":"block"});
$("#messfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_msg_success, on_failure);
return false;

JS;
$mess_form->addCustValidationJs($custom_js);
$mess_form->writeValidateJs();
?>
</div>
