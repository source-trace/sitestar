<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle alignleft"><?php echo $page_title; ?></div>
</div>
<?php if(sizeof($fls) > 0) { ?>
<div class="flinkbody">
<?php foreach ($fls as $fl) { ?>
<a href=<?php echo $fl->fl_addr;?> target='_blank'><img src="upload/image/<?php echo $fl->fl_img ?>" border="0"></a>
<?php } ?>
</div>
<?php } ?>