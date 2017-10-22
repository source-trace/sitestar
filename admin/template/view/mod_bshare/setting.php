<?php if(!defined('IN_CONTEXT')) die('access violation error!');?>
<form name="bshare_chkform" method="post" action="index.php?_m=mod_bshare&_a=save">

  <table class="form_table_list" id="admin_product_list" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
    <tr>
      <td align="left"><?php _e('Your bShare services information');?>：</td>
	  <td align="left"><div style="margin-left:10px; text-align:left;">
	  <?php _e('Account');?>: <?php echo $account;?><br />
    UUID: <?php echo $uuid;?></div>
	  </td>
	  <td></td>
    </tr>
   
    <tr>
	<td><?php _e('Bshare source code');?>(<?php _e('Article detail');?>):</td>
      <td>
      <textarea name="a_code" cols="60" rows="6" style="height:200px;width:460px;"><?php echo A_BSHARE!="A_BSHARE"?A_BSHARE:'';?></textarea><p style="margin:5px auto;">
     </td>
	 <td> <input type="button" value="<?php _e('Get code');?>" id="selectStyle" name="selectStyle" onclick="show_iframe_win('http://www.bshare.cn/moreStylesEmbed?uuid=<?php echo $uuid;?>&bp=<?php echo $codeOrder;?>','<?php _e('Bshare');?>','800','500');" /></td>
    </tr>
	
	<tr>
	<td><?php _e('Bshare source code');?>(<?php _e('Product detail');?>):</td>
      <td>
      <textarea name="p_code" cols="60" rows="6" style="height:200px;width:460px;"><?php echo P_BSHARE!="P_BSHARE"?P_BSHARE:'';?></textarea><p style="margin:5px auto;">
     </td>
	 <td> <input type="button" value="<?php _e('Get code');?>" id="selectStyle" name="selectStyle" onclick="show_iframe_win('http://www.bshare.cn/moreStylesEmbed?uuid=<?php echo $uuid;?>&bp=<?php echo $codeOrder;?>','<?php _e('Bshare');?>','800','500');" /></td>
    </tr>
	
	<tr>
	<td><?php _e('Bshare source code');?>(<font color="#FF0000"><?php _e('Global');?></font>):</td>
      <td>
      <textarea name="g_code" cols="60" rows="6" style="height:200px;width:460px;"><?php echo BSHARE!="BSHARE"?BSHARE:'';?></textarea><p style="margin:5px auto;">
     </td>
	 <td> <input type="button" value="<?php _e('Get code');?>" id="selectStyle" name="selectStyle" onclick="show_iframe_win('http://www.bshare.cn/moreStylesEmbed?uuid=<?php echo $uuid;?>&bp=<?php echo $codeOrder;?>','<?php _e('Bshare');?>','800','500');" /></td>
    </tr>
    
    <tr>
      <td colspan="3"> 
	 
	  &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="<?php _e('Save code');?>" id="setStyle" name="setStyle" />
      </td>
	  
    </tr>
   
  </table>
  </form>
<script language="javascript">
var $ = parent.$;
$(function(){
	var $dialog = $(parent.document).find('#wp-bshare_setting'),
	$iframe = $dialog.find('iframe'),newW = 620,newH = 560;
	// 调整当前Dialog尺寸
	$dialog.width(newW);
	$iframe.width(newW-26).height(newH);
	// Reset position
	var $frmdoc = $iframe.contents(),framH = newH + 60;
	// 自定义样式
	var $txtarea = $frmdoc.find('textarea');
	
	$txtarea.bind('selected',function(){
		$(this).focus().select();
	});
	// 设定样式
	$frmdoc.find('#setStyle').click(function(){
		var newval = $txtarea.val(),code = $.trim(newval);
		if ((code.length == 0) || (code == parent.bshare_translate('bshare nocode'))) {
			alert(parent.bshare_translate('Enter style or code'));
			$txtarea.focus();
			return false;
		}
		// Un/Redo
		
		setTimeout(function(){
			$dialog.triggerHandler('wpdialogclose');
		},100);
	});
	
	// 设置默认值
//	$frmdoc.find('td.bshare_preview')[0].innerHTML = oldval;
});
// 日期比较函数
function checkDate(checkStartDate,checkEndDate){
    var arys1 = new Array();
    var arys2 = new Array();
    if ((checkStartDate != null) && (checkEndDate != null)) {
        arys1 = checkStartDate.split('-');
        var sdate = new Date(arys1[0],parseInt(arys1[1]-1),arys1[2]);
        arys2 = checkEndDate.split('-');
        var edate = new Date(arys2[0],parseInt(arys2[1]-1),arys2[2]);
        if(sdate > edate) return false;
    } else return false;
    return true;
}
</script>