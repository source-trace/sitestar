<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<?php 
if(SessionHolder::get('page/status', 'view') == 'edit')
{
	echo <<<JS
<script type="text/javascript" language="javascript">
function product_edit()
{
	$('#tb_mb_product1').css('display','block');
}
function product_cancel()
{
	$('#tb_mb_product1').css('display','none');
}
</script>
JS;
	$nopermissionstr=__('No Permission');
         $urllink="alert('".$nopermissionstr."');return false;";
	if(empty($cap_id))
	{   
		$str_url = 'admin/index.php?_m=mod_product&_a=admin_list';
		$str_title = __('Product List');
                    if(ACL::isAdminActionHasPermission('mod_product', 'admin_list')){
                            $urllink="popup_window('$str_url','$str_title &nbsp;&nbsp;". __('Edit Content')."','',500,true);return false;";
                    }
	}
	else
	{
		$str_url = "admin/index.php?_m=mod_category_p&_a=admin_edit&cap_id=$cap_id";
		$str_title = __('Product Categories');
                     if(ACL::isAdminActionHasPermission('mod_category_p', 'admin_edit')){
                            $urllink="popup_window('$str_url','$str_title &nbsp;&nbsp;". __('Edit Content')."');return false;";
                    }
	}
           
}
?>

<div class="art_list" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;' onmouseover='product_edit();' onmouseout='product_cancel();'";?>>

	<!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_product1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<?php if(empty($cap_id)){?>
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
		<?php } else {?>
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
		<?php }?>
	</div>
	<!-- 编辑时动态触发 【end】-->

	<div class="art_list_title"><?php if(isset($category->name)){echo $category->name;}else{ echo __("Product Center");} ?></div>
	<div class="art_list_search"><?php include_once(dirname(__FILE__).'/_search.php'); ?></div>
	<div class="prod_list_con">
        <?php
        if (sizeof($products) > 0) {
            $row_idx = 0;
            foreach ($products as $product) {
                $product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
        ?>
			<div class="prod_list_list">
				<div class="prod_list_pic"><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>" target="_blank"><img name="picautozoom" src="<?php echo $product->feature_smallimg; ?>" alt="<?php echo $product->name; ?>" border="0" /></a></div>
				<div class="prod_list_name"><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>" target="_blank"><?php echo $product->name; ?></a></div>
				<?php
					$product_ProductCategory_id='';
					$product_ProductCategory_name='';
					if(isset($product->masters['ProductCategory']->id)){
						$product_ProductCategory_id=$product->masters['ProductCategory']->id;
					}
					if(isset($product->masters['ProductCategory']->name)){
						$product_ProductCategory_name=$product->masters['ProductCategory']->name;
					}
				?>
				<div class="prod_list_type"><?php _e('Category'); ?>: <a href="<?php echo Html::uriquery2('mod_product', 'prdlist', array('cap_id' => $product_ProductCategory_id)); ?>"><?php echo $product_ProductCategory_name; ?></a></div>
				<div class="prod_list_intr"><?php echo Toolkit::substr_MB(strip_tags($product->introduction), 0, 72).((Toolkit::strlen_MB(strip_tags($product->introduction))>72)?'...':''); ?></div>
			</div>
			<?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
		<div class="norecords"><?php _e('No Records!'); ?></div>
		<?php } ?>
	</div>
	<?php include_once(P_TPL_VIEW.'/view/common/pager.php'); ?>
</div>
