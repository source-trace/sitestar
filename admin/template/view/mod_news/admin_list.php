<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
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
	if (confirm("Delete the selected news?")) {
	    var stat = document.getElementById("adminartlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected news...'); ?>";
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
	var arr = document.getElementsByName("news");
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
		var arr = document.getElementsByName("news");
		if(el.checked){
			for(i=0;i<arr.length;i++){arr[i].checked=true;}
		}else{
			for(i=0;i<arr.length;i++){arr[i].checked=false;}
		}
	}catch(e){
		return false;
	}
}
//-->
</script>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e('News');?></div></td>
				<td>
				    <a href="<?php echo Html::uriquery('mod_news', 'admin_add'); ?>" title=""><?php _e('Add News');?></a>
				    &nbsp;|&nbsp;
				    <a href="javascript:void(0)" onclick="delete_articles();"><?php _e('Delete Selected'); ?></a></td>
				<td><?php include_once(P_TPL.'/common/language_switch.php');?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="adminartlst_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$article_order_form = new Form('index.php', 'articleorderform', 'check_login_info');
$article_order_form->p_open('mod_news', $next_action, '_ajax');
?>
<table cellspacing="0" class="list_table" id="admin_article_list">
	<thead>
		<tr>
		    <th width="20"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th><?php _e('Title'); ?></th>
            <th><?php _e('Author'); ?></th>
			<th><?php _e('Order'); echo Html::input('submit', 'submit', __('Save'));?></th>
            <th><?php _e('Publish'); ?></th>
            <th><?php _e('Operation'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($articles) > 0) {
        $row_idx = 0;
        foreach ($articles as $article) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td><?php echo Html::input('checkbox', 'news', $article->id); ?></td>
        	<td class="left"><?php echo $article->title; ?></td>
        	<td><?php echo $article->author; ?></td>
			<td><?php echo Html::input('text', 'i_order['.$article->id.']', $article->i_order, 'size=1'); ?></td>
        	<td><?php echo Toolkit::validateYesOrNo($article->published,$article->id,Html::uriquery('mod_news', 'admin_pic',array('_id'=>$article->id)));?></td>
        	<td>
        		<span class="small">
                    <a href="<?php echo Html::uriquery('mod_news', 'admin_edit', array('article_id' => $article->id)); ?>" title="<?php _e('Edit'); ?>"><?php _e('Edit'); ?></a>
                    &nbsp;
        			<a href="#" onclick="delete_article(<?php echo $article->id; ?>);return false;" title="<?php _e('Delete'); ?>"><?php _e('Delete'); ?></a>
        		</span>
        	</td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="7"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<?php
$article_order_form->close();
$running_msg = __('Saving news order...');
$custom_js = <<<JS
$("#adminartlst_stat").css({"display":"block"});
$("#adminartlst_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$article_order_form->addCustValidationJs($custom_js);
$article_order_form->writeValidateJs();
?>
<div class="space"></div>
<?php
include_once(P_TPL.'/common/pager.php');
?>
