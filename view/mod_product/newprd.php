<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
// for division by zero
//$cols = $p_cols;
$cols = intval($p_cols) ? intval($p_cols) : 3;
$n_prd = sizeof($products);
?>

		<div class="newprod">
				<div class="newprod_top"></div>
				<div class="newprod_con">
			
<?php
if ($n_prd > 0){
?>
<div class="pro_over">
	<table class="media_grid" cellspacing="4" width="100%">
		<?php
		for ($i = 0; $i < $n_prd; $i++) {
			if ($i % $cols == 0) {
				echo '<tr>';
			}
			$_product=$products[$i];
			$_product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
		?>
			<td>
            <div class="newprod_list">
				<div class="newprod_pic"><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $products[$i]->id)); ?>" title="<?php echo $products[$i]->name;?>"><img name="picautozoom" border="0" alt="<?php echo $products[$i]->name;?>" src="<?php echo $products[$i]->feature_smallimg;?>" class="prodthumb" /></a></div>
                <div class="newprod_name"><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $products[$i]->id)); ?>"><?php echo $products[$i]->name;?></a></div>
                <?php if( (EZSITE_LEVEL=='2') && $show_price){?><div class="newprod_price"  <?php if( (EZSITE_LEVEL=='2') && $show_price2){?>style="text-decoration :line-through"<?php }?>><?php echo CURRENCY_SIGN; ?><?php echo $products[$i]->price;?></div><?php }?>
				  <?php if( (EZSITE_LEVEL=='2') && $show_price2){?><div class="newprod_price" ><?php _e('Discount：'); ?><?php echo CURRENCY_SIGN; ?><?php echo $products[$i]->discount_price;?></div><?php }?>
				<?php if($show_cate){?><div class="newprod_intr"><?php _e('Category'); ?>: 
                            <?php 
                                if (isset($_product->masters['ProductCategory']->id)){  
                            ?>
                            <a href="<?php echo Html::uriquery2('mod_product', 'prdlist', array('cap_id' => $_product->masters['ProductCategory']->id)); ?>"><?php echo $_product->masters['ProductCategory']->name; ?></a>
                            <?php
                            }else{
                                     _e('Uncategorised');
                            }
                           ?>
                           
                    </div><?php }?>
                           
                    </div>
                <div class="blankbar1"></div>
            </div>
            
			</td>
		<?php
			if (($i % $cols) == ($cols - 1)) {
				echo '</tr>';
			}
		}
		if ($i % $cols != 0) {
			for ($j = 0; $j < ($cols - $i); $j++) {
				echo '<td width="'.intval(100 / $cols).'%">&nbsp;</td>';
			}
			echo '</tr>';
		}
		?>
	</table>
</div>
<?php
} else {
	echo __('No Records!');
}
?>
			</div>
				<div class="list_bot"></div>
			</div>
			<div class="blankbar"></div>


