<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
#cate_new{background-color:#F8F8F8;}
#cate_new .selected{ background:url(images/selected_bg.jpg);color:#fff ;}
#cate_new .selected a{color:#fff !Important;}
#cate_new ul{height:33px;line-height:33px;list-style:none; margin:0 0 0 30px !Important; padding:5px 0; font-size:12px;}
#cate_new li{float:left;text-align:center;cursor:pointer;margin:0 !Important; padding:0; width:135px; height:33px; line-height:33px;background:url(images/selectli_bg.jpg)}
#cate_new li a{color:#000 !Important;display:block;width:135px; padding:0px !Important;}
#cate_new li a:hover{color:#fff !Important;}
#cate_new li:hover{background:url(images/selected_bg.jpg);}
</style>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	$('#answer5').cluetip({splitTitle: '|',width: '210px',height:'33px'});
});
</script>
<div id="cate_new"><ul><li><a href="index.php?_m=mod_email&_a=admin_list"><?php _e("Site note");?></a></li>

<li class="selected"><a href="index.php?_m=mod_email&_a=email_list"><?php _e("E-mail");?></a></li></ul></div>

<br />
<table class="form_table_list" id="admin_bulletin_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;margin-top:0;">
	<thead>
		<tr>
            <th width="220" style="word-break:break-all;word-wrap:break-word;"><?php _e('Title'); ?></th>
            <th width="330" style="word-break:break-all;word-wrap:break-word;"><?php _e('Send username'); ?>&nbsp;&nbsp;<img id="answer5" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Red failed to send the user');?>"/></th>
            <th width="170" style="word-break:break-all;word-wrap:break-word;"><?php _e('Sended time'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($notes) > 0) {
        $row_idx = 0;
        foreach ($notes as $note) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
        	<td width="220" style="word-break:break-all;word-wrap:break-word; text-align:left;"><a href="index.php?_m=mod_email&_a=detail&id=<?php echo $note->id; ?>&type=email"><?php echo $note->title; ?></a></td>
        	<td width="330" style="word-break:break-all;word-wrap:break-word; text-align:left">  <?php 
			$u_str = '';
			
			foreach ($users as $k=>$v){
				if($note->title==$v['title']){
					if($v['is_ok']==1){
						$u_str .= $v['user_name'].',';
					}else{
						$u_str .= '<font color="#FF0000">'.$v['user_name'].'</font>&nbsp;'.',';
					}
					
				}
			}
			echo substr($u_str,0,strlen(trim($u_str))-1);
			?>          </td>
         	<td  width="170" style="word-break:break-all;word-wrap:break-word;"><?php echo date("Y-m-d H:i:s",$note->create_time); ?></td>
       </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="5"><?php _e('No Records!'); ?></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
<?php
include_once(P_TPL.'/common/pager.php');
?>
