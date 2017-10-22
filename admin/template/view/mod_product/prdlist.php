<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php echo $category->name; ?></div>
    <div class="rightblock">
        <?php include_once(dirname(__FILE__).'/_search.php'); ?>
    </div>
    <div class="clearer"></div>
</div>
<div class="contentbody">
    <table cellspacing="0" class="front_list_table product_list_table">
        <tbody>
        <?php
        if (sizeof($products) > 0) {
            $row_idx = 0;
            foreach ($products as $product) {
                $product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
        ?>
            <tr valign="top" class="row_style_<?php echo $row_idx; ?>">
                <td rowspan="2">
                    <a href="<?php echo $product->feature_img; ?>" target="_blank" title="<?php _e('Click to view large image!'); ?>">
                    <img src="<?php echo $product->feature_smallimg; ?>" class="prodthumb" border="0" /></a>
                </td>
                <td>
                    <div class="prodtitle">
                        <a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>">
                            <?php echo $product->name; ?></a>
                    </div>
                    <div class="prodcate mediumgray">
                        <?php _e('Category'); ?>: <a href="<?php echo Html::uriquery('mod_product', 'prdlist', array('cap_id' => $product->masters['ProductCategory']->id)); ?>">
                            <?php echo $product->masters['ProductCategory']->name; ?></a>
                    </div>
                </td>
            </tr>
            <tr valign="top" class="row_style_<?php echo $row_idx; ?>">
                <td class="proddesc">
                    <?php echo Toolkit::substr_MB(strip_tags($product->description), 0, 72).'...'; ?>
                    &nbsp;
                    <a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>">
                        <img src="<?php echo P_TPL_WEB; ?>/images/more.gif" border="0" /></a>
                </td>
            </tr>
            <tr class="row_style_<?php echo $row_idx; ?>">
                <td colspan="2"><div class="separator"></div></td>
            </tr>
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
            <tr class="row_style_0">
                <td colspan="2" class="aligncenter"><?php _e('No Records!'); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="contentpager">
    <?php include_once(P_TPL.'/common/pager.php'); ?>
</div>