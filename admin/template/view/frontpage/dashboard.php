<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
		    <tr>
				<td><div class="title"><?php _e('Dashboard'); ?></div></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="space"></div>
<div id="dash_wrapper">
    <div id="dash_main">
        <div class="dash_shortcuts">
            <?php
            $cols = 3;
            for ($i = 0; $i < sizeof($shortcuts); $i++) {
            ?>
            <a href="<?php echo Html::uriquery($shortcuts[$i]->module, $shortcuts[$i]->action); ?>" class="shortcut_button">
                <img src="images/<?php echo $shortcuts[$i]->image; ?>" border="0" />
            </a>
            <?php
                if ($i % $cols == $cols - 1) { echo "<div class=\"space16\"></div>\n"; }
            }
            ?>
            <div class="space"></div>
        </div>
    </div>
    
    <div id="dash_side">
        <div class="dash_block">
            <h3><?php _e('Admin Login'); ?></h3>
            <div class="dash_block_wrapper">
                <p>
                <?php _e('Last login time'); ?>: <?php echo date('Y-m-d H:i', SessionHolder::get('user/lastlog_time')); ?><br />
                <?php _e('Last login IP'); ?>: <?php echo SessionHolder::get('user/lastlog_ip'); ?><br />
                </p>
            </div>
        </div>
        <div class="space"></div>
        <div class="dash_block">
            <h3><?php _e('System Info'); ?></h3>
            <div class="dash_block_wrapper">
                <p>
                <?php _e('Current Locale'); ?>: <?php echo SessionHolder::get('_LOCALE'); ?><br />
                <?php _e('Article Categories'); ?>: <?php echo $cate_a_count; ?><br />
                <?php _e('Articles'); ?>: <?php echo $article_count; ?><br />
                <?php _e('Product Categories'); ?>: <?php echo $cate_p_count; ?><br />
                <?php _e('Products'); ?>: <?php echo $prod_count; ?><br />
                </p>
            </div>
        </div>
    </div>
    
    <div class="space"></div>
</div>
