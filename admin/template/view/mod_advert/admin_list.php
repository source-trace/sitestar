<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<style type="text/css">
.label {width:27%;}
</style>
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" src="../script/getcolor.js"></script>
<script type="text/javascript" language="javascript">
<!--
var tag = width = height = 0;
var title = "<?php _e('Ad Theme');?>";
$(document).ready(function(){
	var img = '';
	var type = tag = "<?php echo ADVERT_STATUS;?>";
	$('tbody tr:gt(5)').hide();
	if( parseInt(type) == 0 ) {
		$('tbody tr:first').nextAll().hide();
	} else {
		if( type == '1' ) {
			width = 300;
			height = 200;
		} else if( type == '2' ) {
			width = 100;
			height = 100;
		} else if( type == '3' ) {
			width = 100;
			height = 300;
			$('tbody tr:gt(5)').show();
			$('.show_left').show();
		}
	}
	
	$('input[name="sparam[ADVERT_STATUS]"]').click(function(){
		var objDialog1 = $(parent.document.getElementById("showContents")).parent();//对话框对象
		var ifm1= parent.document.getElementById("showContents");//iframe嵌入层对象
		if( $(this).val() == 0 ) {
			$('tbody tr:first').nextAll().hide();
			height = 200;
			$(ifm1).attr('height',height-30);
			objDialog1.css('height',height);
		} else {
			var ifmheight = 580;
			var diaheight = 585;
			tag = $(this).val();
			$('tbody tr:lt(6)').show();
			$('.show_left').hide();
			if( tag == '1' ) {//弹出广告
				img = 't1.jpg';
				width = 300;
				height = 200;
				$(ifm1).attr('height',ifmheight+30);
				objDialog1.css('height',diaheight+30);
				$('tbody tr:gt(5)').hide();
			} else if( tag == '2' ) {//浮动广告
				img = 'f1.jpg';
				width = 100;
				height = 100;
				ifmheight = 450;
				diaheight = 455;
				$(ifm1).attr('height',ifmheight+30);
				objDialog1.css('height',diaheight+30);
				$('tbody tr:gt(5)').hide();
			} else if( tag == '3' ) {//对联广告
				img = 'd1.jpg';
				width = 100;
				height = 300;
				ifmheight = 500;
				diaheight = 520;
				$(ifm1).attr('height',ifmheight+30);
				objDialog1.css('height',diaheight+30);
				$('tbody tr:gt(5)').show();
				$('.show_left').show();
			}
			
//			var ifm1= parent.document.getElementById("showContents");
//			var subWeb = parent.document.frames ? parent.document.frames["showContents"].document : ifm1.contentDocument;
//			if(ifm1 != null && subWeb != null) {
//				$(ifm1).attr('height',ifmheight);
//				var objDialog1 = $(parent.document.getElementById("showContents")).parent();
				objDialog1.parent().css('top',30+'px');
//				objDialog1.css('height',diaheight);
//			}
			
			// show img
			$('#ad_src img').attr('src', '../data/adtool/theme/'+img);
			$('input[name="sparam[ADVERT_THEME]"]').val('../data/adtool/theme/'+img);
			if( tag == '3' ) {
				$('#rad_src img').attr('src', '../data/adtool/theme/'+img);
				$('input[name="sparam[ADVERT_RTHEME]"]').val('../data/adtool/theme/'+img);
			}
		}
	});
	
	// get color
	updatecolorpreview('c1');
	if (tag == '3') updatecolorpreview('c2');
	
	// help
	$('#answer').cluetip({splitTitle: '|',width: '400px',height:'50px'});
	if (tag == '3') $('#answer2').cluetip({splitTitle: '|',width: '400px',height:'50px'});
});

function popupwin( pos )
{
	var objDialog1 = $(parent.document.getElementById("showContents")).parent().parent();//对话框对象
	var ifm1= parent.document.getElementById("showContents");//iframe嵌入层对象
	$(ifm1).attr('width',630);
	objDialog1.css('width',650);
	
	popup_win=show_adpicker( tag, title, pos );
	return false;	
}

function upload_theme( index )
{	var nt = "<?php _e('Recommended Size');?>：";
	var wh = width+"px(<?php _e('Width');?>)×"+height+"px(<?php _e('Height');?>)";
	alert(nt+wh);
	$('#uptheme'+index).css('display', 'block');
}

//  表单验证
function check_adform( obj )
{
	var reg = /^\d+(\.\d+)?$/;
		
	// ad type
	var type = 0;
	var l = obj.elements["sparam[ADVERT_STATUS]"].length;
	for( var k=0; k<l; k++ ) {
		if( obj.elements["sparam[ADVERT_STATUS]"][k].checked ) {
			type = obj.elements["sparam[ADVERT_STATUS]"][k].value;
			break;
		}
	}
	
	// form check
	if( parseInt(type) > 0 ) 
	{
		var tpl = obj.elements["sparam[ADVERT_THEME]"];
		if( trim(tpl.value).length == 0 ) {
			alert("<?php _e('Please choose ad theme');?>");
			return false;
		}
	
		var txt = obj.elements["sparam[ADVERT_TEXT]"];
		if( trim(txt.value).length == 0 ) {
			alert("<?php _e('Please input ad text value');?>");
			txt.focus();
			return false;
		}
		
		var fsize = obj.elements["sparam[ADVERT_TEXT_SIZE]"];
		if( trim(fsize.value).length == 0 ) {
			alert("<?php _e('Please input ad text font-size value');?>");
			fsize.focus();
			return false;
		} else if( !reg.test(fsize.value) ) {
			alert("<?php _e('The ad text font-size value is invalid');?>");
			fsize.select();
			return false;
		}
		
		var url = obj.elements["sparam[ADVERT_URL]"];
		if( trim(url.value).length > 0 ) {
			if( isUri(url.value) == false ) {
				alert("<?php _e('The ad url value is invalid');?>");
				url.select();
				return false;
			}
		}
		
		if( parseInt(type) == 3 ) {
			var rtpl = obj.elements["sparam[ADVERT_RTHEME]"];
			if( trim(rtpl.value).length == 0 ) {
				alert("<?php _e('Please choose ad theme');?>");
				return false;
			}
		
			var rtxt = obj.elements["sparam[ADVERT_RTEXT]"];
			if( trim(rtxt.value).length == 0 ) {
				alert("<?php _e('Please input ad text value');?>");
				rtxt.focus();
				return false;
			}
			
			var rfsize = obj.elements["sparam[ADVERT_RTEXT_SIZE]"];
			if( trim(rfsize.value).length == 0 ) {
				alert("<?php _e('Please input ad text font-size value');?>");
				rfsize.focus();
				return false;
			} else if( !reg.test(rfsize.value) ) {
				alert("<?php _e('The ad text font-size value is invalid');?>");
				rfsize.select();
				return false;
			}
			
			var rurl = obj.elements["sparam[ADVERT_RURL]"];
			if( trim(rurl.value).length > 0 ) {
				if( isUri(rurl.value) == false ) {
					alert("<?php _e('The ad url value is invalid');?>");
					rurl.select();
					return false;
				}
			}
		}
	}
}

function isUri(str_url) {
	return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(str_url);
}   
//-->
</script>
<?php echo Notice::get('mod_advert/msg');?>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$ad_form = new Form('index.php', 'adform', 'check_adform');
$ad_form->setEncType('multipart/form-data');
$ad_form->p_open('mod_advert', 'admin_list');
?>
<div style="overflow:auto;width:100%;">
<table class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo Html::input('submit', 'send', __('Save'));
            echo Html::input('hidden', 'mode', 'submit');
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Advert Type'); ?></td>
            <td class="entry">
            <?php
            $check0 = $check1 = $check2 = $check3 = '';
			if(ADVERT_STATUS=='ADVERT_STATUS'){
				define("ADVERT_STATUS",0);
			}
            switch( ADVERT_STATUS ) {
            	case 0:
            		$check0 = 'checked';
            	    break;
            	case 1:
            		$check1 = 'checked';
            	    break;
            	case 2:
            		$check2 = 'checked';
            	    break;
            	case 3:
            		$check3 = 'checked';
            	    break;
            }
            echo Html::input('radio', 'sparam[ADVERT_STATUS]', 0, $check0);
            echo '&nbsp;&nbsp;'.__('Advert feature is not enabled').'<br />';
            echo Html::input('radio', 'sparam[ADVERT_STATUS]', 1, $check1);
            echo '&nbsp;&nbsp;'.__('Pop-up ads').'<br />';
            echo Html::input('radio', 'sparam[ADVERT_STATUS]', 2, $check2);
            echo '&nbsp;&nbsp;'.__('Floating Ads').'<br />';
            echo Html::input('radio', 'sparam[ADVERT_STATUS]', 3, $check3);
            echo '&nbsp;&nbsp;'.__('Couplet Ads');
            
            // ad theme
            $show_ad = '<img src="'.ADVERT_THEME.'" border="0" />';
            $show_rad = '<img src="'.ADVERT_RTHEME.'" border="0" />';
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Advert Themes');?>
			<span class="show_left"><?php echo '('.__('left').')'; ?></span></td>
            <td class="entry"><span id="ad_src"><?php echo $show_ad;?></span>
            <?php
            echo Html::input('hidden', 'sparam[ADVERT_THEME]', ADVERT_THEME!='ADVERT_THEME'?ADVERT_THEME:'');
            echo '<a href="#" onclick="popupwin(1)">'.__('Click here to select the ad theme').'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
            // self-defined theme
            echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="###" onclick="upload_theme(1)">'.__('Self-defined theme').'</a>';
            echo '<div id="uptheme1" style="display:none;">'.Html::input('file', 'uptheme').'</div>';
            ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Advert Text');?>
			<span class="show_left"><?php echo '('.__('left').')'; ?></span></td>
            <td class="entry">
            <?php
            echo Html::textarea('sparam[ADVERT_TEXT]', ADVERT_TEXT!='ADVERT_TEXT'?ADVERT_TEXT:'', 'rows="3" cols="50" class="textinput"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') {
            ?>            
            <img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __("AD note");?>"/>
            <?php }?></td>
        </tr>
        <tr>
        	<td class="label"><?php _e('Advert Text Font Size');?>
			<span class="show_left"><?php echo '('.__('left').')'; ?></span></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[ADVERT_TEXT_SIZE]', ADVERT_TEXT_SIZE!='ADVERT_TEXT_SIZE'?ADVERT_TEXT_SIZE:'', 'class="textinput" style="width:50px"');
            ?></td>
        </tr>
       	<tr>
        	<td class="label"><?php _e('Advert Text Color');?>
			<span class="show_left"><?php echo '('.__('left').')'; ?></span></td>
            <td class="entry">
            <input type="text" name="sparam[ADVERT_TEXT_COLOR]" class="textinput" style="width:80px" value="<?php echo ADVERT_TEXT_COLOR!='ADVERT_TEXT_COLOR'?ADVERT_TEXT_COLOR:'';?>" onchange="updatecolorpreview('c1')" id="c1_v">
            <input type="button" style="background: none repeat scroll 0% 0% rgb(0, 0, 0);height:21px;width:40px;" value="" onclick="c1_frame.location='images/getcolor.htm?c1';showMenu({'ctrlid':'c1'})" id="c1">
            <span id="c1_menu" style="display: none"><iframe name="c1_frame" src="" frameborder="0" width="166" height="186" scrolling="no"></iframe></span>
			</td>
        </tr>
        <tr>
        	<td class="label"><?php _e('Advert Url');?>
			<span class="show_left"><?php echo '('.__('left').')'; ?></span></td>
            <td class="entry">
            <?php
            $check = '';
            echo Html::input('text', 'sparam[ADVERT_URL]', ADVERT_URL!='ADVERT_URL'?ADVERT_URL:'', 'class="textinput"');
            echo '<br />';
            if (ADVERT_LTARGET == '_self') $check = 'checked="true"';
            echo Html::input('checkbox', 'sparam[ADVERT_LTARGET]', '_self', $check);
            echo '&nbsp;&nbsp;'.__('Open in the current window');
            ?></td>
        </tr>
        <!-- 对联式（右侧）-->
        <tr>
            <td class="label"><?php _e('Advert Themes');echo '('.__('right').')'; ?></td>
            <td class="entry"><span id="rad_src"><?php echo $show_rad;?></span>
            <?php
            echo Html::input('hidden', 'sparam[ADVERT_RTHEME]', ADVERT_RTHEME!='ADVERT_RTHEME'?ADVERT_RTHEME:'');
            echo '<a href="#" onclick="popupwin(2)">'.__('Click here to select the ad theme').'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
            // self-defined theme
            echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="###" onclick="upload_theme(2)">'.__('Self-defined theme').'</a>';
            echo '<div id="uptheme2" style="display:none;">'.Html::input('file', 'uprtheme').'</div>';
            ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Advert Text');echo '('.__('right').')'; ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('sparam[ADVERT_RTEXT]', ADVERT_RTEXT!='ADVERT_RTEXT'?ADVERT_RTEXT:'', 'rows="3" cols="50" class="textinput"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') {
            ?>            
            <img id="answer2" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __("AD note");?>"/>
            <?php }?></td>
        </tr>
        <tr>
        	<td class="label"><?php _e('Advert Text Font Size');echo '('.__('right').')'; ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[ADVERT_RTEXT_SIZE]', ADVERT_RTEXT_SIZE!='ADVERT_RTEXT_SIZE'?ADVERT_RTEXT_SIZE:'', 'class="textinput" style="width:50px"');
            ?></td>
        </tr>
       	<tr>
        	<td class="label"><?php _e('Advert Text Color');echo '('.__('right').')'; ?></td>
            <td class="entry">
            <input type="text" name="sparam[ADVERT_RTEXT_COLOR]" class="textinput" style="width:80px" value="<?php echo ADVERT_RTEXT_COLOR!='ADVERT_RTEXT_COLOR'?ADVERT_RTEXT_COLOR:'';?>" onchange="updatecolorpreview('c2')" id="c2_v">
            <input type="button" style="background: none repeat scroll 0% 0% rgb(0, 0, 0);height:21px;width:40px;" value="" onclick="c2_frame.location='images/getcolor.htm?c2';showMenu({'ctrlid':'c2'})" id="c2">
            <span id="c2_menu" style="display: none"><iframe name="c2_frame" src="" frameborder="0" width="166" height="186" scrolling="no"></iframe></span>
			</td>
        </tr>
        <tr>
        	<td class="label"><?php _e('Advert Url');echo '('.__('right').')'; ?></td>
            <td class="entry">
            <?php
            $check = '';
            echo Html::input('text', 'sparam[ADVERT_RURL]', ADVERT_RURL!='ADVERT_RURL'?ADVERT_RURL:'', 'class="textinput" size="25"');
            echo '<br />';
            if (ADVERT_RTARGET == '_self') $check = 'checked="true"';
            echo Html::input('checkbox', 'sparam[ADVERT_RTARGET]', '_self', $check);
            echo '&nbsp;&nbsp;'.__('Open in the current window');
            ?></td>
        </tr>
        <!-- 对联式（右侧）-->
    </tbody>
</table>
</div>
<?php
$ad_form->close();
?>