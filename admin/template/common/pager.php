<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
#pagerwrapper {padding:0 15px;width:98%;}
#pagerwrapper table td {padding-right:10px;}
.pageinput {width:18px;_width:18px}
.page_square{ width:15px;height:13px;_width:15px;_height:13px;background-color:#FAFDFC; border:1px #F6F4F2 solid;padding:0 3px;_padding:0 3px;margin:0 3px;_margin:0 3px;}
.page_square_bg{ width:15px;height:13px;_width:15px;_height:13px;background-color:#0468B4; border:1px #F6F4F2 solid;padding:0 3px;_padding:0 3px;}
.page_word{ width:50px;height:13px;_width:50px;_height:13px;background-color:#FAFDFC; border:1px #F6F4F2 solid;padding:0 3px;_padding:0 3px;margin:0 3px;_margin:0 3px;}
.page_sure{width:50px;_width:50px; border-left:#CCCCCC 1px solid; border-top: #CCCCCC 1px solid; border-right:#999999 1px solid; border-bottom:#999999 1px solid; background-color:#00CCFF;} 
</style>
<script language="javascript">
function pageLocation(){
	var p_v = $("#p").val();
	if(p_v==""){
		alert("<?php echo __("The Page number is empty,input it please!");?>");
		return false;
	}
	if(!(p_v.match(/\d/))){
		alert("<?php echo __("The Page number must be a digit,input it please!");?>");
		return false;
	}
	var locationPage = "<?php echo Html::uriquery($page_mod, $page_act, $page_extUrl); ?>";
	// 伪静态下“添加页面”模块分页用
	if ("<?php echo $type;?>" == 'popupwin') {
		window.location= "index.php?<?php echo Html::xuriquery($page_mod, $page_act, $page_extUrl); ?>"+"&p="+p_v;
	} else {
		<?php if(MOD_REWRITE!='3'){ ?>
		if(locationPage.match(/(.html)/) && ){
			var locaTo = locationPage.replace("admin_list","admin_list-p-"+p_v);
			window.location = locaTo;
		}else{
			window.location= "<?php echo Html::uriquery($page_mod, $page_act, $page_extUrl); ?>"+"&p="+p_v;	
		}
		<?php }else{ ?>
			window.location= "<?php echo Html::uriquery($page_mod, $page_act, $page_extUrl); ?>"+"&p="+p_v;	
		<?php } ?>
	}
}
</script>
<div id="pagerwrapper">
	<table id="pager" cellspacing="0">
		<tbody>
			<!--tr>
			    <td><a href="<?php echo $pager['first']; ?>" title="<?php _e('First'); ?>">
                    <img src="<?php echo P_TPL_WEB; ?>/images/pager-first.gif" border="0" /></a></td>
                <td><a href="<?php echo $pager['prev']; ?>" title="<?php _e('Previous'); ?>">
                    <img src="<?php echo P_TPL_WEB; ?>/images/pager-prev.gif" border="0" /></a></td>
                <td><a href="<?php echo $pager['next']; ?>" title="<?php _e('Next'); ?>">
                    <img src="<?php echo P_TPL_WEB; ?>/images/pager-next.gif" border="0" /></a></td>
                <td><a href="<?php echo $pager['last']; ?>" title="<?php _e('Last'); ?>">
                    <img src="<?php echo P_TPL_WEB; ?>/images/pager-last.gif" border="0" /></a></td>
                <td class="small"><?php echo $pager['curr'].' / '.$pager['total']; ?></td>
            </tr-->
            <tr><td colspan="5"><?php echo $pager ?></td>
           </tr>
        </tbody>
    </table>
</div>