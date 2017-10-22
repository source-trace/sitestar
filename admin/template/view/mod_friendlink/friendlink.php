<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($friendlinks) > 0) {
?>
<div class="flinkblock">
    <h3 class="usr_blk_t"><?php _e('Friend Links'); ?></h3>
    <div class="flinks">
<?php
    foreach ($friendlinks as $friendlink) {
        $friendlink_html = '<a href='.$friendlink->fl_addr.' target="_blank" title="'.$friendlink->fl_name.'">'
        .'<img src='.'upload/image/'.$friendlink->fl_img.' border="0" alt="'.$friendlink->fl_name.'"  width="160" /></a>';
        echo $friendlink_html;
    }
?>
    </div>
</div>
<?php } ?>
