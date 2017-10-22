<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<table class="form_table_list" id="admin_article_list" width="600" border="0" cellspacing="1" cellpadding="2" height="85" >
<?php 
//判断语言，如果只有一种，则提示添加语言，否则显示语言列表以供选择
if(count($lans)>1){
?>
<form name="form1" id="form1" action="index.php?_m=mod_product&_a=save_copy" method="post">
		<tr>
			<td width="30%">
			<input type="hidden" name="product" value="<?php echo $product;?>" />
			<?php _e('Choose language'); ?>
			</td>
		    <td width="50%">
			<?php 
			foreach($lans as $k=>$lan){
				if($lan->locale==SessionHolder::get("mod_product/_LOCALE")) continue;
			?>
			<?php echo Html::input('checkbox', 'lan[]', $lan->locale); echo "&nbsp;".$lan->name; ?>&nbsp;&nbsp;&nbsp;
			<?php 
			}
			?>
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
	<?php
	
    } else {
    ?>
    	<tr class="row_style_0">
    		<td colspan="2"><a href="index.php?_m=mod_lang&_a=admin_list"><?php _e('Add language'); ?></a></td>
    	</tr>
    <?php
    }
    ?>
    </tbody>
</table>
