<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="blockwrapper">
    <a href="<?php echo Html::uriquery('mod_cart', 'viewcart'); ?>">
        <img src="<?php echo P_TPL_WEB; ?>/images/cart.gif" border="0" class="imgvbot" />
        <span id="disp_n_prds"><?php echo $n_prds; ?></span>
        <?php _e('Items'); ?>
    </a>
</div>
