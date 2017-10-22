<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
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
function set_val(){
	var a = document.getElementsByName("product");
	var prd_val = [];
	var prd_html= "";
	var prd_id = '';
	var prd_name='';
	for(var i = 0 ;i<a.length;i++){
		if(a[i].checked){
			prd_val=a[i].value.split("-");
			prd_id = prd_val[0];
			prd_name = prd_val[1];
			prd_html += '<li><input type="hidden" name="ex_params[mar_prd_id2][]" value="'+prd_id+'" />'+prd_name+""+"<font style='color:red;cursor:pointer;' onclick='prd_remove(this);'>&nbsp;&nbsp;&nbsp;删除</font></li>";

		}
	}
	prd_html += '<input type="hidden" name="ex_params[prd_list_tag]" value="1" id="prd_list_tag" />';
	//parent.document.getElementById("getPrdList").innerHTML='';
	parent.document.getElementById("getPrdList").innerHTML+=prd_html;
	parent.tb_remove();
}
//-->
</script>
<div class="status_bar">
	<span id="adminprdlst_stat" class="status" style="display:none;"></span>
</div>
<ul style="margin-left:10px;">

<?php
$product_order_form = new Form('index.php', 'productorderform', 'check_login_info');
$product_order_form->p_open('mod_product', $next_action, '_ajax');
?>
<?php
    if(ACL::isAdminActionHasPermission('mod_product', 'admin_order')){
?> 	
    <li>
    <input style="_margin-top:10px;margin-top:10px;" onclick="set_val()" type="button" value="<?php _e('Save Choose');?>" id="submit" name="submit"/>
    
    </li>
<?php
}
?>		
</ul>

<table class="form_table_list" id="admin_product_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
	<thead>
		<tr>
		    <th width="10%"><?php echo Html::input('checkbox', 'ckselect','','onclick=ck_select()'); ?></th>
            <th><?php _e('Name'); ?></th>
            <th><?php _e('Category');?></th>
			<!--th width="50"><?php _e('Order');?></th-->
            <th><?php _e('Publish Date');//_e('Create Time'); ?></th>
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
        	<td><?php echo Html::input('checkbox', 'product', $product->id."-".$product->name); ?></td>
        	<td>
             <?php
            if($lang_sw==$default_lang){
			?>
            <a href="../<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $product->id)); ?>" target="_blank"><?php echo $product->name; ?></a>
             <?php
            }else{
			?>
           <a href="<?php echo Html::uriquery('mod_product', 'admin_edit', array('p_id' => $product->id)); ?>" title="<?php _e('Edit'); ?>"><?php echo $product->name; ?></a>
            <?php
			}
			?>
            </td>
        	<td><?php echo isset($product->masters['ProductCategory']->name)?$product->masters['ProductCategory']->name:__('Uncategorised'); ?></td>
			<!--td><?php echo Html::input('text', 'i_order['.$product->id.']', $product->i_order, 'class="textinput" style="width:20px;"'); ?></td-->
        	<td><?php echo date('y-n-j g:i', $product->create_time); ?></td>
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
<br><br>