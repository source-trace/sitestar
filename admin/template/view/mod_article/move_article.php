<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
function check_sub($id){
	$db =& MysqlConnection::get();
    	$sql = 'select * from '.Config::$tbl_prefix.'article_categories where article_category_id='.intval($id);
    	$res = $db->query($sql);
    	$cat_arr = $res->fetchRow();
		if(!empty($cat_arr)) return true; else return false;
}
?>
<table class="form_table_list" id="admin_article_list" width="600" border="0" cellspacing="1" cellpadding="2" >
<form name="form1" id="form1" action="index.php?_m=mod_article&_a=save_move" method="post">
		<tr>
			<td width="30%">
			<input type="hidden" name="article" value="<?php echo $article;?>" />
			<?php _e('Choose article category'); ?>
			</td>
		    <td width="50%" align="left">
			<div style="display:block; text-align:left">
			<?php 
			foreach($cates as $k=>$cate){
				if(!in_array($k,$cate_ids)){
			?>
			<?php echo $cate;?><input type="radio" name="cate" value="<?php echo $k;?>" /><br />
			<?php 
				}else{
					if($cate==0 && !check_sub($k)){
				?>
				<?php echo $cate;?><input type="radio" name="cate" value="<?php echo $k;?>" /><br />
				<?php
					}else{
			?>
				<?php echo $cate;?><br />
			<?php
					}
				}
			}
			?>
			</div>
			</td>
        </tr>
		<tr height="50">
			<td colspan="2" height="50">
    <div style="margin-right:200px;">
	<?php
	if(Toolkit::isadmin()){			
	?>
	<input type="hidden" name="token" id="token" value="<?php echo $token;?>">
	<?php }?>
	<input style="_margin-top:14px;" type="submit" value="<?php _e('Ok');?>" id="submit" name="submit"/></div>
			</td>
        </tr>
    </form>
    </tbody>
</table>
