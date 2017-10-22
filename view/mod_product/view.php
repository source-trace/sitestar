<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<script type="text/javascript">
function product_edit1()
{
	$('#tb_mb_article_list1').css('display','block');
}

function product_cancel1()
{
	$('#tb_mb_article_list1').css('display','none');
}
</script>
<?php
$nopermissionstr=__('No Permission');
$urllink="alert('".$nopermissionstr."');return false;";
 if(ACL::isAdminActionHasPermission('mod_product', 'admin_edit')){
    $urllink="popup_window('admin/index.php?_m=mod_product&_a=admin_edit&p_id=".$curr_product->id."','". __('Product')."&nbsp;&nbsp;". __('Edit Content')."','',500,true);return false;";
}
?>

<div class="art_list" <?php if(SessionHolder::get('page/status', 'view') == 'edit'){ echo "onmouseover='product_edit1();' onmouseout='product_cancel1();'"; echo "style='position:relative;'"; } ?>>
	
	<!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_article_list1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
	</div>
	<!-- 编辑时动态触发 【end】-->
	
	<div class="art_list_title product_title"><?php echo $curr_product->name; ?></div>
	<div class="prodview_info"><?php _e('Category'); ?>: <?php echo isset( $curr_product->masters['ProductCategory']->name)? $curr_product->masters['ProductCategory']->name:__('Uncategorised'); ?>&nbsp;
    <?php _e('Publish Time'); ?>: <?php echo date('Y-m-d H:i', $curr_product->create_time); ?>&nbsp;</div>
	<?php if ($curr_product->online_orderable && EZSITE_LEVEL=='2' && EXCHANGE_SWITCH == '1') { ?>
	<div class="artview_intr"><?php echo nl2br($curr_product->introduction); ?></div>
	<?php } ?>
	<?php	?>
	<br />
  <div class="prodview_pic jqzoom" >
	<img name="picautozoom" src="<?php echo $curr_product->feature_smallimg; ?>" alt="<?php echo $curr_product->name; ?>" jqimg="<?php echo $curr_product->feature_img; ?>" />
	</div> 
<?php	?>
	
	
	<div class="prodview_prices">
	<?php if ($curr_product->online_orderable && EZSITE_LEVEL=='2' && EXCHANGE_SWITCH == '1') { ?>
				<?php _e('Price'); ?> : <?php echo CURRENCY_SIGN; ?><font <?php if ($curr_product->discount_price!='0.00'&&$curr_product->discount_price!=$curr_product->price) { ?>style="text-decoration :line-through"<?php }?>><?php echo $curr_product->price; ?></font><br />
				<?php if ($curr_product->discount_price!='0.00'&&$curr_product->discount_price!=$curr_product->price) { ?>
				<?php _e('Discount Price'); ?> : <?php echo CURRENCY_SIGN; ?><?php echo $curr_product->discount_price; ?><br />
				<?php } ?>
				<?php _e('Delivery Fee'); ?> : <?php echo CURRENCY_SIGN; ?><?php echo $curr_product->delivery_fee; ?><br />
				<?php _e('Quantity'); ?> : <?php echo Html::input('text', 'prod_num_'.$curr_product->id, isset($_COOKIE['n_prd'.SessionHolder::get('user/id','0')][$curr_product->id])?$_COOKIE['n_prd'.SessionHolder::get('user/id','0')][$curr_product->id]:1, 'size="4"'); ?><br />				
				<input type="button" class="add_to_cart_b_view" value="<?php _e('Add to cart'); ?>" onclick="add2cart('<?php echo $curr_product->id; ?>');return false;" />
            <?php }else{ ?>
			<?php echo nl2br($curr_product->introduction); ?>
			<?php } ?>
			</div>
	<div class="prodview_content"><?php
	if(strpos($curr_product->description,'fckeditor/upload')>0){
	$pos = strpos($_SERVER['PHP_SELF'],'/index.php');
	$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
	$curr_product->description = str_replace($path,"",$curr_product->description);
	$curr_product->description = str_replace(FCK_UPLOAD_PATH,"",$curr_product->description);
	$curr_product->description = str_replace(FCK_UPLOAD_PATH_AB,"",$curr_product->description);
	}
	echo $curr_product->description; ?></div>
 <?php include_once(ROOT.'/view/common/pagerbytext.php'); ?>


<?php if (defined('SYSVER')) { ?>
        <p id="product_gallery">
            <?php
                $curr_product->loadRelatedObjects(REL_CHILDREN, array('ProductPic'));
                $ext_pics = $curr_product->slaves['ProductPic'];
                
                foreach ($ext_pics as $pic) {          
                	$ext_picss[$pic->id] = $pic->pic;   
                } 
				
				if(!empty($ext_picss)){
                	ksort($ext_picss);   //不能排序处理   "Frontpage"=>"首页"      
				}
             
                if (sizeof($ext_picss) > 0) {
                    foreach ($ext_picss as $pic) {
            ?>
                <a href="<?php echo $pic; ?>" rel="product_gallery" class="thickbox">
                    <img class="product_gallery_pic" src="<?php echo $pic; ?>" alt="<?php echo $curr_product->name; ?>" /></a>
            <?php
                    }
                }
            ?>
        </p>
<?php } ?>
</div>
<table>
<tr><td align="left" height="50" width="800"><?php echo P_BSHARE!="P_BSHARE"?P_BSHARE:'';?></td></tr>

<tr><td align="left" height="50" width="800"><?php echo $nextAndPrevArr; ?></td></tr>

</table>