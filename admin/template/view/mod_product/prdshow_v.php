<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($products) > 0) {
?>
<div class="prdshow_block_v">
	<?php
	foreach ($products as $product) {
	?>
		<div class="prd_block">
        	<div class="l">
            	<a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>">
                <img src="<?php echo $product->feature_smallimg; ?>" alt="" border="0" class="prdlist_thumb" /></a>
            </div>
            <div class="r">
            	<div class="tle">
                    <a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>">
                    <?php echo $product->name; ?></a>
                </div>
            	<div class="cnt">
                    <?php echo Toolkit::substr_MB(strip_tags($product->description), 0, 100); ?>
                </div>
            </div>
            <div class="clear"></div>
		</div>
        <div class="air"></div>
	<?php
	}
	?>
</div>
<?php
}
?>