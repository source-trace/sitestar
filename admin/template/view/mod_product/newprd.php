<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$cols = $p_cols;

$n_prd = sizeof($products);
if ($n_prd > 0){
?>
<div class="prodlistwrapper">
<?php for ($i = 0; $i < $n_prd; $i++) { ?>
    <div class="prodinfo">
        <a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $products[$i]->id)); ?>">
            <img class="prodthumb" src="<?php echo $products[$i]->feature_smallimg;?>" border="0" /></a><br />
        <a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $products[$i]->id)); ?>" class="prodnamelink">
            <?php echo $products[$i]->name;?></a>
        <span class="proddesc"><?php echo Toolkit::substr_MB($products[$i]->introduction, 0, 56);?>...</span>
    </div>
    <?php if ($i % $cols == ($cols - 1)) { ?><div class="clearer"></div><?php } ?>
<?php } ?>
</div>
<?php } ?>
<div class="clearer"></div>
