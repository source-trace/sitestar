<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="flash_image">
<?php
if(isset($lhtypeview)&&$lhtypeview){
	$img_ordernum=5;
	if($img_order){unset( $img_order);}
	
	$img_order[0]='1';
	$img_order[1]='2';
	$img_order[2]='3';
	$img_order[3]='4';
	$img_order[4]='5';

	if($img_src){unset( $img_src);}
	
	$img_src[0]='script/banner/images/1.jpg';
	$img_src[1]='script/banner/images/2.jpg';
	$img_src[2]='script/banner/images/3.jpg';
	$img_src[3]='script/banner/images/4.jpg';
	$img_src[4]='script/banner/images/5.jpg';

	if($islink){unset( $islink);}
	
	$islink[0]='yes';
	$islink[1]='yes';
	$islink[2]='yes';
	$islink[3]='yes';
	$islink[4]='yes';
	
	if($linkaddr){unset( $linkaddr);}
	
	$linkaddr[0]='#';
	$linkaddr[1]='#';
	$linkaddr[2]='#';
	$linkaddr[3]='#';
	$linkaddr[4]='#';
	
	if($sp_title){unset( $sp_title);}
		
	$sp_title[0]='aaaaa';
	$sp_title[1]='bbbbb';
	$sp_title[2]='ccccc';
	$sp_title[3]='ddddd';
	$sp_title[4]='eeeee';

	$my_file = file_get_contents('template/'.DEFAULT_TPL.'/css/style.css');
	$string = $my_file;

	$subject = $my_file;
	$pattern = '/(#banner\s*{)(.)*(})/i';
	preg_match($pattern, $subject, $matches);

	$def_width=0;
	$margin_left=0;
	
	if(isset($matches[0])&&$matches[0]){	
		$subject1 = $matches[0];
		$pattern1 = '/(width:)(\s)*(\d+)(px)/i';
		preg_match($pattern1, $subject1, $matches1);
		if($matches1){$def_width=$matches1[3];}
		
		$subject2 = $matches[0];
		$pattern2 = '/(margin:)(\s)*(\d+)(px)/i';
		preg_match($pattern2, $subject2, $matches2);

		if($matches2){
			$margin_left=$matches2[3];
		}else{	
			$subject3 = $matches[0];
			$pattern3 = '/(margin-left:)(\s)*(\d+)(px)/i';
			preg_match($pattern3, $subject3, $matches3);
			if($matches3){$margin_left=$matches3[3];}
		}
	}

	$def_width=$def_width*1-$margin_left*2;
	if($def_width==0){$def_width=960;}
	$def_height= round(($def_width/3),0);
	$img_width=$def_width;
	$img_height=$def_height;
	require_once("script/banner/a".$lhtypeview."/banner.php");
}else{

	if(isset($geshi)&&$geshi==1){
		if($img_src){
			if(is_array($img_src)){	
				$urlstr='';		
				$kmv=0;
				$img_ordertmp="";
				$islinktmp="";
				$linkaddrtmp="";
				$img_srctmp="";
				$sp_titletmp="";
				
				foreach($img_order as $k=>$v){
					if($kmv==0){
						$img_ordertmp.=$v;
						$islinktmp.=$islink[$k];
						$linkaddrtmp.=$linkaddr[$k];
						$img_srctmp.=$img_src[$k];
						$sp_titletmp.=$sp_title[$k];
					}else{
						$img_ordertmp.="||".$v;
						$islinktmp.="||".$islink[$k];
						$linkaddrtmp.="||".$linkaddr[$k];
						$img_srctmp.="||".$img_src[$k];
						$sp_titletmp.="||".$sp_title[$k];
					}
					$kmv++;
				}
				
				$bz=1;
				$urlstr.='img_order='.$img_ordertmp;
				$urlstr.='&islink='.$islinktmp;
				$urlstr.='&linkaddr='.$linkaddrtmp;
				$urlstr.='&img_src='.$img_srctmp;
				$urlstr.='&sp_title='.$sp_titletmp;				
				$urlstr.='&img_height='.$img_height;
				$urlstr.='&img_width='.$img_width;
				$urlstr.='&bz='.$bz;			
				$img_widthx=$img_width;
				$img_heightx=$img_height;
				if($lhtype==1){$img_heightx=$img_height+28;}
				if($lhtype==3){$img_heightx=$img_height+24;}
				if($lhtype==4){$img_heightx=$img_height+40;}
				if($lhtype==5){$img_heightx=$img_height+0;}
				if($lhtype==6){$img_heightx=$img_height+72;}
				if($lhtype==9){$img_heightx=$img_height+70;}					
				asort($img_order);
				$img_ordernum=count($img_order);			
				$bz=12;
				if($bz==1){			
					require_once("script/banner/a".$lhtype."/index.html");
				}else{				
					require_once("script/banner/a".$lhtype."/banner.php");
				}	
				
			}else{	
				if (strstr($img_src,"sitelogo.png")) {
					unset($img_src);
				}else{
				if ($islink == 'yes') {
					
				?><a href="<?php echo $img_url; ?>" <?php if ($$img_open_type=='1'){ ?> target="_blank"><?php }else{ ?> target='_self' <?php } ?>>
				<img src="<?php echo $img_src; ?>" alt="<?php echo $img_desc; ?>"<?php echo $str_img_width.$str_img_height; ?> /></a>
				<?php
				} else {
				?>
				<img src="<?php echo $img_src; ?>" alt="<?php echo $img_desc; ?>"<?php echo $str_img_width.$str_img_height; ?> />
				<?php 
				}
				}
			}		
		}else{
			if(SessionHolder::get('user')){
					echo '<div>&nbsp;</div>';
				}
		}
	}elseif(isset($geshi)&&$geshi==2){
		if(file_exists($flv_src)){?>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"<?php echo $str_flv_width.$str_flv_height; ?>>
            <param name="movie" value="<?php echo $flv_src; ?>" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <embed src="<?php echo $flv_src; ?>"<?php echo $str_flv_width.$str_flv_height; ?> quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
            </object>	
		<?php 
        }else{
            if(file_exists($img_src)){
				if (strstr($img_src,"sitelogo.png")) {
				unset($img_src);
				}else{
            ?>
                <img alt="<?php echo $curr_siteinfo->site_name;?>" src="<?php echo $img_src;?>" style=" <?php echo $str_flv_width.$str_flv_height; ?> " />
            <?php
				}
            }
        }	
	}elseif(isset($geshi)&&$geshi==3){
		if(!$single_img_src){$single_img_src=$img_src;}

		if(file_exists($single_img_src)){
			if (!empty($single_img_link)) {
				$img_target = $single_link_open==1?"_self":"_blank";
				?>
			<a href="<?php echo $single_img_link; ?>" target="<?php echo $img_target; ?>"><img alt="<?php echo $curr_siteinfo->site_name;?>" src="<?php echo $single_img_src?>"  <?php echo $str_img_width.$str_img_height; ?> /></a>	
		<?php
			}else{
				if (strstr($img_src,"sitelogo.png")) {
				unset($img_src);
			}else{
			?>
			
           <img src="<?php echo $single_img_src?>" alt="<?php echo $curr_siteinfo->site_name; ?>"  <?php echo $str_img_width.$str_img_height; ?> />
		<?php 
			}
			}
        }else{

				if(SessionHolder::get('user')){
					echo '<div>&nbsp;</div>';
				}

        }
	}else{
		if(file_exists($img_src)){
			if (strstr($img_src,"sitelogo.png")) {
				unset($img_src);
			}else{
            ?>
<img src="<?php echo $img_src; ?>" alt="<?php echo $img_desc; ?>"<?php echo $str_img_width.$str_img_height; ?> />
            <?php
			}
            }
	}
}
?>
<?php
if (SessionHolder::get('page/status', 'view') == 'edit') {
?>
&nbsp;&nbsp;
<?php } ?>
</div>
<?php
if ($showtitle) {
	echo '<div class="list_bot"></div><div class="blankbar"></div>';
}
?>