<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
include_once(P_INC."/htmlsubstring.php");
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<style type="text/css">
#content .fr li {margin:0;}
#submit {float:left;}
</style>
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function(){
	$('#answer').cluetip({splitTitle: '|',width: '300px',height:'68px'});
});
function on_del_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminartlst_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_del_failure(response) {
    document.getElementById("adminartlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_article(article_id) {
	if (confirm("<?php _e('Delete the selected article?'); ?>")) {
	    var stat = document.getElementById("adminartlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected article...'); ?>";
		_ajax_request("mod_article", 
			"admin_delete", 
	        {
	            article_id:article_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
function delete_articles(){
	var arr = document.getElementsByName("article");
	var str="";
	for (var i = 0; i < arr.length; i++){
		var e = arr[i];
		if (e.checked){
			str = e.value + "_" + str;
		}
	}
	if(str.length < 1) {
		alert("<?php _e('Please select items to be deleted!'); ?>");
	} else {
		delete_article(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("article");
		if(el.checked){
			for(i=0;i<arr.length;i++){arr[i].checked=true;}
		}else{
			for(i=0;i<arr.length;i++){arr[i].checked=false;}
		}
	}catch(e){
		return false;
	}
}

function keyword_search() {
	var kw = $('#keyword').val();
	if( kw.length == 0 ) {
		alert("<?php _e('Please give me a keyword!');?>");
		$('#keyword').focus();
		return false;
	} else {
		document.forms['caaswform'].hidkeyword.value = kw;
		document.forms['caaswform'].submit();
	}
}
function copy_data(){
	var chk_value =[];    
    $('input[name="article"]:checked').each(function(){    
   		chk_value.push($(this).val());    
  	});
	if(chk_value==''){
		alert('<?php _e('Choose article please!');?>');
		return false;
	}
	
	show_iframe_win('index.php?_m=mod_article&_a=copy_article&article='+chk_value,'<?php _e('Copy Article to language');?>','610','118');
	return false;
}

function move_data(){
	var chk_value2 =[];    
    $('input[name="article"]:checked').each(function(){    
   		chk_value2.push($(this).val());    
  	});
	if(chk_value2==''){
		alert('<?php _e('Choose article please!');?>');
		return false;
	}
	
	show_iframe_win('index.php?_m=mod_article&_a=move_article&article='+chk_value2,'<?php _e('Move Article to category');?>','610','238');
	return false;
}
//-->
</script>
<div class="status_bar">
	<span id="adminartlst_stat" class="status" style="display:none;"></span>
</div>

<ul style="margin-left:15px;">
        <?php
            if(ACL::isAdminActionHasPermission('mod_category_a', 'admin_list')){
        ?>
        <li><a class="iconfl" href="<?php echo Html::uriquery('mod_category_a', 'admin_list', array('goto' => '10000')); ?>"><?php _e('Manage Categories'); ?></a></li>
       <?php
        }
        ?>
        <?php
            if(ACL::isAdminActionHasPermission('mod_article', 'admin_add')){
        ?>
        <li><a class="icontj" href="<?php echo Html::uriquery('mod_article', 'admin_add'); ?>" title=""><?php _e('Add Article'); ?></a></li>
        <?php
        }
        ?>
<!--li><a class="icontj" href="<?php echo Html::uriquery('mod_article', 'admin_batch'); ?>" title=""><?php _e('Batch Import'); ?></a></li>
	<li><a class="icontj" href="<?php echo Html::uriquery('mod_article', 'admin_list', array('act'=>'9999')); ?>" title=""><?php _e('Batch Export'); ?></a></li-->
	<?php
            if(ACL::isAdminActionHasPermission('mod_article', 'admin_delete')){
        ?>
    <li><a class="iconsc" href="javascript:void(0)" onclick="delete_articles();"><?php _e('Delete Selected'); ?></a></li>
 <?php
        }
 ?>
    <li style="margin-top:14px;margin-right:15px;"><?php include_once(dirname(__FILE__).'/_category_switch.php');?></li>
	<li style="margin-top:14px;margin-right:5px;"><?php include_once(P_TPL.'/common/language_switch.php');if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:16px;line-height:0px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Language note');?>"/>
	<?php } ?></li> 
	<li style="margin-top:0;_margin-top:14px;"><?php echo Html::input('text', 'keyword', $keyword, 'class="textinput" style="width:100px;"'); echo '&nbsp;&nbsp;'.Html::input('button', 'search', '', ' class="btn" onclick="keyword_search()"');?></li>
	<br />
<?php
$article_order_form = new Form('index.php', 'articleorderform', 'check_login_info');
$article_order_form->p_open('mod_article', $next_action, '_ajax');
?>
<?php
    if(ACL::isAdminActionHasPermission('mod_article', 'admin_order')){
?>
    <li><input style="_margin-top:14px;" type="submit" value="<?php _e('Save Order');?>" id="submit" name="submit"/></li>
<?php
}
 ?>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;<input style="_margin-top:14px;" type="button" value="<?php _e('Copy Article');?>" id="copyToL" name="copyToL" onclick="copy_data();"/></li>
 		<li>&nbsp;&nbsp;&nbsp;&nbsp;<input style="_margin-top:14px;" type="button" value="<?php _e('Move Article');?>" id="copyToL" name="copyToL" onclick="move_data();"/></li>
</ul>

<table class="form_table_list" id="admin_article_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
	<thead>
		<tr>
		    <th width="10%" bgcolor="#f6f6f4"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th width="35%"><?php _e('Title'); ?></th>
            <th width="10%"><?php _e('Author'); ?></th>
            <th width="12%"><?php _e('Category'); ?></th>
			<th width="12%"><?php _e('Order');?></th>
            <th width="7%"><?php _e('Publish'); ?></th>
            <th width="7%"><?php _e('Edit'); ?></th>
            <th width="7%"><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($articles) > 0) {
        $row_idx = 0;
        foreach ($articles as $article) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo Html::input('checkbox', 'article', $article->id); ?></td>
        	<td class="left">
            
             <?php
            if($lang_sw==$default_lang){
			?>
             <a href="../<?php echo Html::uriquery2('mod_article', 'article_content',array('article_id'=>$article->id)); ?>" target="_blank" title="<?php echo $article->title; ?>"><?php echo subString($article->title,0,20); ?></a>
            <?php
            }else{
			?>
            <a href="<?php echo Html::uriquery2('mod_article', 'admin_edit', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>"><?php echo subString($article->title,0,20); ?></a>
            <?php
			}
			?>
            </td>
        	<td><?php echo $article->author; ?></td>
        	<?php $article->loadRelatedObjects(REL_PARENT, array('ArticleCategory')); ?>
        	<td><?php if(isset($article->masters['ArticleCategory']->name)){echo $article->masters['ArticleCategory']->name;} ?></td>
			<td><?php echo Html::input('text', 'i_order['.$article->id.']', $article->i_order, 'class="textinput" style="width:20px;"'); ?></td>
        	<td>
            <?php 
            $needchange=true;
            if(!ACL::isAdminActionHasPermission('mod_article', 'admin_pic')) $needchange=false;
            echo Toolkit::validateYesOrNo($article->published,$article->id,"index.php?_m=mod_article&_a=admin_pic&_r=ajax&_id=".$article->id,$needchange);
            ?>
            </td>
        	<td>
<?php
    if(ACL::isAdminActionHasPermission('mod_article', 'admin_edit')){
?>
                    <a href="<?php echo Html::uriquery('mod_article', 'admin_edit', array('article_id' => $article->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
 <?php
}
 ?>       	
            </td>
        	<td>
<?php
    if(ACL::isAdminActionHasPermission('mod_article', 'admin_delete')){
?>        
        		<a href="#" onclick="delete_article(<?php echo $article->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
<?php
}
 ?>         	
            </td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="8"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<?php
$article_order_form->close();
$running_msg = __('Saving article order...');
$custom_js = <<<JS
$("#adminartlst_stat").css({"display":"block"});
$("#adminartlst_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;
JS;
$article_order_form->addCustValidationJs($custom_js);
$article_order_form->writeValidateJs();
?>
<?php
include_once(P_TPL.'/common/pager.php');
?>
