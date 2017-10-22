<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>


<div class="art_list">
	<div class="art_list_title"><?php _e('Recent Bulletin List') ?></div>
	<div class="art_list_search"><?php include_once(dirname(__FILE__).'/_search.php'); ?></div>
	<div class="art_list_con">
		<ul>
		<?php
        if (sizeof($bulletins) > 0) {
            $row_idx = 0;
            foreach ($bulletins as $bulletin) {
        ?>
		<li><p class="l_title"><a href="<?php echo Html::uriquery2('mod_bulletin', 'bulletin_content', array('bulletin_id' => $bulletin->id)); ?>" title="<?php echo $bulletin->title; ?>"><?php echo $bulletin->title; ?></a></p><p class="n_time"><?php echo date('Y-m-d H:i', $bulletin->create_time); ?></p></li>
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
		<div class="norecords"><?php _e('No Records!'); ?></div>
		<?php } ?>
		</ul>
	</div>
<?php include_once(P_TPL_VIEW.'/view/common/pager.php'); ?>
</div>