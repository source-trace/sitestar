<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<style type="text/css">
.entry {width:85%;}
</style>
<script type="text/javascript" src="../script/jquery.cluetip.min.js"></script>
<script type="text/javascript" src="../script/getcolor.js"></script>
<script type="text/javascript" language="javascript">
<!--
var wt = parseInt("<?php echo WATERMARK_TYPE;?>");// for png or text
var tag = parseInt("<?php echo WATERMARK_STATUS;?>");
var tt = "<?php _e('Watermark Preview');?>";
$(document).ready(function(){
	$('#answer1').cluetip({splitTitle: '|',width: '300px',height:'50px'});
	$('#answer2').cluetip({splitTitle: '|',width: '500px',height:'50px'});
	$('#answer3').cluetip({splitTitle: '|',width: '400px',height:'30px'});
	$('#answer4').cluetip({splitTitle: '|',width: '500px',height:'55px'});
	$('#answer5').cluetip({splitTitle: '|',width: '300px',height:'30px'});
	$('#answer6').cluetip({splitTitle: '|',width: '500px',height:'65px'});
	$('#answer7').cluetip({splitTitle: '|',width: '500px',height:'50px'});
	$('#answer8').cluetip({splitTitle: '|',width: '200px',height:'30px'});
	$('#answer9').cluetip({splitTitle: '|',width: '350px',height:'30px'});
	$('#answer10').cluetip({splitTitle: '|',width: '350px',height:'30px'});
	
	var thumb_status = "<?php echo THUMB_STATUS;?>";
	var watermark_type = "<?php echo WATERMARK_TYPE;?>";
	
	$('input[name="sparam[WATERMARK_STATUS]"]').click(function(){
		tag = parseInt( $(this).val() );
	});
	
	if( parseInt(thumb_status) == 0 ) {
		$('tbody tr').eq(1).hide();
	} else {
		$('tbody tr').eq(1).show();
	}
	
	if( parseInt(watermark_type) == 2 ) {
		$('tbody tr:last').show();
		$('#water_mark_png').hide();
	} else {
		$('tbody tr:last').hide();
		$('#water_mark_png').show();
	}
	
	$('input[name="sparam[THUMB_STATUS]"]').click(function(){
		if( $(this).val() == 0 ) {
			$('tbody tr').eq(1).hide();
		} else {
			$('tbody tr').eq(1).show();
		}
	});
	
	var objDialog1 = $(parent.document.getElementById("showContents")).parent();//对话框对象
	var ifm1= parent.document.getElementById("showContents");//iframe嵌入层对象
	$('input[name="sparam[WATERMARK_TYPE]"]').click(function(){
		if( $(this).val() == 2 ) {
			$('tbody tr:last').show();
			$('#water_mark_png').hide();
			$(ifm1).attr('height', 890);
			objDialog1.css('height', 900);
		} else {
			$('tbody tr:last').hide();
			$('#water_mark_png').show();
			$(ifm1).attr('height', 445);
			objDialog1.css('height', 455);
		}
		wt = parseInt($(this).val());
	});
	
	// for color
	updatecolorpreview('c1');
	updatecolorpreview('c2');
});


function popupwin()
{
	var msg = tmp = '';
	if( wt == '1' ) {
		//tmp = $('#markpng').attr('src');		
		tmp =document.getElementById("hiddendefault").innerHTML;
		msg = (trim(tmp).length > 0) ? tmp : 'images/watermark.png';
		if(typeof(msg)=="undefined"){msg = 'images/watermark.png';}
	} else {
		tmp = $('#sparam_WATERMARK_TEXT_').val();
		var fsize = $('#sparam_WATERMARK_TEXT_SIZE_').val();
		var angle = $('#sparam_WATERMARK_TEXT_ANGLE_').val();
		var fcolor = $('#c1_v').val();
		var showx = $('#sparam_WATERMARK_TEXT_SHADOWX_').val();
		var showy = $('#sparam_WATERMARK_TEXT_SHADOWY_').val();
		var scolor = $('#c2_v').val();
		tmp = (trim(tmp).length > 0) ? tmp : 'SiteStar建站之星';
		fsize = (trim(fsize).length > 0) ? fsize : 24;
		angle = (trim(angle).length > 0) ? angle : 0;
		fcolor = (trim(fcolor).length > 0) ? fcolor : '#000000';
		showx = (trim(showx).length > 0) ? showx : 1;
		showy = (trim(showy).length > 0) ? showy : 1;
		scolor = (trim(scolor).length > 0) ? scolor : '#000000';
		msg = tmp+','+fsize+','+angle+','+fcolor.substr(1)+','+showx+','+showy+','+scolor.substr(1);
	}	

	popup_win=show_markpicker( tag, tt, wt, encodeURI(msg) );
	return false;	
}
	
	
//  表单验证
function check_imgform( obj )
{
	var reg = /^\d+(\.\d+)?$/
		
	// thumb
	var status = 0;
	var len = obj.elements["sparam[THUMB_STATUS]"].length;
	for( var i=0; i<len; i++ ) {
		if( obj.elements["sparam[THUMB_STATUS]"][i].checked ) {
			status = obj.elements["sparam[THUMB_STATUS]"][i].value;
			break;
		}
	}
	
	if( status != 0 )
	{
		var thquality = obj.elements["sparam[THUMB_QUALITY]"];
		var thwidth = obj.elements["sparam[THUMB_WIDTH]"];
		var theight = obj.elements["sparam[THUMB_HEIGHT]"];
		
		// thumb quality
		if( trim(thquality.value).length == 0 ) {
			alert("<?php _e('Please input thumb quality value');?>");
			thquality.focus();
			return false;
		} else if( !reg.test(thquality.value) || (parseInt(thquality.value) > 100) ) {
			alert("<?php _e('The thumb quality value is invalid');?>");
			thquality.select();
			return false;
		}
		
		// thumb size
		if( (trim(thwidth.value).length == 0) || (trim(theight.value).length == 0) ) {
			alert("<?php _e('Please input thumb size value');?>");
			thwidth.focus();
			return false;
		} else if( !reg.test(thwidth.value) || !reg.test(theight.value) ) {
			alert("<?php _e('The thumb size value is invalid');?>");
			thwidth.select();
			return false;
		}
	}
	
	// Watermark
	// thumb
	var mark = 0;
	var ln = obj.elements["sparam[WATERMARK_STATUS]"].length;
	for( var j=0; j<ln; j++ ) {
		if( obj.elements["sparam[WATERMARK_STATUS]"][j].checked ) {
			mark = obj.elements["sparam[WATERMARK_STATUS]"][j].value;
			break;
		}
	}
	
	if( mark != 0 )
	{
		// watermark condition
		var wmin_width = obj.elements["sparam[WATERMARK_MIN_WIDTH]"];
		var wmin_height = obj.elements["sparam[WATERMARK_MIN_HEIGHT]"];
		
		if( (trim(wmin_width.value).length > 0) && !reg.test(wmin_width.value) ) {
			alert("<?php _e('The watermark min-width value is invalid');?>");
			wmin_width.select();
			return false;
		}
		
		if( (trim(wmin_height.value).length > 0) && !reg.test(wmin_height.value) ) {
			alert("<?php _e('The watermark min-height value is invalid');?>");
			wmin_height.select();
			return false;
		}
		
		// watermark confluence
		var wtrans = obj.elements["sparam[WATERMARK_TRANS]"];
		if( trim(wtrans.value).length == 0 ) {
			alert("<?php _e('Please input watermark confluence value');?>");
			wtrans.focus();
			return false;
		} else if( !reg.test(wtrans.value) ) {
			alert("<?php _e('The watermark confluence value is invalid');?>");
			wtrans.select();
			return false;
		}
		
		// watermark quality
		var wquality = obj.elements["sparam[WATERMARK_QUALITY]"];
		if( (trim(wquality.value).length > 0) && !reg.test(wquality.value) ) {
			alert("<?php _e('The watermark quality value is invalid');?>");
			wquality.select();
			return false;
		}
		
		// watermark type
		var type = 0;
		var l = obj.elements["sparam[WATERMARK_TYPE]"].length;
		for( var k=0; k<l; k++ ) {
			if( obj.elements["sparam[WATERMARK_TYPE]"][k].checked ) {
				type = obj.elements["sparam[WATERMARK_TYPE]"][k].value;
				break;
			}
		}
		
		if( type == 2 ) {
			// watermark text
			var wtxt = obj.elements["sparam[WATERMARK_TEXT]"];
			if( trim(wtxt.value).length == 0 ) {
				alert("<?php _e('Please input watermark text value');?>");
				wtxt.focus();
				return false;
			}
			
			// watermark font size
			var fsize = obj.elements["sparam[WATERMARK_TEXT_SIZE]"];
			if( trim(fsize.value).length == 0 ) {
				alert("<?php _e('Please input watermark text font-size value');?>");
				fsize.focus();
				return false;
			} else if( !reg.test(fsize.value) ) {
				alert("<?php _e('The watermark text font-size value is invalid');?>");
				fsize.select();
				return false;
			}
			
			// watermark text shadow-x
			var shadowx = obj.elements["sparam[WATERMARK_TEXT_SHADOWX]"];
			if( trim(shadowx.value).length == 0 ) {
				alert("<?php _e('Please input watermark text shadow-x value');?>");
				shadowx.focus();
				return false;
			} else if( !reg.test(shadowx.value) ) {
				alert("<?php _e('The watermark text shadow-x value is invalid');?>");
				shadowx.select();
				return false;
			}
			
			// watermark text shadow-y
			var shadowy = obj.elements["sparam[WATERMARK_TEXT_SHADOWY]"];
			if( trim(shadowy.value).length == 0 ) {
				alert("<?php _e('Please input watermark text shadow-y value');?>");
				shadowy.focus();
				return false;
			} else if( !reg.test(shadowy.value) ) {
				alert("<?php _e('The watermark text shadow-y value is invalid');?>");
				shadowy.select();
				return false;
			}
		} else {
			// watermark image
			var wpng = obj.elements["WATERMARK_PNG"].value;
			if( trim(wpng).length > 0 ) {
				var pos = wpng.lastIndexOf(".");
	 			var ext = wpng.substring( pos, wpng.length );
	 			if( ext.toLowerCase() != '.png' ) {
	 				alert("<?php _e('Please upload a PNG image');?>");
	 				return false;
	 			}
			}
		}	
	}
}

// 还原水印图片
function restore_watermark_image()
{
	var act = document.forms[0].mode;
	act.value = 'restore';
	document.forms[0].submit();
}
//-->
</script>
<?php echo Notice::get('mod_attachment/msg');?>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$img_form = new Form('index.php', 'imgform', 'check_imgform');
$img_form->setEncType('multipart/form-data');
$img_form->p_open('mod_attachment', 'admin_list');
?>
<div style="overflow:auto;width:100%;">
<table class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('hidden', 'mode', 'submit');
			///echo '&nbsp;&nbsp;<span style="float:right;">('.__('No need to save the settings you can preview').')</span>';
            echo Html::input('button', 'browser', __('Watermark preview of effects'), 'onclick="popupwin()"');
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('submit', 'send', __('Save'));
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Thumb'); ?></td>
            <td class="entry">
            <?php
            $check0 = $check1 = $check2 = $check3 = '';
            switch( THUMB_STATUS ) {
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
            echo Html::input('radio', 'sparam[THUMB_STATUS]', 0, $check0);
            echo '&nbsp;&nbsp;'.__('Thumbnail feature is not enabled').'<br />';
//            echo Html::input('radio', 'sparam[THUMB_STATUS]', 1, $check1);
//            echo '&nbsp;&nbsp;'.__('Generate no more than the specified size of thumbnails').'<br />';
            echo Html::input('radio', 'sparam[THUMB_STATUS]', 3, $check3);
            echo '&nbsp;&nbsp;'.__('Generate fixed-size thumbnails').'<br />';
            echo Html::input('radio', 'sparam[THUMB_STATUS]', 2, $check2);
//            echo '&nbsp;&nbsp;'.__('Convert image attachments to no more than the specified size image');
			echo '&nbsp;&nbsp;'.__('Generate no more than the specified size of thumbnails');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer1" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set image note');?>"/>
            <?php }?>
            </td>
        </tr>
        <tr>
            <td class="label">&nbsp;&nbsp;</td>
            <td class="entry">
            <?php
            _e('Thumb Quality');echo ':<br />';
            echo Html::input('text', 'sparam[THUMB_QUALITY]', THUMB_QUALITY, 'class="textinput" style="width:50px;" maxlength="3"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer2" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set image size note');?>" />
            <?php
            }
            echo '<br />'.__('Thumb Size').':<br />';
            echo Html::input('text', 'sparam[THUMB_WIDTH]', THUMB_WIDTH, 'class="textinput" style="width:50px;" maxlength="4"');	
            echo '&nbsp;&nbsp;X&nbsp;&nbsp;';
            echo Html::input('text', 'sparam[THUMB_HEIGHT]', THUMB_HEIGHT, 'class="textinput" style="width:50px;" maxlength="4"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer3" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set image note 1');?>" />
            <?php }?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Watermark'); ?></td>
            <td class="entry">
            <?php
            $status0 = $status1 = $status2 = $status3 = $status4 = $status5 = $status6 = $status7 = $status8 = $status9 = '';
            switch( WATERMARK_STATUS ) {
            	case 0:
            		$status0 = 'checked';
            		break;
            	case 1:
            		$status1 = 'checked';
            		break;
            	case 2:
            		$status2 = 'checked';
            		break;
            	case 3:
            		$status3 = 'checked';
            		break;
            	case 4:
            		$status4 = 'checked';
            		break;
            	case 5:
            		$status5 = 'checked';
            		break;
            	case 6:
            		$status6 = 'checked';
            		break;
            	case 7:
            		$status7 = 'checked';
            		break;
            	case 8:
            		$status8 = 'checked';
            		break;
            	case 9:
            		$status9 = 'checked';
            		break;
            }
            	
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 0, $status0);
            echo '&nbsp;&nbsp;'.__('Watermark feature is not enabled').'<br />';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 1, $status1);
            echo '&nbsp;&nbsp;'.__('top-left').'&nbsp;&nbsp;';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 2, $status2);
            echo '&nbsp;&nbsp;'.__('top-center').'&nbsp;&nbsp;';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 3, $status3);
            echo '&nbsp;&nbsp;'.__('top-right').'<br />';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 4, $status4);
            echo '&nbsp;&nbsp;'.__('middle-left').'&nbsp;&nbsp;';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 5, $status5);
            echo '&nbsp;&nbsp;'.__('middle').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 6, $status6);
            echo '&nbsp;&nbsp;'.__('middle-right').'<br />';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 7, $status7);
            echo '&nbsp;&nbsp;'.__('buttom-left').'&nbsp;&nbsp;';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 8, $status8);
            echo '&nbsp;&nbsp;'.__('buttom-center').'&nbsp;&nbsp;';
            echo Html::input('radio', 'sparam[WATERMARK_STATUS]', 9, $status9);
            echo '&nbsp;&nbsp;'.__('buttom-right').'&nbsp;&nbsp;&nbsp;&nbsp;';
            ?>
            <?php
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer4" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set image note 2');?>"/>
            <?php }?>
            </td>
        </tr>
        
        <tr>
            <td class="label"><?php _e('Watermark Condition'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[WATERMARK_MIN_WIDTH]', WATERMARK_MIN_WIDTH, 'class="textinput" style="width:50px;" maxlength="5"');
            echo '&nbsp;&nbsp;X&nbsp;&nbsp;';
            echo Html::input('text', 'sparam[WATERMARK_MIN_HEIGHT]', WATERMARK_MIN_HEIGHT, 'class="textinput" style="width:50px;" maxlength="5"');
            
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer5" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="<?php _e('Set image note 3')?>"/>
            <?php }?>
            </td>
        </tr>
        <tr>
        	<td class="label"><?php _e('Watermark Type'); ?></td>
        	<td class="entry">
            <?php
            $type1 = $type2 = '';
            switch( WATERMARK_TYPE ) {
            	case 1:
            		$type1 = 'checked';
            		break;
            	case 2:
            		$type2 = 'checked';
            		break;
            }
            echo Html::input('radio', 'sparam[WATERMARK_TYPE]', 1, $type1);
            echo '&nbsp;&nbsp;'.__('PNG watermark type').'<br />';
            echo Html::input('radio', 'sparam[WATERMARK_TYPE]', 2, $type2);
            echo '&nbsp;&nbsp;'.__('Text watermark type');
            ?>
            </td>
        </tr>
        <tr id="water_mark_png">
        	<td class="label">&nbsp;&nbsp;</td>
        	<td class="entry"><div id="hiddendefault" style="display:none"><?php if(((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)) && (WATERMARK_PNG == 'images/watermark.png'||WATERMARK_PNG == 'images/watermark2.png')){echo "template/images/agent_logo.png";}else{echo WATERMARK_PNG;}?></div><img id="markpng" src="<?php if(((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)) && (WATERMARK_PNG == 'images/watermark.png'||WATERMARK_PNG == 'images/watermark2.png')){echo "template/images/agent_logo.png";}else{echo WATERMARK_PNG;}?>" border="0" />
            <?php
            if( $default_watermark_image != WATERMARK_PNG ) {
            	echo '<a href="#" onclick="restore_watermark_image()">'.__('Restore to default picture').'</a>';
            }
            echo '<br />'.Html::input('file', 'WATERMARK_PNG');
            ?>
            </td>
        </tr>
		<!--tr>
            <td class="label"><?php _e('Watermark Fusion'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[WATERMARK_TRANS]', WATERMARK_TRANS, 'size="10" maxlength="3"');
            
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer6" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;设置 GIF 类型水印图片与原始图片的融合度，范围为 1～100 的整数，数值越大水印图片透明度越低。PNG 类型水印本身具有真彩透明效果，无须此设置。本功能需要开启水印功能后才有效"/>
            <?php }?>
            </td>
        </tr-->
        <tr>
            <td class="label"><?php _e('Watermark Quality'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'sparam[WATERMARK_QUALITY]', WATERMARK_QUALITY, 'class="textinput" style="width:50px;" maxlength="3"');
            
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            ?>
            <img id="answer7" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e('Set image note 5');?>"/>
            <?php }?>
            </td>
        </tr>
        <tr>
            <td class="label">&nbsp;&nbsp;</td>
            <td class="entry">
            <?php
           
            echo __('Text watermark text').':<br />';
            // if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){
             	//echo Html::textarea('sparam[WATERMARK_TEXT]', '', 'rows="6" cols="50"class="textinput"');
            //}else{
            	echo Html::textarea('sparam[WATERMARK_TEXT]', WATERMARK_TEXT, 'rows="6" cols="50"class="textinput"');
            //}
            
            echo '<br />'.__('Text watermark font size').':<br />';
            echo Html::input('text', 'sparam[WATERMARK_TEXT_SIZE]', WATERMARK_TEXT_SIZE, 'class="textinput" style="width:50px;"');
            echo '<br />'.__('Text watermark angle').':<br />';
            echo Html::input('text', 'sparam[WATERMARK_TEXT_ANGLE]', WATERMARK_TEXT_ANGLE, 'class="textinput" style="width:50px;"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            	echo '&nbsp;&nbsp;<img id="answer8" class="title" src="'.P_TPL_WEB.'/images/answer1.gif" alt="help" title="'.__("Set image note 6").'"/>';
            }
            echo '<br />'.__('Text watermark font color').':<br />';
            ?>
            <input type="text" name="sparam[WATERMARK_TEXT_COLOR]" class="textinput" style="width:70px;" value="<?php echo WATERMARK_TEXT_COLOR;?>" onchange="updatecolorpreview('c1')" id="c1_v">
            <input type="button" style="background: none repeat scroll 0% 0% rgb(0, 0, 0);height:21px;width:40px;" value="" onclick="c1_frame.location='images/getcolor.htm?c1';showMenu({'ctrlid':'c1'})" id="c1">
            <span id="c1_menu" style="display: none"><iframe name="c1_frame" src="" frameborder="0" width="166" height="186" scrolling="no"></iframe></span>
			<?php
            echo '<br />'.__('Text watermark shadow-x').':<br />';
            echo Html::input('text', 'sparam[WATERMARK_TEXT_SHADOWX]', WATERMARK_TEXT_SHADOWX, 'class="textinput" style="width:50px;"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            	echo '&nbsp;&nbsp;<img id="answer9" class="title" src="'.P_TPL_WEB.'/images/answer1.gif" alt="help" title="'.__("Set image note 7").'"/>';
            }
            echo '<br />'.__('Text watermark shadow-y').':<br />';
            echo Html::input('text', 'sparam[WATERMARK_TEXT_SHADOWY]', WATERMARK_TEXT_SHADOWY, 'class="textinput" style="width:50px;"');
            if(SessionHolder::get('_LOCALE', DEFAULT_LOCALE) != 'en') 
            {
            	echo '&nbsp;&nbsp;<img id="answer10" class="title" src="'.P_TPL_WEB.'/images/answer1.gif" alt="help" title="'.__("Set image note 8").'"/>';
            }
            echo '<br />'.__('Text watermark shadow-color').':<br />';
            ?>
            <input type="text" name="sparam[WATERMARK_TEXT_SHADOW_COLOR]" class="textinput" style="width:70px;" value="<?php echo WATERMARK_TEXT_SHADOW_COLOR;?>" onchange="updatecolorpreview('c2')" id="c2_v">
            <input type="button" style="background: none repeat scroll 0% 0% rgb(0, 0, 0);height:21px;width:40px;" value="" onclick="c2_frame.location='images/getcolor.htm?c2';showMenu({'ctrlid':'c2'})" id="c2">
            <span id="c2_menu" style="display: none"><iframe name="c2_frame" src="" frameborder="0" width="166" height="186" scrolling="no"></iframe></span>
             </td>
        </tr>
    </tbody>
</table>
</div>
<?php
$img_form->close();
//$img_form->writeValidateJs();

//include_once(P_TPL.'/view/mod_param/admin_list.php');// load mod_param' page
?>
