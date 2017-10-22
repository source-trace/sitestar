<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<div class="cart_con">
<?php 
if((EZSITE_LEVEL == 2) && (EXCHANGE_SWITCH == 1)){
if(MOD_REWRITE=='3'){
?>
<a href="<?php echo Html::uriquery('mod_cart', 'viewcart'); ?>"><?php _e('Shopping Cart');?>： <span id="disp_n_prds"><?php echo $n_prds; ?></span>&nbsp; <?php _e('Items'); ?></a>
<?php }else{ ?>
<a href="<?php echo Html::uriquery2('mod_cart', 'viewcart'); ?>"><?php _e('Shopping Cart');?>： <span id="disp_n_prds"><?php echo $n_prds; ?></span>&nbsp; <?php _e('Items'); ?></a>
<?php } }?>
</div>
	<div class="list_bot"></div>
<div class="blankbar"></div>