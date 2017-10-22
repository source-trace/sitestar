<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript">
<!--
function ContentSize(size)
{
	var obj=document.getElementById("artview_content");
	obj.style.fontSize=size+"px";
}
-->
</script>

<div class="artview">
	<div class="artview_title"><?php echo $curr_news->title; ?></div>
	<div class="artview_info"><?php _e('Source'); ?>: <?php echo $curr_news->source; ?>&nbsp;&nbsp;&nbsp;<?php _e('Publish Time'); ?>: <?php echo date('Y-m-d H:i', $curr_news->create_time); ?>&nbsp;&nbsp;&nbsp;<?php echo $curr_news->v_num.' '.__('Views'); ?>&nbsp;&nbsp;&nbsp;文字大小: <a href="javascript:ContentSize(16)">大</a> <a href="javascript:ContentSize(14)">中</a> <a href="javascript:ContentSize(12)">小</a></div>
	
	<div id="artview_content"><?php echo $curr_news->content; ?></div>
	<div class="blankbar1"></div>
</div>