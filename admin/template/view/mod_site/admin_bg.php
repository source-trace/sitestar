<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<link rel="stylesheet" href="../script/jquery.cluetip.css" type="text/css" />
<link rel="stylesheet" href="../script/colorpicker.css" type="text/css" />
<script type="text/javascript" src="../script/jquery.cluetip.min.js">  </script>
<script type="text/javascript" src="../script/colorpicker.js">  </script>
<script type="text/javascript" language="javascript">
<!--
function createXml(tag){
	$.post("../index.php?_m=mod_sitemap&_a=create_sitemap",{tag:tag},function(msg){
		if(tag=="b"){
		document.getElementById("stbaidu").innerHTML="<a target='_blank' href='../sitemap_baidu.xml'>"+"<?php _e('Baidu map'); ?>"+"</a>";;
		}else if(tag=="g"){
			document.getElementById("stgoogle").innerHTML="<a target='_blank' href='../sitemap.xml'>"+"<?php _e('Google map'); ?>"+"</a>";
		}
		alert(msg);
	});
}

//-->
</script>

<script language="javascript">
$(function(){
	dialog_global={};
	dialog_global.setFontColor=function (){
			$("#backcolor_div2").colorpicker({	
				zIndex:2000,
				success : function(o,color){
					if("transparent"==color){
						$("#backcolor_div2,#backcolor_divs").css("background-image","")
					}else{
						$("#backcolor_div2,#backcolor_divs").css("background-image","none")
					}
					$("#backcolor_div2,#backcolor_divs").css("background-color",color);
					$("#si_color").val(color);
				}
			});
		}
	dialog_global.selche=function(o){
		//background-attachment
		if(o.checked){
			$("#background-attachment").val("fixed");
		}else{
			$("#background-attachment").val("scroll");
		}
	}
	dialog_global.clearBgImage=function(){
		if(confirm('<?php _e('Are you sure to clear');?>')){
			$.ajax({
			   type: "POST",
			   url: "index.php",
			   data: "_m=mod_site&_a=del_bg_info&lang_sw=<?php echo $lang_sw;?>",
			   success: function(msg){
				 alert("<?php _e('Delete success!');?>");
				 parent.window.location.href='../index.php';
			   }
			});
		}
	}
	
$('.wp-bg-set-position-block span').click(function(){
		//_inposition();
		var changepos='left top';			
		if($(this).hasClass('wp-bg-set-top-left')){ $(this).addClass('wp-bg-tl-selected'); changepos='left top';}
		if($(this).hasClass('wp-bg-set-top-center')){$(this).addClass('wp-bg-tc-selected'); changepos='center top';}
		if($(this).hasClass('wp-bg-set-top-right')){$(this).addClass('wp-bg-tr-selected'); changepos='right top';}
		
		if($(this).hasClass('wp-bg-set-middle-left')){ $(this).addClass('wp-bg-ml-selected'); changepos='left center';}
		if($(this).hasClass('wp-bg-set-middle-center')){$(this).addClass('wp-bg-mc-selected'); changepos='center center';}
		if($(this).hasClass('wp-bg-set-middle-right')){$(this).addClass('wp-bg-mr-selected'); changepos='right center';}
		
		if($(this).hasClass('wp-bg-set-bottom-left')){ $(this).addClass('wp-bg-bl-selected'); changepos='left bottom';}
		if($(this).hasClass('wp-bg-set-bottom-center')){$(this).addClass('wp-bg-bc-selected'); changepos='center bottom';}
		if($(this).hasClass('wp-bg-set-bottom-right')){$(this).addClass('wp-bg-br-selected'); changepos='right bottom';}
		$("#si_position").val(changepos);
		
		
	
	});		
});
</script>
<style type="text/css">
.wp-bg-set-position-block{ width:75px;}
.wp-bg-set-position-block span{ display:inline-block; width:17px; height:17px; border:1px solid #d5d5d5; cursor:pointer; margin-right:2px;  margin-bottom:-2px; background:#f5f5f6; -moz-border-radius:2px; -webkit-border-radius:2px; border-radius:2px; behavior: url(../../../script/pie.htc); }

.wp-bg-set-position-block span.wp-bg-set-top-left{ background:url(../images/wp-bg-set-position.png) no-repeat;}
.wp-bg-set-position-block span.wp-bg-set-top-right{ background:url(../images/wp-bg-set-position.png) no-repeat  -34px 0;}
.wp-bg-set-position-block span.wp-bg-set-top-center{ background:url(../images/wp-bg-set-position.png) no-repeat 0 -34px;}
.wp-bg-set-position-block span.wp-bg-set-middle-left{ background:url(../images/wp-bg-set-position.png) no-repeat -34px -34px;}
.wp-bg-set-position-block span.wp-bg-set-middle-center{ background:url(../images/wp-bg-set-position.png) no-repeat 0 -51px;}
.wp-bg-set-position-block span.wp-bg-set-bottom-left{ background:url(../images/wp-bg-set-position.png) no-repeat -17px 0;}
.wp-bg-set-position-block span.wp-bg-set-bottom-right{ background:url(../images/wp-bg-set-position.png) no-repeat -51px 0;}
.wp-bg-set-position-block span.wp-bg-set-bottom-center{ background:url(../images/wp-bg-set-position.png) no-repeat -34px -51px;}

.wp-bg-set-position-block span.wp-bg-tl-selected{ background:url(../images/wp-bg-set-position.png) no-repeat 0 -17px; }
.wp-bg-set-position-block span.wp-bg-tc-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -17px -34px; }
.wp-bg-set-position-block span.wp-bg-tr-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -34px -17px; }
.wp-bg-set-position-block span.wp-bg-ml-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -17px -34px; }
.wp-bg-set-position-block span.wp-bg-mc-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -51px -34px; }
.wp-bg-set-position-block span.wp-bg-mr-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -17px -51px; }
.wp-bg-set-position-block span.wp-bg-bl-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -17px -17px; }
.wp-bg-set-position-block span.wp-bg-bc-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -51px -51px; }
.wp-bg-set-position-block span.wp-bg-br-selected{ background:url(../images/wp-bg-set-position.png) no-repeat -51px -17px; }

.wp-background-bgcolor-sample{ width:30px; height:30px; -moz-border-radius:2px; -webkit-border-radius:2px; border-radius:2px; behavior: url(../images/pie.htc);  border:1px solid #adadad; background:url(../images/wp-background-preview-bg.gif); margin-left:7px; background:url(../images/wp-background-bgcolor-set-block.gif) no-repeat;}
</style>

<?php echo Notice::get('mod_site/msg');?>
<ul style="margin-left:1px;height:51px;line-height:51px;">
	<li style="margin-top:14px;"><?php include_once(P_TPL.'/common/language_switch.php');?></li>
</ul>
<?php
$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->setEncType('multipart/form-data');
$sinfo_form->p_open('mod_site', 'save_bg_info');
?>
<div style="overflow:auto;width:100%;">
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
			echo Html::input('submit', 'submit', __('Save'));
			echo Html::input('hidden', 'si[s_locale]', $lang_sw);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
       
        <tr>
            <td class="label""><?php _e('Select File'); ?></td>
            <td class="entry">
			<?php
			$seria = unserialize(BACKGROUND_INFO);
			$seria_arr = $seria[$lang_sw];
			if($seria_arr['img']!=''){
			?>
			<img src="../upload/image/<?php echo $seria_arr['img'];?>" width=100 height=100>
			<?php }?>
			<br />
            <?php
          echo  Html::input('file', 'background_img');
		   ?>
            </td>
        </tr>
       
        <tr>
            <td class="label"><?php _e('Background position'); ?></td>
            <td class="entry" style="line-height:20px;">
            <div class="wp-manage_panel_pic_left">
<div class="wp-manage_panel_block_two overz">
<div class="wp-manage_panel_block_two_c">
<div id="wp-bgtabs_2" style="">
<div class="wp-background-set overz">
<div class="wp-background-preview" id="background-img"></div><!--//wp-background-preview end-->
<div class="wp-background-upload overz">
<p class="wp-bg-remove"><span><a href="#" onclick="dialog_global.clearBgImage();return false;"><img src="../images/remove-background.gif" width="14" height="14" align="absmiddle" border="0" /><?php _e('Remove background'); ?></a></span>
  <span><input type="checkbox" name="si[fixed]" id="background-attachment" value="scroll" onclick="dialog_global.selche(this)" <?php if($seria_arr['fixed']=='fixed'){echo 'checked';}?> /><?php _e('Fixed background'); ?></span>
  
</p>
</div><!--//wp-background-upload end-->
</div><!--//wp-background-set end-->
<div class="wp-background-set-bottom overz">
<div class="wp-background-set-repeat">
<ul >
<li style="margin-right:20px;margin-left:0px;"><input type="radio" name="si[radio]" id="radio1" value="repeat" <?php if($seria_arr['radio']=='repeat'){echo 'checked';}?> /><?php _e('repeat'); ?></li>
<li style="margin-right:20px;margin-left:0px;"><input type="radio" name="si[radio]" id="radio2" value="repeat-x" <?php if($seria_arr['radio']=='repeat-x'){echo 'checked';}?> /><?php _e('repeat-x'); ?></li>
<li style="margin-right:20px;margin-left:0px;"><input type="radio" name="si[radio]" id="radio3" value="repeat-y" <?php if($seria_arr['radio']=='repeat-y'){echo 'checked';}?> /><?php _e('repeat-y'); ?></li>
<li style="margin-right:20px;margin-left:0px;"><input type="radio" name="si[radio]" id="radio4" value="no-repeat" <?php if($seria_arr['radio']=='no-repeat'){echo 'checked';}?> /><?php _e('no-repeat'); ?></li>

</ul>
</div>
<div class="wp-background-set-position">
<div class="wp-bg-set-position-block">
<?php if(trim($seria_arr['position'])=='' || trim($seria_arr['position'])=='left top'){?>
<span class="wp-bg-set-top-left wp-bg-tl-selected"></span>
<?php }else{?>
<span class="wp-bg-set-top-left"></span>
<?php }?>
<span class="wp-bg-set-top-center <?php if(trim($seria_arr['position'])=='center top'){echo 'wp-bg-tc-selected';}?>"></span>
<span class="wp-bg-set-top-right <?php if(trim($seria_arr['position'])=='right top'){echo 'wp-bg-tr-selected';}?>"></span>
<span class="wp-bg-set-middle-left <?php if(trim($seria_arr['position'])=='left center'){echo 'wp-bg-ml-selected';}?>"></span>
<span class="wp-bg-set-middle-center <?php if(trim($seria_arr['position'])=='center center'){echo 'wp-bg-mc-selected';}?>"></span>
<span class="wp-bg-set-middle-right <?php if(trim($seria_arr['position'])=='right center'){echo 'wp-bg-mr-selected';}?>"></span>
<span class="wp-bg-set-bottom-left <?php if(trim($seria_arr['position'])=='left bottom'){echo 'wp-bg-bl-selected';}?>"></span>
<span class="wp-bg-set-bottom-center <?php if(trim($seria_arr['position'])=='center bottom'){echo 'wp-bg-bc-selected';}?>"></span>
<span class="wp-bg-set-bottom-right <?php if(trim($seria_arr['position'])=='right bottom'){echo 'wp-bg-br-selected';}?>"></span>
</div>
</div>
<input type="hidden" name="si[postion]" id="si_position" value="<?php echo trim($seria_arr['position']);?>">
</div><!--//wp-background-set-bottom end-->
</div><!--//wp-bgtabs_2 end-->
</div><!--//wp-manage_panel_block_two_c end-->
</div><!--//wp-manage_panel_block_two end-->
</div><!--//wp-manage_panel_pic_left end-->
<div id="pic_choose_div" style="display:none"></div>
            </td>
        </tr>
          <tr>
            <td class="label""><?php _e('Background color'); ?></td>
            <td class="entry">
            <div class="wp-background-set-bgcolor">
<div class="wp-background-bgcolor-sample" id="backcolor_div2" <?php if(!empty($seria_arr['color'])){?>style="background-image: none; background-color: <?php echo $seria_arr['color'];?>;" <?php }?> onclick="dialog_global.setFontColor();">
<input type="hidden" name="si[color]" id="si_color" value="">
</div>
</div>
            </td>
        </tr>
  
    </tbody>
</table>
</div>
<?php
$sinfo_form->close();
?>
