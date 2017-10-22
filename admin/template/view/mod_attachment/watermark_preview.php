<?php
@header('Expires: Thu, 01 Jan 1970 00:00:01 GMT');  
@header('Cache-Control: no-cache, must-revalidate, max-age=0');  
@header('Pragma: no-cache');

if (!defined('IN_CONTEXT')) die('access violation error!');

$type =& ParamHolder::get('mktype', '0');
$wt =& ParamHolder::get('wt', '1');
$msg =& ParamHolder::get('msg');

if( $type ) {
	//if( file_exists('images/sitestar.'.$type.'.jpg') ) {
		//$img = 'images/sitestar.'.$type.'.jpg';
	//} else {
		$img = watemark('images/sitestar.jpg', $type, $wt, $msg );
	//}
	echo '<div style="margin:0 auto;text-align:center;"><img src="'.$img.'?'.time().'" border="0" /></div>';
} else {
	_e('Watermark feature is not enabled');
}

function watemark($sfile, $watermarkstatus, $watermarktype, $msg)
{
	list($img_w, $img_h) = @getimagesize($sfile);
	if($watermarktype == 1) {
		$ext = $watermarkstatus;
		$watermarkpng = $msg;
		$watermarkinfo	= @getimagesize($watermarkpng);
		$watermark_logo	= @imagecreatefrompng($watermarkpng);
		if(!$watermark_logo) {
			return;
		}
		list($logo_w, $logo_h) = $watermarkinfo;
	} else {
		$arr = explode(',', $msg);
		$ext = 't'.$watermarkstatus;
		$watermarktext['size'] = $arr[1];
		$watermarktext['angle'] = $arr[2];
		$watermarktext['shadowx'] = $arr[4];
		$watermarktext['shadowy'] = $arr[5];
		$watermarktext['fontpath'] = '../data/font/stxingka.ttf';
		$watermarktextcvt = pack("H*", bin2hex($arr[0]));
		$box = imagettfbbox($watermarktext['size'], $watermarktext['angle'], $watermarktext['fontpath'], $watermarktextcvt);
		$logo_h = max($box[1], $box[3]) - min($box[5], $box[7]);
		$logo_w = max($box[2], $box[4]) - min($box[0], $box[6]);
		$ax = min($box[0], $box[6]) * -1;
		$ay = min($box[5], $box[7]) * -1;
	}

	$wmwidth = $img_w - $logo_w;
	$wmheight = $img_h - $logo_h;

	switch($watermarkstatus) {
		case 1: // 左上角
			$x = +5;
			$y = +5;
			break;
		case 2: // 顶端居中
			$x = ($img_w - $logo_w) / 2;
			$y = +5;
			break;
		case 3: // 右上角
			$x = $img_w - $logo_w - 5;
			$y = +5;
			break;
		case 4: // 居中偏左
			$x = +5;
			$y = ($img_h - $logo_h) / 2;
			break;
		case 5: // 居中
			$x = ($img_w - $logo_w) / 2;
			$y = ($img_h - $logo_h) / 2;
			break;
		case 6: // 居中偏右
			$x = $img_w - $logo_w;
			$y = ($img_h - $logo_h) / 2;
			break;
		case 7: // 左下角
			$x = +5;
			$y = $img_h - $logo_h - 5;
			break;
		case 8: // 底端居中
			$x = ($img_w - $logo_w) / 2;
			$y = $img_h - $logo_h - 5;
			break;
		case 9: // 右下角
			$x = $img_w - $logo_w - 5;
			$y = $img_h - $logo_h - 5;
			break;
	}

	$dst_photo = imagecreatetruecolor($img_w, $img_h);
	$target_photo = @imagecreatefromjpeg($sfile);
	@imagecopy($dst_photo, $target_photo, 0, 0, 0, 0, $img_w, $img_h);

	if($watermarktype == 1) {
		@imagecopy($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h);
	} else {
		$watermarktext['color'] = preg_replace('/#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/e', 
				                          "hexdec('\\1').','.hexdec('\\2').','.hexdec('\\3')", '#'.$arr[3]);
		$watermarktext['shadowcolor'] = preg_replace('/#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/e', 
				                          "hexdec('\\1').','.hexdec('\\2').','.hexdec('\\3')", '#'.$arr[6]);
				                          
		$shadowcolorrgb = explode(',', $watermarktext['shadowcolor']);
		$shadowcolor = imagecolorallocate($dst_photo, $shadowcolorrgb[0], $shadowcolorrgb[1], $shadowcolorrgb[2]);
		imagettftext($dst_photo, $watermarktext['size'], $watermarktext['angle'], $x + $ax + $watermarktext['shadowx'], 
		             $y + $ay + $watermarktext['shadowy'], $shadowcolor, $watermarktext['fontpath'], $watermarktextcvt);
		$colorrgb = explode(',', $watermarktext['color']);
		$color = imagecolorallocate($dst_photo, $colorrgb[0], $colorrgb[1], $colorrgb[2]);
		imagettftext($dst_photo, $watermarktext['size'], $watermarktext['angle'], $x + $ax, $y + $ay, $color, 
		             $watermarktext['fontpath'], $watermarktextcvt);
	}
	clearstatcache();
	
	$sfile = Rname($sfile, "{$ext}.");
	imagejpeg($dst_photo, $sfile, 100);
	
	return $sfile;
}

function Rname( $path, $tag ) {
	$path_parts = array();
	$path_parts = pathinfo( $path );
	$ext = strtolower( $path_parts["extension"] );
	return str_replace( $ext, $tag.$ext, $path );
}

?>