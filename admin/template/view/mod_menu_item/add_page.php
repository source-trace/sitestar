<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript" src="../script/popup/jquery-1.4.3.js"></script>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.core.js"></script>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.widget.js"></script>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.mouse.js"></script>

<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.draggable.js"></script>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.resizable.js"></script>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.dialog.js"></script>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.position.js"></script>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.sortable.js"></script>
<script type="text/javascript" language="javascript">
<!--
var popup_win = false;

function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminmifrm_stat");
    if (o_result.result == "ERROR") {
        //document.forms["miform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
    	parent.window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["miform"].reset();
    
    document.getElementById("adminmifrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function show_selected(text) {
    $("#menu_link_content_title").html(text);
    $("#mi_selected_content_").attr("value", 
        $("#menu_link_content_title").html());
}
function set_tmp_id(id) {
    $("#tmp_id").attr("value", id);
}
function check_url(obj){
	var stat = document.getElementById("adminmifrm_stat");
	var url = $(obj).val();
	var regExp = /[a-zA-Z]+/g;

	$.post("index.php?_m=mod_menu_item&_a=check_url",{url:url},function(data){
		if(data==0){
			//stat.innerHTML = "";
			$("#adminmifrm_stat").hide();
			$("#submit").attr('disabled','');
		}else if(data==3){
			$("#adminmifrm_stat").html("<?php _e('Custom address can not contain special characters'); ?>").show()
			$("#submit").attr('disabled','disabled');
		}else if(data==2){
			$("#adminmifrm_stat").html("<?php _e('-p and paging keyword conflicts, use other characters'); ?>").show()
			$("#submit").attr('disabled','disabled');
		}else{
			$("#adminmifrm_stat").html("<?php _e('Custom link already exists, please re-enter'); ?>").show()
			$("#submit").attr('disabled','disabled');
		}
	})
}
$(document).ready(function() {
	$("#keyword").hide();
	$("#title").hide();
	$("#description").hide();
	$("#utilize_seo").click(function(){
		$("#keyword").show();
		$("#title").show();
		$("#description").show();
	});

	$("#forbidden_seo").click(function(){
		$("#keyword").hide();
		$("#title").hide();
		$("#description").hide();
		$("#mi_meta_key_").attr('value','');
		$("#mi_meta_desc_").attr('value','');
	});
    $(".menu_link_type_selector").click(function() {
            var my_value = $(this).attr("value");
            var value_parts = my_value.split(/\|/);
            if (value_parts[1] == "1") {
            	parent.$('#showContents').hide();
            	parent.$('#showContents').parent().append('<iframe width="600" scrolling="auto" height="412" frameborder="no" src="admin/index.php?_m=mod_menu_item&_a=admin_link_content_select&txt='+encodeURI(value_parts[2])+'&pt='+value_parts[0]+'" id="showContents1" style="border: 1px solid rgb(153, 187, 232);" allowtransparency="yes" name="showContents1"></iframe>');
            } else {
                show_selected(value_parts[2]);
            }
    });
});

//-->
</script>
<style type="text/css">
.label_add_page {width:25%;text-align:right;padding-right:10px;color:#4372B0;font-weight:bold;}
</style>
<div class="status_bar">
	<span id="adminmifrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$mi_form = new Form('index.php', 'miform', 'check_mi_info');
$mi_form->p_open('mod_menu_item', 'save_page', '_ajax');
?>
<table id="miform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="4">
            <?php
    		$top_menu_id = trim(ParamHolder::get('top_menu_id', '0'));
            if ($top_menu_id == '999') {
            	echo Html::input('button', 'cancel', __('Cancel'),'onclick="window.location.href=\''.Html::uriquery('mod_menu_item', 'admin_list').'\'"');
            }
			echo Html::input('reset', 'reset', __('Reset'),'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
			echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'mi[id]', @$curr_mi->id);
            echo Html::input('hidden', 'mi[menu_item_id]', 0);
            echo Html::input('hidden', 'mi[menu_id]', $menu_id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label" width="27%"><?php _e('Language'); ?></td>
            <td class="entry" colspan="3">
            <?php
            echo Toolkit::switchText((isset($curr_mi->s_locale) && $curr_mi->s_locale)?$curr_mi->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'mi[s_locale]', 
           		(isset($curr_mi->s_locale) && $curr_mi->s_locale)?$curr_mi->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
        
        <tr>
            <td class="label"><?php _e('Navbar Title'); ?></td>
            <td colspan="3" class="entry">
            <?php
			$curr_mi_name='';
			if(isset($curr_mi->name)){
				$curr_mi_name=$curr_mi->name;
			}
            echo Html::input('text', 'mi[name]', $curr_mi_name, 
                'class="textinput"', $mi_form, 'RequiredTextbox', 
                __('Please input menu item name!'));
            ?>
			<img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set title note');?>"/>
            </td>
        </tr>
		<?php if(MOD_REWRITE == '3'){ ?>
			<tr>
            <td class="label"><?php _e('Custom link'); ?></td>
            <td colspan="3" class="entry">
            <?php
            echo Html::input('text', 'mi[url]', $curr_mi->url, 
                'class="textinput" onblur="check_url(this)"', $mi_form);
            ?>
            </td>
        </tr>
		<?php } ?>
        <tr>
        	<td class="label"><?php  _e('Set SEO'); ?></td>
        	<td colspan="3" class="entry">
            <input type="radio" name="set_seo" id="utilize_seo" value="on" />&nbsp;<?php _e('Yes'); ?>&nbsp;
            <input type="radio" name="set_seo" id="forbidden_seo" value="off" checked/>&nbsp;<?php _e('No'); ?>&nbsp;<br /><?php _e("select 'yes' means separately setting SEO parameters for this page,  global parameters are invalid to this page"); ?>
			<a href="<?php echo Html::uriquery('mod_static', 'seo')?>" target="_blank"><?php _e('About SEO'); ?></a>
            </td>
        </tr>
        <tr id="title">
            <td class="label"  width="30%"><?php _e('Page Title'); ?>(Title)</td>
            <td colspan="3" class="entry">
            <?php
			$curr_mi_title='';
			if(isset($curr_mi->title)){
				$curr_mi_title=$curr_mi->title;
			}
            echo Html::input('text', 'mi[title]', $curr_mi_title, 
                'class="textinput"', $mi_form);
            ?>
			<img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set content note');?>"/>
            </td>
        </tr>
         <tr id="keyword">
            <td class="label_add_page"><?php _e('Page Keyword'); ?>(Keyword)</td>
            <td class="entry" colspan="3">
            <span class="seo">
            <?php
			$curr_mblock_meta_key='';
			if(isset($curr_mblock->meta_key)){
				$curr_mblock_meta_key=$curr_mblock->meta_key;
			}
            echo Html::input('text', 'mi[meta_key]', $curr_mblock_meta_key,'class="textinput"');
            ?>
            <img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set key note');?>"/>
            </span>
            </td>
        </tr>
		
        <tr id="description">
            <td class="label" height="60"><span class="seo"><?php _e('Page Description'); ?>(Description)</span></td>
            <td class="entry" colspan="3">
            <span class="seo">
            <?php
			$curr_mblock_meta_desc='';
			if(isset($curr_mblock->meta_desc)){
				$curr_mblock_meta_desc=$curr_mblock->meta_desc;
			}
            echo Html::input('text', 'mi[meta_desc]', $curr_mblock_meta_desc,'class="textinput"');
            ?>
			<img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set search note');?>"/>
			</span>
            </td>
        </tr>
       
        <tr>
            <td class="label"><?php _e('Superior Column'); ?></td>
            <td class="entry" colspan="3">
            <?php
			$curr_mi_id='';
			if(isset($curr_mi->menu_item_id)){
				$curr_mi_id=$curr_mi->menu_item_id;
			}
            echo Html::select('mi[menu_item_id]', 
            	$select_mis, 
            	$curr_mi_id,'class="textselect" style="width:150px;"');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Column Property');?></td>
            <?php if (sizeof($menus) > 0) {
                foreach ($menus as $section => $list_items) { ?>
                <td width="27%">
                <span class="menu_link_type_section">
                    <h3><?php //_e($section); ?></h3>
                    <!-- TODO:: Add "link_type" field in menu_items table -->
                    <?php
						$curr_mi_type='';
						if(isset($curr_mi->link_type)){
							$curr_mi_type = $curr_mi->link_type;
						}
						echo Html::groupradio('mi[link_type]', $list_items, $curr_mi_type, 'class="menu_link_type_selector"', 1); 
					?>
                </span></td>
            <?php }} ?>
            <?php /* echo Html::input('hidden', 'mi[link]', $curr_mi->link); */ ?>
            <?php echo Html::input('hidden', 'tmp_id'); ?>
        </tr>
        <tr>
            <td class="label"><?php _e('Selected Content'); ?></td>
            <td class="entry" colspan="3">
                <!-- TODO:: Add "selected_content" field in menu_items table -->
                <span id="menu_link_content_title"><?php echo isset($curr_mi->selected_content)?$curr_mi->selected_content:''; ?></span>
                <?php echo Html::input('hidden', 'mi[selected_content]', isset($curr_mi->selected_content)?$curr_mi->selected_content:''); ?>
            </td>
        </tr>
        <!--
        <tr>
            <td class="label"><?php _e('Page Layout'); ?></td>
            <td class="entry">
            <?php 
            	//动态获取模板中layout的页面多样式列表
            	include_once(ROOT.'/template/'.DEFAULT_TPL.'/layout/conf.php');
            	$arr = LayouConfig::$layout_param;
            	foreach($arr as $k => $v)
            	{
            		if($k == 'default') continue;
            		echo Html::input('radio', 'mi[layout]', $k);
            		echo "&nbsp;<img src='../template/".DEFAULT_TPL."/layout/{$v['layout_screenshot_file']}' />&nbsp;";
            	}
            ?>
            
            </td>
        </tr>-->
        
        <tr>
        	<td class="label"><?php  _e('Menu Display'); ?></td>
        	<td colspan="3" class="entry">
            <input type="radio" name="mi[published]"  value="1" checked />&nbsp;<?php _e('Yes'); ?>&nbsp;
            <input type="radio" name="mi[published]"  value="0" />&nbsp;<?php _e('No'); ?>&nbsp;
            </td>
        </tr>
		<tr>
        	<td class="label"><?php  _e('Blank or self'); ?></td>
        	<td colspan="3" class="entry">
            <input type="radio" name="open_style"  value="1" />&nbsp;<?php _e('Yes'); ?>&nbsp;
            <input type="radio" name="open_style"  value="0" checked />&nbsp;<?php _e('No'); ?>&nbsp;
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="entry" colspan="3">
            <?php
            echo Html::input('checkbox', 'ismemonly', '1', 
                Toolkit::switchText(strval(ACL::isMemOnly(@$curr_mi->for_roles)), 
                    array('0' => '', '1' => 'checked="checked"')));
            ?>
            &nbsp;<?php _e('Member only access'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$mi_form->close();
$running_msg = __('Saving menu item...');
$warn_msg = __('Please select column property!');
$custom_js = <<<JS
if (!$('#menu_link_content_title').text().length) {
	alert("$warn_msg");
	return false;
}
$("#adminmifrm_stat").css({"display":"block"});
$("#adminmifrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$mi_form->addCustValidationJs($custom_js);
$mi_form->writeValidateJs();
?>
