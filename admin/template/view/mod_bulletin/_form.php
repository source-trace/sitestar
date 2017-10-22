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
    
    var stat = document.getElementById("adminartfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["bulletinform"].reset();
        
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
    document.forms["bulletinform"].reset();
    
    document.getElementById("adminartfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function backPrv(){
	window.location.href="index.php?_m=mod_bulletin&_a=admin_list";	
}
//-->
</script>
<div class="status_bar">
	<span id="adminartfrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$bulletin_form = new Form('index.php', 'bulletinform', 'check_login_info');
$bulletin_form->p_open('mod_bulletin', $next_action, '_ajax');
?>
<table id="bulletinform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_bulletin_id='';
			if(isset($curr_bulletin->id)){
				$curr_bulletin_id=$curr_bulletin->id;
			}
    		echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv();"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'bulletin[id]', $curr_bulletin_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="10%"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
			echo Toolkit::switchText(isset($curr_article->s_locale)?$curr_article->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'bulletin[s_locale]', isset($curr_bulletin->s_locale)?$curr_bulletin->s_locale:$mod_locale);
            ?><script language="javascript">
function setCookie(name,value)
{var Days = 1;
var exp= new Date();
exp.setTime(exp.getTime() + Days*24*60*60*1000);
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
setCookie("language_info",'<?php echo $language_info;?>');		
</script>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Title'); ?></td>
            <td class="entry">
            <?php
			$curr_bulletin_title='';
			if(isset($curr_bulletin->title)){
				$curr_bulletin_title=$curr_bulletin->title;
			}
            echo Html::input('text', 'bulletin[title]', $curr_bulletin_title, 'class="textinput" maxlength="100"');
            echo "&nbsp;&nbsp;&nbsp;";
            echo  __("Words must in thirty characters, no more!");
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Bulletin Content');?></td>
            <td class="entry">
            <?php
			$pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
			$path = substr($_SERVER['PHP_SELF'],0,$pos);
			$curr_bulletin_content='';
			if(isset($curr_bulletin->content)){
				$curr_bulletin_content=$curr_bulletin->content;
			}
			if(strpos($curr_bulletin_content,$path.'/') == 0) {
				$curr_bulletin_content = str_replace('/admin/fckeditor',$path.'/admin/fckeditor',$curr_bulletin_content);
			}
            echo Html::textarea('bulletin[content]', $curr_bulletin_content, 'rows="20" cols="108"')."\n";
            $o_fck = new RichTextbox('bulletin[content]');
            $o_fck->height = 320;

            echo $o_fck->create();
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
			$curr_bulletin_for_roles='';
			if(isset($curr_bulletin->for_roles)){
				$curr_bulletin_for_roles=$curr_bulletin->for_roles;
			}
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_bulletin_for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$bulletin_form->close();
$running_msg = __('Saving bulletin...');
$warnstr = __('Please input title!');
$custom_js = <<<JS
if ($(":text").val().length == 0) {
	alert("$warnstr");
	$(":text").focus();
	return false;
}
$("#adminartfrm_stat").css({"display":"block"});
$("#adminartfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$bulletin_form->addCustValidationJs($custom_js);
$bulletin_form->writeValidateJs();
?>
