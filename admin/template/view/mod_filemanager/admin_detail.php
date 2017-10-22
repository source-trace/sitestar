<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
<!--
body {background-color:#FFF;}
* {margin:0;padding:0}
ul,li {list-style:none}
a {color:#0089D1;font-family:"arial";font-size:12px;text-decoration:none;}
.clearfix {clear:both;}
.album {margin:0;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif;margin-bottom:10px;}
.tagMenu {height:28px;line-height:28px;background:#efefef;position:relative;border-bottom:1px solid #999;}
.tagMenu h2 {font-size:12px;padding-left:10px;}
.tagMenu h2 a {color:#000;}
.tagMenu ul {position:absolute;left:100px;bottom:-1px;height:26px;}
ul.menu li {float:left;margin-bottom:1px;line-height:16px;height:14px;margin:5px 0 0 -1px;text-align:center;padding:0 12px;cursor:pointer;width:100px;}
ul.menu li.current {border:1px solid #999;border-bottom:none;background:#fff;height:25px;line-height:26px;margin:0;}
.bgimage {background:url(template/images/pic.gif) no-repeat top left;padding-left:18px;display:inline-block;height:16px;line-height:16px;overflow:hidden;}
.bgflash {background:url(template/images/flash.gif) no-repeat top left;padding-left:18px;display:inline-block;height:16px;line-height:16px;overflow:hidden;}
.bgmedia {background:url(template/images/media.gif) no-repeat top left;padding-left:18px;display:inline-block;height:16px;line-height:16px;overflow:hidden;}
.bgfolder {background:url(template/images/folder.gif) no-repeat top left;padding-left:18px;display:inline-block;height:16px;line-height:16px;overflow:hidden;}
ul.menu li.current span {margin-top:5px;*margin-top:7px;}
.tabmain .local_nav {display:inline-block;padding:10px 0 0 10px;}
.preview {margin-left:7%;margin-top:15px;width:600px;}
.preview a.prev, .preview a.next {background: url(template/images/arrow_left.gif) no-repeat scroll left 40px transparent;display:block;float:left;height:100px;text-decoration:none;width:24px;}
.preview a.next {background: url(template/images/arrow_right.gif) no-repeat scroll left 40px transparent;margin-left:10px;}
.preview ul li {float:left;}
.preview ul li p {cursor:pointer;margin-left:10px;}
.preview ul li p, .preview ul li img {border:1px solid #DDD;width:100px;height:100px;overflow:hidden;}
.preview ul li img {border:0;}
#mycarousel {float:left;height:102px;}
.curimage {margin:0 auto;text-align:center;margin-top:15px;width:98%;}
.editblock span input {width:98px;border:1px solid #DDD;}

/*jqzoom*/
#spec-n1, .editblock {margin:0 auto;width:300px;}
#spec-n1 img {width:300px;}
.editblock {margin-top:10px;}
.jqzoom{position:relative;padding:0;}
.zoomdiv{z-index:100;position:absolute;top:0;left:0;width:120px;height:300px;background-color:#FFF;border:1px solid #e4e4e4;display:none;text-align:center;overflow:hidden;}
.bigimg{width:1000px;height:1000px;}
.jqZoomPup{z-index:10;visibility:hidden;position:absolute;top:0px;left:0px;width:50px;height:50px;border:1px solid #aaa;background:#FEDE4F 50% top no-repeat;opacity:0.5;-moz-opacity:0.5;-khtml-opacity:0.5;filter:alpha(Opacity=50);cursor:move;}
-->
</style>
<?php
$post_p = trim(ParamHolder::get('_p'));
if (!isset($post_p) || empty($post_p)) {
	ob_end_clean();
	header('Location: '.Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'image')));
	exit;
}
$param = urldecode($post_p);
$ftype = substr($param, 0, strpos($param, '|'));
$picpath = substr($param, strpos($param, '|')+1);
$curpic = basename($picpath);
$curdir = str_replace("/{$curpic}", '', $picpath);
switch ($ftype) {
	case 'pic':
		$type = 'image';
		$title = __('Upload Picture(s)');
		$search = '../upload/image/';
		break;
	case 'flash':
		$type = 'flash';
		$title = __('Upload Flash');
		$search = '../upload/flash/';
		break;
	case 'media':
		$type = 'media';
		$title = __('Upload Media');
		$search = '../upload/media/';
		break;
}
// 自动生成路径
$show = true;
if (str_ireplace($search, '', "{$curdir}/")) {
	$navArr = explode('/', str_ireplace($search, '', $curdir));
	$ln = count($navArr);
	$navstr = $tempdir = '';
	for($i=0; $i<$ln; $i++) {
		$filter = ($i < $ln - 1) ? ' &gt; ' : '';
		$tempdir .= $navArr[$i].'/';
		$navstr .= '<a href="#" onclick="goPrev(\'dir|'.$search.substr($tempdir,0,-1).'\',\''.$type.'\')">'.$navArr[$i].'</a>'.$filter;
	}
} else {
	$show = false;
}
?>

<div class="album">
  	<div class="tagMenu">
      <h2><?php echo $title;?></h2>
      <ul class="menu">
          <li <?php if($ftype=='pic'){echo 'class="current"';}?>><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'image')); ?>"><span class="bgimage">图片</span></a></li>
    	  <li <?php if($ftype=='flash'){echo 'class="current"';}?>><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'flash')); ?>"><span class="bgflash">Flash</span></a></li>
          <li <?php if($ftype=='media'){echo 'class="current"';}?>><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'media')); ?>"><span class="bgmedia">多媒体</span></a></li>
          <li><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'file')); ?>"><span class="bgfolder">文件</span></a></li>
       </ul>
	</div>
	<div class="tabmain"><form name="picshow" id="picshow" method="post" action="">
			<input type="hidden" name="_p" value="" /></form>
	   <span class="local_nav"><?php _e('Current Position');?>：<a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>$type));?>">root</a><?php if($show) echo " &gt; ".$navstr;?></span>
<?php if ($type == 'image') {?>
		<div class="preview">
		    <a class="prev" href="#" id="spec-left">&nbsp;</a>
		    <div id="mycarousel"><ul>
<?php
if (file_exists($curdir)) {
	//$tab = $k = '0';
	$dh = dir($curdir);
	while (false !== ($pic = $dh->read())) {
	   if (in_array($pic, array(".", ".."))) continue;
	   if (is_dir("$curdir/$pic")) continue;
	   else {
	   	  $thumb = "$curdir/$pic";
	   	  if (preg_match("/^WIN/i", PHP_OS)) {
	   	  	  if (preg_match("/[\x80-\xff]./", $thumb)) $thumb = iconv('GBK', 'UTF-8//IGNORE', $thumb);
	   	  	  if (preg_match("/[\x80-\xff]./", $pic)) $pic = iconv('GBK', 'UTF-8//IGNORE', $pic);
	   	  }
	   	  //if ($pic == $curpic) $tab = $k;
	   	  echo '<li><p><img name="picautozoom" src="'.$thumb.'" title="'.$pic.'" /></p></li>';
	   	  //$k++;
	   }
	}
} else {
	echo '<li>&nbsp;</li>';
}
?>
		    </ul></div>
		    <a class="next" href="#" id="spec-right">&nbsp;</a>
		    <div class="clearfix"></div>
		</div>
		<div class="curimage">
			<div id="spec-n1" class="jqzoom">
				<img name="picautozoom" src="<?php echo $picpath;?>" jqimg="<?php echo $picpath;?>" alt="" />
			</div>
			<div class="clearfix"></div>
			<div class="editblock">
				<span class="pic|<?php echo urlencode($picpath);?>"><?php echo basename($picpath);?></span><!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">删除</a-->
			</div>
		</div>
<?php } elseif ($type == 'flash') {?>
		<div class="curimage">
			<div id="spec-n1">
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="400" height="300">   
				    <param name="movie" value="<?php echo $picpath;?>" />   
				    <param name="quality" value="high" />   
				    <param name="allowFullScreen" value="true" />   
				    <embed src="<?php echo $picpath;?>" allowfullscreen="true" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="400" height="300"></embed>   
				</object>
			</div>
			<div class="clearfix"></div>
			<div class="editblock">
				<span class="flash|<?php echo urlencode($picpath);?>"><?php echo basename($picpath);?></span><!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">删除</a-->
			</div>
		</div>
<?php } elseif ($type == 'media') {?>
		<div class="curimage">
			<div id="spec-n1">
				<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="400" height="300"> 
					<param name="movie" value="../images/flvplayer.swf" /> 
					<param name="allowfullscreen" value="true" /> 
					<param name="allowscriptaccess" value="always" /> 
					<param name="flashvars" value="file=<?php echo $picpath;?>&image=images/watermark.png&autostart=true" /> 
					<embed type="application/x-shockwave-flash" id="player2" name="player2" src="../images/flvplayer.swf" width="400" height="300" allowscriptaccess="always" allowfullscreen="true" flashvars="file=<?php echo $picpath;?>&image=images/watermark.png&autostart=true" /> 
				</object>
			</div>
			<div class="clearfix"></div>
			<div class="editblock">
				<span class="flash|<?php echo urlencode($picpath);?>"><?php echo basename($picpath);?></span><!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">删除</a-->
			</div>
		</div>
<?php }?>
	</div>
</div>

<script language="javascript">
function goPrev(path, ftype) {
	$('#picshow input').val(path);
	document.getElementById('picshow').action = 'index.php?_m=mod_filemanager&_a=admin_dashboard&_f='+ftype;
	document.getElementById('picshow').submit();
}
</script>
<?php if ($type == 'image') {?>
<script language="javascript">
$(function(){
	var reg = /<[^>].*?>/;
	var filter = /^[^/\\:\*\?,\"<>\|]+$/;
	// 放大镜
	$('.jqzoom').jqueryzoom({
		xzoom:120,
		yzoom:300,
		offset:1,
		position:'left'
	});
	$('#mycarousel p').hover(function(){
		$(this).css('border','1px solid #ff6600');
	},function(){
		$(this).css('border','1px solid #ddd');
	});
	// 
	$('#mycarousel p').click(function(){
		var newpic = $(this).find('img').attr('src');
		var newtitle = $(this).find('img').attr('title');
		$('#spec-n1 img').attr({'src': newpic,'jqimg': newpic});
		$('.editblock span').html(newtitle);
	});
	
	// 图片幻灯
	$('#mycarousel').jCarouselLite({
		btnNext: '.next',
		btnPrev: '.prev',
		speed: 500,
		visible: 4,
		circular: false
		//start: 2
	});
	// 消除虚线框
	$('a').bind('focus', function(){
		if(this.blur) this.blur();
	});
});
</script>
<script type="text/javascript" src="picAutoZoom.js"></script>
<script type="text/javascript" src="jqzoom.js"></script>
<script type="text/javascript" src="jcarousellite.min.js"></script>
<?php
Html::includeJs('/picAutoZoom.js');
Html::includeJs('/jqzoom.js');
Html::includeJs('/jcarousellite.js');
} else {
Html::includeJs('/jwplayer.js');
}
?>