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
    
    var stat = document.getElementById("adminprdlst_stat");
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
    document.getElementById("adminprdlst_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_product(p_id) {
	if (confirm("<?php _e('Delete the selected product?'); ?>")) {
	    var stat = document.getElementById("adminprdlst_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected product...'); ?>";
		_ajax_request("mod_product", 
			"admin_delete", 
	        {
	            p_id:p_id
	        }, 
			on_del_success, 
			on_del_failure);
	}
}
function delete_products(){
	var arr = document.getElementsByName("product");
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
		delete_product(str);
	}
}
function ck_select(){
	try{
		var el=document.getElementById('ckselect');
		var arr = document.getElementsByName("product");
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
		document.forms['capswform'].hidkeyword.value = kw;
		document.forms['capswform'].submit();
	}
}

function loadCsv() {
	var cls = document.forms['capswform'].cap_sw.value;
	var kyw = document.forms['productorderform'].keyword.value;
	document.forms['batchexportform'].cap_sw.value = cls;
	document.forms['batchexportform'].hidkeyword.value = kyw;
	// for checkbox
	var prdlist = '';
	var arr = document.getElementsByName("product");
	for (var i=0; i<arr.length; i++) {
		if (arr[i].checked == true) {
			prdlist = prdlist + arr[i].value + ',';
		}
	}
	document.forms['batchexportform'].prd_ids.value = prdlist.replace(/,$/g, '');
	document.forms['batchexportform'].submit();
}

function copy_data(){
	var chk_value =[];    
    $('input[name="product"]:checked').each(function(){    
   		chk_value.push($(this).val());    
  	});
	if(chk_value==''){
		alert('<?php _e('Choose Product please!');?>');
		return false;
	}
	
	show_iframe_win('index.php?_m=mod_product&_a=copy_product&product='+chk_value,'<?php _e('Copy Product to language');?>','610','118');
	return false;
}

function move_data(){
	var chk_value2 =[];    
    $('input[name="product"]:checked').each(function(){    
   		chk_value2.push($(this).val());    
  	});
	if(chk_value2==''){
		alert('<?php _e('Choose Product please!');?>');
		return false;
	}
	
	show_iframe_win('index.php?_m=mod_product&_a=move_product&product='+chk_value2,'<?php _e('Move Product to category');?>','610','518');
	return false;
}
//-->
</script>
<div class="status_bar">
	<span id="adminprdlst_stat" class="status" style="display:none;"></span>
</div>
<ul style="margin-left:10px;">
<?php
    if(ACL::isAdminActionHasPermission('mod_category_p', 'admin_list')){
?>	
        <li><a class="iconfl" href="<?php echo Html::uriquery('mod_category_p', 'admin_list', array('goto' => '10000')); ?>" title=""><?php _e('Manage Categories'); ?></a></li>
<?php
}
?>	
 <?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_add')){
?>     
        <li><a class="icontj" href="<?php echo Html::uriquery('mod_product', 'admin_add'); ?>" title=""><?php _e('Add Product');?></a></li>
<?php
}
?>
<?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_batch')){
?>          
        <li><a class="icontj" href="<?php echo Html::uriquery('mod_product', 'admin_batch'); ?>" title=""><?php _e('Batch Import');?></a></li>
<?php
}
?>	
<?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_export')){
?>   
        <li><?php
	$batch_export_form = new Form('index.php?_m=mod_product&_a=admin_export', 'batchexportform');
	$batch_export_form->open();
//	echo Html::input('hidden', 'act', '9999');
	echo Html::input('hidden', 'cap_sw', '0');
	echo Html::input('hidden', 'prd_ids', '0');
	echo Html::input('hidden', 'hidkeyword', '');
	?><a class="icontj" href="javascript:void(0);" onclick="loadCsv();return false;" title=""><?php _e('Batch Export'); ?></a>
	<?php $batch_export_form->close();?></li>
<?php
}
?>	
<?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_delete')){
?>  	
        <li><a class="iconsc" href="javascript:void(0)" onclick="delete_products();"><?php _e('Delete Selected'); ?></a></li>
<?php
}
?>	
        <li style="margin-top:14px;margin-right:15px;"><?php include_once(dirname(__FILE__).'/_category_switch.php'); ?></li>
	<li style="margin-top:14px;margin-right:5px;"><?php include_once(P_TPL.'/common/language_switch.php'); if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en'){ ?></li><li style="margin-top:16px;line-height:0px;">
	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Language note');?>"/>
	<?php } ?></li> 
<?php
$product_order_form = new Form('index.php', 'productorderform', 'check_login_info');
$product_order_form->p_open('mod_product', $next_action, '_ajax');
?>
	<li style="margin-top:0;_margin-top:14px;"><?php echo Html::input('text', 'keyword', $keyword, 'class="textinput" style="width:70px;"'); echo '&nbsp;&nbsp;'.Html::input('button', 'search', '', ' class="btn" onclick="keyword_search()"');?></li>
	<br />
<?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_order')){
?> 	
    <li><input style="_margin-top:10px;margin-top:10px;" type="submit" value="<?php _e('Save Order');?>" id="submit" name="submit"/></li>
<?php
}
?>		
   
	    <li>&nbsp;&nbsp;&nbsp;&nbsp;<input style="_margin-top:14px;" type="button" value="<?php _e('Copy Product');?>" id="copyToL" name="copyToL" onclick="copy_data();"/></li>
		<li>&nbsp;&nbsp;&nbsp;&nbsp;<input style="_margin-top:14px;" type="button" value="<?php _e('Move Product');?>" id="copyToL" name="copyToL" onclick="move_data();"/></li>


</ul>

<table class="form_table_list" id="admin_product_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
	<thead>
		<tr>
		    <th width="10%"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th><?php _e('Name'); ?></th>
            <th><?php _e('Category');?></th>
			<th width="50"><?php _e('Order');?></th>
            <th><?php _e('Show at frontpage'); ?></th>
            <th><?php _e('Recommend'); ?></th>
            <th width="70"><?php _e('Member only access'); ?></th>
			<?php if (intval(EZSITE_LEVEL) > 1) { ?>
            <th width="70"><?php _e('Online Orderable'); ?></th>
			<?php } ?>
            <th><?php _e('Publish Date');//_e('Create Time'); ?></th>
            <th><?php _e('Edit'); ?></th>
            <th><?php _e('Delete'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($products) > 0) {
        $row_idx = 0;
        foreach ($products as $product) {
        	$product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>" valign="middle">
        	<td><?php echo Html::input('checkbox', 'product', $product->id); ?></td>
        	<td>
             <?php
            if($lang_sw==$default_lang){
			?>
            <a href="../<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $product->id)); ?>" target="_blank" title="<?php echo $product->name; ?>"><?php echo subString($product->name,0,20); ?></a>
             <?php
            }else{
			?>
           <a href="<?php echo Html::uriquery2('mod_product', 'admin_edit', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>"><?php echo subString($product->name,0,20); ?></a>
            <?php
			}
			?>
            </td>
        	<td><?php echo isset($product->masters['ProductCategory']->name)?$product->masters['ProductCategory']->name:__('Uncategorised'); ?></td>
			<td><?php echo Html::input('text', 'i_order['.$product->id.']', $product->i_order, 'class="textinput" style="width:20px;"'); ?></td>
			
        	<td><?php //echo Product::explain_publish($product);
             $needchange=true;
            if(!ACL::isAdminActionHasPermission('mod_product', 'admin_pic')) $needchange=false;
    	echo Toolkit::validateYesOrNo($product->published,$product->id,"index.php?_m=mod_product&_a=admin_pic&_r=ajax&_id=".$product->id."&_tag=pic", $needchange); 
    			?></td>
    			
    		<td>
    		<?php //echo Product::explain_publish($product);
             $needchange=true;
            if(!ACL::isAdminActionHasPermission('mod_product', 'admin_pic')) $needchange=false;
    	echo Toolkit::validateYesOrNo($product->recommended,$product->id,"index.php?_m=mod_product&_a=admin_pic&_r=ajax&_id=".$product->id."&_tag=recommended", $needchange); 
    			?>
			</td>
			
			<td>
			<?php //echo Product::explain_publish($product);
             $needchange=true;
             $for_roles_tag=$product->for_roles=='{member}{admin}{guest}'?0:1;            
            if(!ACL::isAdminActionHasPermission('mod_product', 'admin_pic')) $needchange=false;
    	echo Toolkit::validateYesOrNo($for_roles_tag,$product->id,"index.php?_m=mod_product&_a=admin_pic&_r=ajax&_id=".$product->id."&_tag=for_roles", $needchange); 
    			?>
			</td>
			<?php if (intval(EZSITE_LEVEL) > 1) { ?>
			<td>
				<?php //echo Product::explain_publish($product);
             $needchange=true;
            if(!ACL::isAdminActionHasPermission('mod_product', 'admin_pic')) $needchange=false;
    	echo Toolkit::validateYesOrNo($product->online_orderable,$product->id,"index.php?_m=mod_product&_a=admin_pic&_r=ajax&_id=".$product->id."&_tag=online_orderable", $needchange); 
    			?>
			</td>
			<?php } ?>
        	<td><?php echo date('y-n-j g:i', $product->create_time); ?></td>
        	<td>
<?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_edit')){
?>                     
        		<a href="<?php echo Html::uriquery('mod_product', 'admin_edit', array('p_id' => $product->id)); ?>" title="<?php _e('Edit'); ?>"><img style="border:none;" alt="<?php _e('Edit');?>" src="<?php echo P_TPL_WEB; ?>/images/edit.gif"/></a>
<?php
}
?>       
            </td>
        	<td>
  <?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_delete')){
?>                   
        		<a href="#" onclick="delete_product(<?php echo $product->id; ?>);return false;" title="<?php _e('Delete'); ?>"><img style="border:none;" alt="<?php _e('Delete');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
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
    		<td colspan="11"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<?php
$product_order_form->close();
$running_msg = __('Saving product order...');
$custom_js = <<<JS
$("#adminprdlst_stat").css({"display":"block"});
$("#adminprdlst_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$product_order_form->addCustValidationJs($custom_js);
$product_order_form->writeValidateJs();
?>
<?php
include_once(P_TPL.'/common/pager.php');
?>
