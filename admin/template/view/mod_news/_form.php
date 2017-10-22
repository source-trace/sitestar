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
        document.forms["articleform"].reset();
        
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
    document.forms["articleform"].reset();
    
    document.getElementById("adminartfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e($article_title); ?></div></td>
				<td><a href="<?php echo Html::uriquery('mod_news', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a></td>
			</tr>
		</tbody>
	</table>
	
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="adminartfrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$article_form = new Form('index.php', 'articleform', 'check_login_info');
$article_form->p_open('mod_news', $next_action, '_ajax');
?>
<table id="articleform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'article[id]', $curr_article->id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText($curr_article->s_locale?$curr_article->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'article[s_locale]', 
           		$curr_article->s_locale?$curr_article->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Title'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'article[title]', $curr_article->title, 
                'size="48"', $article_form, 'RequiredTextbox', 
                __('Please input title!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Author'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'article[author]', 
            	$curr_article->author?$curr_article->author:SessionHolder::get('user/login'), 
                '', $article_form, 'RequiredTextbox', 
                __('Please input author!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Source'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'article[source]', $curr_article->source, 'size="48"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Tags'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'article[tags]', $curr_article->tags, 'size="48"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Content'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('article[content]', $curr_article->content, 'rows="24" cols="108"')."\n";
            $o_fck = new RichTextbox('article[content]');
            $o_fck->height = 420;
            echo $o_fck->create();
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_article->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$article_form->close();
$running_msg = __('Saving article...');
$custom_js = <<<JS
$("#adminartfrm_stat").css({"display":"block"});
$("#adminartfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$article_form->addCustValidationJs($custom_js);
$article_form->writeValidateJs();
?>
