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
    
    var stat = document.getElementById("adminscfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["scontentform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        if(o_result.forward == 'close'){
			parent.location.reload();
        } else {
		    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
		    window.location.href = o_result.forward;
        }
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["scontentform"].reset();
    
    document.getElementById("adminscfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>

<span id="adminscfrm_stat" class="status" style="display:none;"></span>
<?php
$sc_form = new Form('index.php', 'scontentform', 'check_scontent_info');
$sc_form->p_open('mod_static', $next_action, '_ajax');
?>
<table id="scontentform_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_scontent_id='';
			if(isset($curr_scontent->id)){
				$curr_scontent_id=$curr_scontent->id;
			}
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'sc[id]', $curr_scontent_id);
            echo Html::input('hidden', 'isback', $isback);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Title'); ?></td>
            <td class="entry">
            <?php
			$curr_scontent_title='';
			if(isset($curr_scontent->title)){
				$curr_scontent_title=$curr_scontent->title;
			}
            echo Html::input('text', 'sc[title]', $curr_scontent_title, 
                'class="textinput"', $sc_form, 'RequiredTextbox', 
                __('Please input title!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Content'); ?></td>
            <td class="entry">
            <?php
			$curr_scontent_content='';
			if(isset($curr_scontent->content)){
				$curr_scontent_content=$curr_scontent->content;
			}
           if(strpos($_SERVER['PHP_SELF'],'/admin/index.php') != 0){
				$pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
				$path = substr($_SERVER['PHP_SELF'],0,$pos);
				if(strpos($curr_scontent_content,$path.'/') == 0) {
					$curr_scontent_content = str_replace('/admin/fckeditor',$path.'/admin/fckeditor',$curr_scontent_content);
				}
			}
            echo Html::textarea('sc[content]', $curr_scontent_content,'rows="24" cols="80"')."\n";
            $o_fck = new RichTextbox('sc[content]');
          
            $o_fck->height = 360;
            echo $o_fck->create();
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText(isset($curr_scontent->s_locale)?$curr_scontent->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'sc[s_locale]', 
           		isset($curr_scontent->s_locale)?$curr_scontent->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
        <!-- tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sc[published]', '1', 
                Toolkit::switchText($curr_scontent->published, 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Publish'); ?>
            </td>
        </tr -->
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
			$curr_scontent_for_roles='';
			if(isset($curr_scontent->for_roles)){
				$curr_scontent_for_roles=$curr_scontent->for_roles;
			}
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_scontent_for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$sc_form->close();
$running_msg = __('Saving content...');
$custom_js = <<<JS
$("#adminscfrm_stat").css({"display":"block"});
$("#adminscfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$sc_form->addCustValidationJs($custom_js);
$sc_form->writeValidateJs();
?>
