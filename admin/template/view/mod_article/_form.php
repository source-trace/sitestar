<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$act_2='';
if(isset($act)){
	$act_2=$act;
}
$curr_article_is_seo='';
if(isset($curr_article->is_seo)){
	$curr_article_is_seo=$curr_article->is_seo;
}
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer1').cluetip({splitTitle: '|',width: '165px',height:'33px'});
	$('#answer2').cluetip({splitTitle: '|',width: '235px',height:'33px'});
	$('#answer3').cluetip({splitTitle: '|',width: '235px',height:'33px'});
});
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

function on_quick_add_cate_a_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminartfrm_stat");
    if (o_result.result == "ERROR") {
        $("#new_cate_a").val("");
        
        stat.innerHTML = o_result.errmsg;
        stat.style.display = "block";
        return false;
    } else if (o_result.result == "OK") {
        var cate_select = document.getElementById("article_article_category_id_");
        var after_idx = cate_select.selectedIndex;
        var new_id = o_result.id;
        var new_text = $("#new_cate_a").val();
        var parent_id = cate_select.options[after_idx].value;
        var level_count = cate_select.options[after_idx].text.count("--");

        for (var i = cate_select.length - 1; i > after_idx; i--) {
            cate_select.options[i + 1] = new Option();
            cate_select.options[i + 1].value = cate_select.options[i].value;
            cate_select.options[i + 1].text = cate_select.options[i].text;
        }
        if (typeof(cate_select.options[i + 1]) == "undefined") {
            cate_select.options[i + 1] = new Option();
        }
        cate_select.options[i + 1].value = new_id;
        if (parent_id == "0") {
            cate_select.options[i + 1].text = " " + new_text;
        } else {
            cate_select.options[i + 1].text = " " + "-- ".repeat(level_count + 1) + new_text;
        }
        cate_select.options[i + 1].selected = "selected";
    } else {
        return on_failure(response);
    }
}

function add_cate_a() {
    _ajax_request("mod_category_a", 
        "admin_quick_create", 
        {
            name: $("#new_cate_a").val(),
            parent: $("#article_article_category_id_").val(),
            locale: $("#article_s_locale_").val()
        }, 
        on_quick_add_cate_a_success, 
        on_failure);
}


$(function(){
	var act = "<?php echo $act_2;?>";
	if ( act == 'add' ) {
		$('<option value="-1" selected=true><?php _e('Please add category');?></option>').prependTo('#article_article_category_id_');
		opt = document.getElementById('article_article_category_id_');
		if( opt.options[0].value == '-1' ) opt.options[0].selected = true;
	    $('#article_article_category_id_').click(function(){
	    	opt = document.getElementById('article_article_category_id_');
	    	if( opt.options[0].value == '-1' ) {
	    		opt.options[1].selected = true;
	    		opt.remove(0);
	    	}
	    });
	}
	// 2011/02/28 设置SEO
	var iseo = "<?php echo $curr_article_is_seo;?>";
	setSeo(iseo);
});

// 2011/02/28 设置SEO
function setSeo(val) {
	$('.seoption').css('display','none');
	if (val == '1') {
		$('.seoption').css('display','');
	} else {
		$('.seoption input').val('');
	}
}
//-->
</script>
<div class="status_bar">
	<span id="adminartfrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$article_form = new Form('index.php', 'articleform', 'check_login_info');
$article_form->p_open('mod_article', $next_action, '_ajax');
?>
<table id="articleform_table" class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			$curr_articleid='';
			if(isset($curr_article->id)){
				$curr_articleid=$curr_article->id;
			}
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'article[id]', $curr_articleid);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="10%"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText((isset($curr_article->s_locale) && $curr_article->s_locale)?$curr_article->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'article[s_locale]', 
           		(isset($curr_article->s_locale) && $curr_article->s_locale)?$curr_article->s_locale:$mod_locale);
            ?><script language="javascript">
function setCookie(name,value)
{
　　var Days = 1; 
　　var exp　= new Date();
　　exp.setTime(exp.getTime() + Days*24*60*60*1000);
　　document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
setCookie("language_info",'<?php echo $language_info;?>');

			
			</script>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Category'); ?></td>
            <td class="entry">
            <?php
			$curr_crticle_id ='';
			if(isset($curr_article->article_category_id)){
				$curr_crticle_id=$curr_article->article_category_id;
			}
            echo Html::select('article[article_category_id]', 
                $select_categories, 
                $curr_crticle_id, 'class="textselect"');
            ?>
		  <?php
			if(ACL::isAdminActionHasPermission('mod_category_a', 'admin_quick_create')){
			?>
            &nbsp;<a href="#" onclick="add_cate_a(); return false;"><?php _e('Add Category'); ?></a>
		<?php
			}
		?>
            &nbsp;<?php echo Html::input('text', 'new_cate_a', '', 'class="textinput" style="width:190px;"'); ?>
		<?php
			if(ACL::isAdminActionHasPermission('mod_category_a', 'admin_list')){
			?>
            &nbsp;<a href="<?php echo Html::uriquery('mod_category_a', 'admin_list'); ?>"><?php _e('Manage Categories'); ?></a>
		<?php
			}
		?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Title'); ?></td>
            <td class="entry">
            <?php
			$curr_article_title='';
			if(isset($curr_article->title)){
				$curr_article_title=$curr_article->title;
			}
            echo Html::input('text', 'article[title]', htmlspecialchars($curr_article_title), 
                'class="textinput"', $article_form, 'RequiredTextbox', 
                __('Please input title!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Author'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'article[author]', 
            	(isset($curr_article->author)&&$curr_article->author)?$curr_article->author:SessionHolder::get('user/login'), 
                'class="textinput" style="width:150px;"', $article_form, 'RequiredTextbox', 
                __('Please input author!'));
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('Publish Date'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'article[create_time]', 
            	(isset($curr_article->create_time) && $curr_article->create_time)?date("Y-m-d H:i:s",$curr_article->create_time):date("Y-m-d H:i:s"), 
                'class="textinput" style="width:150px;"', $article_form);
            ?>
            <img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php echo __('Date note');?>"/>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Source'); ?></td>
            <td class="entry">
            <?php
			$curr_article_source='';
			if(isset($curr_article->source)){
				$curr_article_source=$curr_article->source;
			}
            echo Html::input('text', 'article[source]', $curr_article_source, 'class="textinput"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Set SEO'); ?></td>
            <td class="entry">
            <?php
            if (isset($curr_article->is_seo) && $curr_article->is_seo == '1') {
            	$checked01 = 'checked';
            	$checked02 = '';
            } else {
            	$checked01 = '';
            	$checked02 = 'checked';
            }
            echo Html::input('radio', 'article[is_seo]', '1', 'onclick="setSeo(this.value)"'.$checked01).'&nbsp;&nbsp;'.__('Yes').'&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('radio', 'article[is_seo]', '0', 'onclick="setSeo(this.value)"'.$checked02).'&nbsp;&nbsp;'.__('No').'&nbsp;&nbsp;&nbsp;&nbsp;';
            echo __("select 'yes' means separately setting SEO parameters for this page,  global parameters are invalid to this page").'&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?_m=mod_static&_a=seo" target="_blank">'.__('About SEO').'</a>';
            ?>
            </td>
        </tr>
        <tr class="seoption" style="display:none;">
            <td class="label"><?php _e('Tags');echo '(Keyword)'; ?></td>
            <td class="entry">
            <?php
			$curr_article_tags='';
			if(isset($curr_article->tags)){
				$curr_article_tags=$curr_article->tags;
			}
            echo Html::input('text', 'article[tags]', $curr_article_tags, 'class="textinput"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer2" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Key note');?>"/>
            <?php }?>
            </td>
        </tr>
        <tr class="seoption" style="display:none;">
            <td class="label"><?php _e('Description');echo '(Description)'; ?></td>
            <td class="entry">
            <?php
			$curr_article_description='';
			if(isset($curr_article->description)){
				$curr_article_description=$curr_article->description;
			}
            echo Html::input('text', 'article[description]', $curr_article_description, 'class="textinput"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer3" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Help note');?>"/>
            <?php }?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Abstract');?></td>
            <td class="entry">
            <?php
			$curr_article_intro='';
			if(isset($curr_article->intro)){
				$curr_article_intro=$curr_article->intro;
			}
            echo Html::textarea('article[intro]', $curr_article_intro, 'rows="4" cols="76" class="textinput" style="width:500px"', 
            	$article_form)
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Article Content');?></td>
            <td class="entry">
            <?php
         	$pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
			$path = substr($_SERVER['PHP_SELF'],0,$pos);
			$curr_article_content='';
			if(isset($curr_article->content)){
				$curr_article_content=$curr_article->content;
			}
			if(strpos($curr_article_content,$path.'/') == 0) {
				$curr_article_content = str_replace('/admin/fckeditor',$path.'/admin/fckeditor',$curr_article_content);
			} 
            echo Html::textarea('article[content]', $curr_article_content, 'rows="24" cols="108"')."\n";
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
			$curr_article_roles='';
			if(isset($curr_article->for_roles)){
				$curr_article_roles=$curr_article->for_roles;
			}
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly($curr_article_roles)), 
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