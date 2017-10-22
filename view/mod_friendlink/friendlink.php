<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($friendlinks) > 0) {
?>
<div class="list_main">
				<div class="list_con">
					<ul class="flink_index">
<?php
    foreach ($friendlinks as $friendlink) {
    	if($fl_type != 2){
        $friendlink_html = '<li><a href='.$friendlink->fl_addr.' target="_blank" title="'.$friendlink->fl_name.'">'
        .'<img src='.'upload/image/'.$friendlink->fl_img.' border="0" alt="'.$friendlink->fl_name.'"  width="160" /></a></li>';
    	}else{
    		$friendlink_html = '<li><a href='.$friendlink->fl_addr.' target="_blank" title="'.$friendlink->fl_name.'">'
        	.$friendlink->fl_name.'</a></li>';
    	}
        echo $friendlink_html;
    }
?>	
</ul><div class="blankbar1"></div>
				</div>
	<div class="list_bot"></div>
</div>
<div class="blankbar"></div>
<?php } ?>
