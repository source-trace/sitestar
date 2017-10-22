<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($products) > 0) {
?>
<div class="prdshow_block_h">
	<?php
	foreach ($products as $product) {
	?>
		<div class="prd_block">
        	<a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>">
        	<img src="<?php echo $product->feature_smallimg; ?>" alt="" border="0" width="80" height="80" class="prdlist_thumb" /></a>
        	<br />
        	<a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>">
        	<?php echo $product->name; ?></a>
		</div>
	<?php
	}
	?>
	<div class="clearer"></div>
</div>
<?php
}
?>