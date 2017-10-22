<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<?php
$url_info = '_all';

//$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
//$sinfo_form->setEncType('multipart/form-data');
//$sinfo_form->p_open('mod_media', 'save_banner1');


$p_banner_url_info=$p_banner[$url_info];
$lhtype='';
if(isset($p_banner_url_info['lhtype'])){$lhtype=$p_banner_url_info['lhtype'];}
if(isset($p_banner_url_info['img_width'])){$img_width=$p_banner_url_info['img_width'];}
if(isset($p_banner_url_info['img_height'])){$img_height=$p_banner_url_info['img_height'];}

if(isset($p_banner_url_info['sp_title'])){$sp_title=$p_banner_url_info['sp_title'];}
if(isset($p_banner_url_info['islink'])){$isLink=$p_banner_url_info['islink'];}
if(isset($p_banner_url_info['linkaddr'])){$LinkAddr=$p_banner_url_info['linkaddr'];}
if(isset($p_banner_url_info['img_order'])){$img_order=$p_banner_url_info['img_order'];}

if(isset($p_banner_url_info['img_src'])){$img_src=$p_banner_url_info['img_src'];}
if(isset($p_banner_url_info['geshi'])){$geshi=$p_banner_url_info['geshi'];}
if(isset($p_banner_url_info['flv_src'])){$flv_src=$p_banner_url_info['flv_src'];}else{$flv_src='';}
if(isset($p_banner_url_info['img_open_type'])){$img_open_type=$p_banner_url_info['img_open_type'];}
if(isset($p_banner_url_info['play_speed'])){$play_speed=$p_banner_url_info['play_speed'];}//播放速度
if(isset($img_src)){$img_srcnum=count($img_src);}

if(!isset($geshi)){$geshi=2;}
if(!isset($img_srcnum)){$img_srcnum=0;}

?>
<form name="sinfoform" id="sinfoform" enctype="multipart/form-data" onsubmit="javascript:return check_sinfo_info(this);" action="index.php" method="post">
<input type="hidden" name="_m" id="_m" value="mod_media"  /><input type="hidden" name="_a" id="_a" value="save_banner1"  /><input type="hidden" name="_r" id="_r" value="_page"  />
<div style="overflow:auto;width:100%;">
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
	<tfoot>
		<tr>
            <td colspan="2">
            
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo Html::input('submit', 'submit', __('Save'));
            if(isset($curr_siteinfo->id)){echo Html::input('hidden', 'si[id]', $curr_siteinfo->id);}
            echo Html::input('hidden', 'si[s_locale]', $lang_sw);
            echo Html::input('hidden', 'getParams1','');
            echo Html::input('hidden', 'allshow','no');
            ?>            </td>
        </tr>
	</tfoot>
	<tbody>
		<?php
			echo Html::input('hidden', 'banner[id]', $curr_banner->id);
				if(isset($p_banner[$url_info]['img_src'])){echo Html::input('hidden', 'param[banner_img]', $p_banner[$url_info]['img_src']);}
			// 编辑模式下删除编辑前的对应图片文件
            if (!empty($p_banner[$url_info]['img_src'])) {
            	$del_banner_img = $p_banner[$url_info]['img_src'];
            }
            if (!empty($p_banner[$url_info]['flv_src'])) {
            	$del_banner_img = $p_banner[$url_info]['flv_src'];
            }
            echo Html::input('hidden', 'param[del_banner_img]', $del_banner_img);
		?>
        <tr>
        <td class="label"><?php echo __('Play file format');?></td>
            <td class="entry">          
            <select name="geshi" onchange="changegs(this.value)">
             <option value="3"  <?php if($geshi=='3') echo 'selected="selected"';?> ><?php echo __('Single image file');?></option>
            <option value="1"  <?php if($geshi=='1') echo 'selected="selected"';?> ><?php echo __('Mulit image file');?></option>
              <option value="2"  <?php if($geshi=='2') echo 'selected="selected"';?> ><?php echo __('Flash file');?></option>
            </select>    <input type="submit" name="moddelxx"  value="<?php echo __('Delete a custom record');?>"  onclick="delallitem(this.form,9)" style="display:none" />     
            <?php
            //var $my_file,$string,$subject,$pattern,$matches,$subject1,$pattern1,$matches1,$subject2,$pattern2,$matches2,$subject3,$pattern3,$matches3,$def_width,$margin_left;
$my_file = file_get_contents('../template/'.DEFAULT_TPL.'/css/style.css');
$string = $my_file;
//$subject = '#banner { width:990px; overflow:hidden; margin:10px auto 0px auto ; clear:both; text-align:center;}xxxx';
$subject = $my_file;
$pattern = '/(#banner\s*{)(.)*(})/i';
preg_match($pattern, $subject, $matches);
//print_r($matches);
$def_width=0;
$margin_left=0;
if(isset($matches[0])&&$matches[0]){
	$subject1 = $matches[0];
	$pattern1 = '/(width:)(\s)*(\d+)(px)/i';
	preg_match($pattern1, $subject1, $matches1);
	//print_r($matches1);
	if($matches1){$def_width=$matches1[3];}
	
	$subject2 = $matches[0];
	$pattern2 = '/(margin:)(\s)*(\d+)(px)/i';
	preg_match($pattern2, $subject2, $matches2);
	//print_r($matches2);
			if($matches2){
				$margin_left=$matches2[3];
			}else{	
				$subject3 = $matches[0];
				$pattern3 = '/(margin-left:)(\s)*(\d+)(px)/i';
				preg_match($pattern3, $subject3, $matches3);
				//print_r($matches3);
				if($matches3){$margin_left=$matches3[3];}
			}
}


$def_width=$def_width*1-$margin_left*2;
if($def_width==0){$def_width=960;}
if($url_info){
$single_banner = $p_banner[$url_info];
$single1 = $single_banner['single_img_src'];
$single2 = $single_banner['single_img_link'];
}else{
$single_banner = $p_banner['_all'];
}
			?></td>
        </tr>
		 <tr  id="imagefile" style="display:<?php if($geshi==3){echo '';}else{echo 'none';}?>">
         <td class="label"><?php echo __('Select a picture');?></td>
            <td class="entry">          
            <input type="text" name="single_image" id="single_image" value="<?php echo $single1;?>" class="txtinput" />&nbsp;<a href="#" onclick="popup_win=show_imgpickers('single_image');return false;" title=""><b><?php echo __('Select a picture');?></b></a>&nbsp;&nbsp; </td>
        </tr>
		<tr id="img_link"  <?php if($geshi==3){echo '';}else{ ?> style="display:none;" <?php } ?>>
		<td  class="label"><?php echo __('Link Addr');?></td>
        <td class="entry"  style="text-align:left"> <input size="25"  type="text" name="imglink" value="<?php echo $single2;?>"  style="width:100px">&nbsp;&nbsp;<img class="title" src="template/images/answer1.gif" alt="help" title="<?php echo __('Click the picture after the jump to the web site');?>" /></td></tr>
          <tr id=img_link_open <?php if($geshi==3){echo '';}else{ ?> style="display:none;" <?php } ?>>
            <td class="label"><?php echo __("open image in window from self or blank"); ?></td>
            <td class="entry"   style="text-align:left" ><input type="radio" name="img_link_open" value="1" /><?php echo __("image self"); ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="img_link_open" value="2" />&nbsp;&nbsp;&nbsp;<?php echo __("image blank"); ?>            </td>

			
        </tr>
        
        <!--单图片结束-->
        <tr  id="flashfile" style="display:<?php if($geshi==2){echo '';}else{echo 'none';}?>">
         <td class="label"><?php echo __('Swf file');?></td>
            <td class="entry">          
            <input type="file" name="banner_file" id="banner_file" value=""  /> <?php if($flv_src){echo ' √';}else{echo ' X';}?>  <BR />√:<?php echo __('File note yes');?>  X:<?php echo __('File note no');?>&nbsp;&nbsp;</td>
        </tr>
          <tr id="typelhselect" style=" <?php if($geshi=='1'){ echo 'display:';}else{ echo 'display:none';} ?> "><td width="10%"  class="label"><?php echo __('Rotation Type');?></td><td width="90%" class="entry">
            <div style="float:left"><select name="lhtype" onChange="chgtype(this.value)">
           <?php if(!$lhtype){$lhtype=4;}?>
           <option value="4" <?php if($lhtype=='4') echo 'selected="selected"';?>><?php echo __('Effect1');?></option>        
               <!--option value="11" <?php if($lhtype=='11') echo 'selected="selected"';?>><?php echo __('Effect2');?></option--> 
                <option value="12" <?php if($lhtype=='12') echo 'selected="selected"';?>><?php echo __('Effect2');?></option>
            <option value="1" <?php if($lhtype=='1') echo 'selected="selected"';?> ><?php echo __('Effect3');?></option>
            <option value="2" <?php if($lhtype=='2') echo 'selected="selected"';?>><?php echo __('Effect4');?></option>
            <option value="3" <?php if($lhtype=='3') echo 'selected="selected"';?>><?php echo __('Effect5');?></option>       
            <option value="9" <?php if($lhtype=='9') echo 'selected="selected"';?>><?php echo __('Effect6');?></option>    
        <option value="13" <?php if($lhtype=='13') echo 'selected="selected"';?>><?php echo __('Effect7');?></option>  
        <option value="14" <?php if($lhtype=='14') echo 'selected="selected"';?>><?php echo __('Effect8');?></option>
		<option value="15" <?php if($lhtype=='15') echo 'selected="selected"';?>><?php echo __('Effect9');?></option>   
		<option value="16" <?php if($lhtype=='16') echo 'selected="selected"';?>><?php echo __('Effect10');?></option>               
            </select>
            <div id="lhtypevalue" style="display:none"><?php echo $lhtype;?></div> <input onclick="openshow()"  type="button" name="showjsdemo"  value="<?php echo __('Preview');?>"/>

            <script language="javascript">
				function chgtype(v){
				 document.getElementById('lhtypevalue').innerHTML=v;
				}
				function openshow(){
					var lhtypevalue;
					 lhtypevalue=document.getElementById('lhtypevalue').innerHTML*1;
					 window.open('../index.php?_v=preview&_c=o&lhtype='+lhtypevalue);
				}
			</script>
            <?php if($img_srcnum==0){$img_srcnumx=1;}else{$img_srcnumx=$img_srcnum;}
			
			if($img_srcnum==0){$bdiv="0 1";}
			if($img_srcnum==1){$bdiv="0 1";}
			if($img_srcnum==2){$bdiv="0 1 1";}
			if($img_srcnum==3){$bdiv="0 1 1 1";}
			if($img_srcnum==4){$bdiv="0 1 1 1 1";}
			if($img_srcnum==5){$bdiv="0 1 1 1 1 1";}
			?>
            
              <input type="hidden" value="<?php echo $img_srcnumx;?>" name="addv" />
              <input type="hidden" value="-1" id="delv" name="delv" />
               <input type="hidden" value="-1" id="delall" name="delall" />
             
              <input type="hidden" value="<?php echo $img_srcnumx;?>" name="realshow" id="realshow" />
               <input type="hidden" value="<?php echo $img_srcnumx;?>" name="realnum" id="realnum" />
               <input type="hidden" name="b" value="<?php echo $bdiv;?>" id="b">               
              <div id="showreal" style="display:none"><?php echo $img_srcnumx;?></div><div id="numreal"  style="display:none"><?php echo $img_srcnumx;?></div>
               <div id="bdiv"  style="display:none"><?php echo $bdiv;?></div>
              
              <script language="javascript">
			  var arrreal = new Array();
			   arrreal[0]="0";
			   <?php 
			   for($kkk=1;$kkk<$img_srcnumx+1;$kkk++){
			   ?>
			   arrreal[<?php echo $kkk;?>]="1";
			   <?php
			   }
			   ?>
			   function changegs(n){
			   document.getElementById('show_banner_note').style.display="";
				  document.getElementById('flashfile').style.display="none";
				   document.getElementById('addmfirst').style.display="";
				    document.getElementById('addclick').style.display="";
					 document.getElementById('typelhselect').style.display="";
				    document.getElementById('imagefile').style.display="none";
				    document.getElementById('img_link').style.display="none";
				   document.getElementById('img_link_open').style.display="none";
				    document.getElementById('addm').style.display="";
					document.getElementById('scroll_speed').style.display="";
				  if(n==2){
				   document.getElementById('show_banner_note').style.display="none";
				  document.getElementById('flashfile').style.display="";
				  document.getElementById('imagefile').style.display="none";
				   document.getElementById('imagefile').style.display="none";
				    document.getElementById('img_link').style.display="none";
				   document.getElementById('img_link_open').style.display="none";
				    document.getElementById('addmfirst').style.display="none";
					  document.getElementById('addclick').style.display="none";
				    document.getElementById('addm').style.display="none";
					 document.getElementById('typelhselect').style.display="none";
					 document.getElementById('scroll_speed').style.display="none";
				  }
				  if(n==3){
				   document.getElementById('show_banner_note').style.display="none";
				   document.getElementById('imagefile').style.display="";
				    document.getElementById('imagefile').style.display="";
				    document.getElementById('img_link').style.display="";
				   document.getElementById('img_link_open').style.display="";
				  document.getElementById('flashfile').style.display="none";
				    document.getElementById('addmfirst').style.display="none";
					  document.getElementById('addclick').style.display="none";
				    document.getElementById('addm').style.display="none";
					 document.getElementById('typelhselect').style.display="none";
					 document.getElementById('scroll_speed').style.display="none";
				  }
			  }
			  function additem(){
						var realshow;
						var htmltr = '';
						var realnum;
						 realshow=document.getElementById('showreal').innerHTML*1+1;
						 if(realshow>5){
							 alert('<?php echo __('Only for the five largest');?>');
						 
						 }else{
							 realnum=document.getElementById('numreal').innerHTML*1+1;
							 
							 document.getElementById('showreal').innerHTML=realshow;
							 document.getElementById('realshow').value=realshow;
							 
							 document.getElementById('numreal').innerHTML=realnum;
							 document.getElementById('realnum').value=document.getElementById('numreal').innerHTML;
							 
							 
							 arrreal[realnum]="1";
						
							  document.getElementById('bdiv').innerHTML=arrreal.join(" ");
							 document.getElementById('b').value=document.getElementById('bdiv').innerHTML;
							 
							htmltr += '<table width=100% border="0" align="left" id="loop'+realnum+'" style=" clear:both; border-top:1PX solid #993300;display:">';
							htmltr += '<tr><td width=10%  class="label"><?php echo __('Title');?>'+realnum+'</td>';
							htmltr += '<td width=90% class="entry" style="text-align:left"><input type="text" name="sp_title[]" value=""  style="width:100px"  />              &nbsp; <input type="button" name="moddel"  value="<?php echo __('Remove this item');?>"  onclick="delitem(this.form,'+realnum+')" /></td>';
						
							htmltr += '</tr>';
							
							
							htmltr += '<tr id=slide_img_srtr >';
							htmltr += '<td class="label"><?php echo __('Select a picture');?></td>';
							htmltr += ' <td class="entry"  style="text-align:left">';
							htmltr += '<input type="text" name="ex_params[]" id="ex_params_'+realnum+'_" value="" class="txtinput" />&nbsp;<a href="#" onclick="popup_win=show_imgpickers(\'ex_params['+realnum+']\');return false;" title=""><b><?php echo __('Select a picture');?></b></a>&nbsp;&nbsp;&nbsp;</td>';
							htmltr += '</tr>';
							
							htmltr += '<tr><td  class="label"><?php echo __('Link mode');?></td><td class="entry"  style="text-align:left">';
					
							 
								htmltr += '<select name="isLink[]" onchange="selectChange(this,'+realnum+')">';
								htmltr += ' <option value="no"  ><?php echo __('No link');?></option>';
								htmltr += ' <option value="yes" selected="selected" ><?php echo __('Link');?></option>';
								htmltr += '</select>';
								htmltr += '</td></tr>';
								
								htmltr += ' <tr id=banner_link'+realnum+'><td  class="label"><?php echo __('Link Addr');?></td><td class="entry"  style="text-align:left"> <input size="25"  type="text" name="LinkAddr[]" value="http://"  style="width:100px">&nbsp;&nbsp;<img class="title" src="template/images/answer1.gif" alt="help" title="<?php echo __('Click the picture after the jump to the web site');?>" /></td></tr>';
								htmltr +='<tr id=banner_link_open'+realnum+' ><td class="label"><?php echo __("open image in window from self or blank"); ?></td><td class="entry"   style="text-align:left" ><input type="radio" name="link_open'+realnum+'[]" id="ex_params_image_open_" value="1" checked /><?php echo __("image self"); ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="link_open'+realnum+'[]" id="ex_params_image_open_" value="2"  />&nbsp;&nbsp;&nbsp;<?php echo __("image blank"); ?></td></tr>';
								htmltr += ' <tr><td  class="label"><?php echo __('Display order');?></td><td class="entry"  style="text-align:left"> <input size="25"  type="text" name="img_order[]" value=""  style="width:100px"></td></tr>';   
							 
						
							htmltr += '</table>';						
							
							//  document.getElementById('addm').innerHTML+=htmltr;
							$("#addm").append(htmltr); 
							//  $('#addm').after(htmltr);
							  }
							  
			  }
			  
			  function delitem(m,n){
					 document.getElementById('loop'+n).style.display="none";
					  $('#loop'+n).remove();
					 var realshow;

					realshow=document.getElementById('showreal').innerHTML*1-1;
			 
					document.getElementById('showreal').innerHTML=realshow;
					document.getElementById('realshow').value=document.getElementById('showreal').innerHTML;
					arrreal[n]="0";
				
					document.getElementById('bdiv').innerHTML=arrreal.join(" ");
					document.getElementById('b').value=document.getElementById('bdiv').innerHTML;
			  }
			   function delallitem(m,n){
			  	m.delall.value=n;
			  }
			  
			  
		
			  </script>
              </div>  <div id="addclick" style=" float:left;<?php if($geshi==2){echo '';}else{echo 'none';}?>">   <a href="javascript:void(0);" onclick="additem();return false;"><font style=" margin-left:15px; font-size:14px; font-weight:bold;"><?php echo __('Add Slide items');?></font></a></div>
              </td></tr>
            <tr>
            <td class="label"><?php _e('Size'); ?></td>
            <td class="entry">
            <?php
            if($p_banner[$url_info]['img_width']==0){$p_banner[$url_info]['img_width']=$def_width;}
			if($p_banner[$url_info]['img_height']==0){$p_banner[$url_info]['img_height']=round($def_width/3,0);}
			
            echo Html::input('text', 'param[banner_width]', $p_banner[$url_info]['img_width'], 
                'class="textinput" style="width:40px;"', $mod_form);
            ?>
            &times;
            <?php
            echo Html::input('text', 'param[banner_height]', $p_banner[$url_info]['img_height'], 
                'class="textinput" style="width:40px;"', $mod_form);
            ?>         <?php echo __('Is recommend');?>：<?php echo __('Width');?> <?php echo $def_width;?>px &nbsp;&nbsp; <?php echo __('Height');?> <?php echo round(($def_width/3),0);?>px   
             <div id="show_banner_note"  style="display:<?php if($geshi==2){echo 'none';}else{echo '';}?>"><?php _e("Banner note");?>            </td>
        </tr>
		<tr id="scroll_speed"  style="display:<?php if($geshi==1){echo '';}else{echo 'none';}?>">
            <td class="label"><?php _e("Play speed");?></td>
            <td class="entry">
           
			<input size="25"  type="text" name="play_speed" value="<?php echo $play_speed?$play_speed:5000;?>"  style="width:100px">
			<?php _e("Larger value, the slower the speed,preferred values");?>：5000
			</td>
        </tr>
		<tr>
            <td class="label"><?php _e('Overcast Banner'); ?></td>
            <td class="entry">
            <input type="radio" value="1" id="overfast" name="radio[overfast]"><?php _e('Yes');?>&nbsp;&nbsp;&nbsp;<input type="radio" value="0" id="overfast" name="radio[overfast]" checked ><?php _e('No');?>
            </td>
        </tr>
        
       
	
        <tr><td colspan="2"  class="label">    
     <div id="addmfirst" style="clear:both;<?php if($geshi==1){echo 'display:';}else{echo 'display:none';}?> ">    
  <?php 
		
		for($kk=1;$kk<$img_srcnumx+1;$kk++){

		?>
        
        <table width="100%" border="0" align="left"  id="loop<?php echo $kk;?>" style="border-top:1px solid #CC3300; clear:both;">
            <tr><td width="10%"  class="label"><?php echo __('Title');?><?php echo $kk;?></td>
            <td width="90%" class="entry" style="text-align:left"><input type="text" name="sp_title[]" value="<?php if(isset($sp_title[$kk-1])){echo $sp_title[$kk-1];}?>" />              
            <input type="button" name="moddel"  value="<?php echo __('Remove this item');?>"   onclick="delitem(this.form,<?php echo $kk;?>)" />
            </td>
            </tr>
        <tr id=slide_img_srtr >
            <td class="label"><?php echo __('Select a picture');?></td>
          <td class="entry"  style="text-align:left">
        <input type="text" name="ex_params[]" id="ex_params_<?php echo ($kk);?>_" value="<?php if(isset($img_src[$kk-1])){echo $img_src[$kk-1];}?>" class="txtinput" />&nbsp;<a href="#" onClick="popup_win=show_imgpickers('ex_params[<?php echo ($kk);?>]');return false;" title=""><b><?php echo __('Select a picture');?></b></a>&nbsp;&nbsp;&nbsp;</td>
       <?php
			 ?> </tr>
       
         <tr><td  class="label"><?php echo __('Link mode');?></td><td class="entry"  style="text-align:left">
  
             <select name="isLink[]" id=" banner_link_select" onchange="selectChange(this,<?php echo $kk; ?>)">
            <option value="yes" <?php   if(isset($isLink[$kk-1])){	if($isLink[$kk-1]=='yes')  	{ echo 'selected="selected"';    	}else{ 	}     }else{   	echo 'selected="selected"';  }	?>><?php echo __('Link');?></option>
			<option value="no"  <?php if(isset($isLink[$kk-1])&&$isLink[$kk-1]=='no') echo 'selected="selected"';?>><?php echo __('No link');?></option>
              

            </select>

           </td></tr>
           
          <tr id="banner_link<?php echo $kk; ?>"  <?php if(isset($isLink[$kk-1])&&$isLink[$kk-1]=='no'){ ?> style="display:none;" <?php } ?>><td  class="label"><?php echo __('Link Addr');?></td>
          <td class="entry"  style="text-align:left"> <input size="25"  type="text" name="LinkAddr[]" value="<?php if(isset($LinkAddr[$kk-1])){echo $LinkAddr[$kk-1];}else{echo "http://";}?>"  style="width:100px">&nbsp;&nbsp;<img class="title" src="template/images/answer1.gif" alt="help" title="<?php echo __('Click the picture after the jump to the web site');?>" /></td></tr>
          <tr id=banner_link_open<?php echo $kk; ?>   <?php if(isset($isLink[$kk-1])&&$isLink[$kk-1]=='no'){ ?> style="display:none;" <?php } ?>>
            <td class="label"><?php echo __("open image in window from self or blank"); ?></td>
            <td class="entry"   style="text-align:left" ><input type="radio" name="link_open<?php echo $kk;?>[]" id="ex_params_image_open_" value="1" <?php if(!empty($img_open_type[$kk][0])&&$img_open_type[$kk][0]==1){echo "checked";}?> /><?php echo __("image self"); ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="link_open<?php echo $kk;?>[]" id="ex_params_image_open_" value="2" <?php if(!empty($img_open_type[$kk][0])&&$img_open_type[$kk][0]==2){echo "checked";}?> />&nbsp;&nbsp;&nbsp;<?php echo __("image blank"); ?>            </td>

			
        </tr>
       
            <tr><td  class="label"><?php echo __('Display order');?></td><td class="entry"  style="text-align:left"> <input size="25"  type="text" name="img_order[]" value="<?php if(isset($LinkAddr[$kk-1])){echo $img_order[$kk-1];}?>"  style="width:100px"></td></tr>
        </table>
        
          <?php

	  }
	  ?>
      </div>
       <div id="addm" style="clear:both;">
      
        </div>
        </td></tr>
        
    

            
	</tbody>
</table>
</div>
</form>
 <script language="javascript">
       
       function selectChange(obj,ind){
       		if($(obj).val()=='no'){
       			$("#banner_link"+ind).hide(); 
       			$("#banner_link_open"+ind).hide();
       		}else{
       			$("#banner_link"+ind).show();
       			$("#banner_link_open"+ind).show();
       		}
       }
        </script>
<script type="text/javascript" language="javascript"> 
<!--
function check_sinfo_info(thisForm)
{
 document.getElementById('realshow').value=document.getElementById('showreal').innerHTML;
 document.getElementById('b').value=document.getElementById('bdiv').innerHTML;
 document.getElementById('realnum').value=document.getElementById('numreal').innerHTML;
return true;
}
-->
</script>
<?php
//$sinfo_form->close();
//$sinfo_form->writeValidateJs();
die;
?>