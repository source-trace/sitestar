<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<style type="text/css">
<!--
body {background-color:#FFF;}
* {margin:0;padding:0}
ul,li {list-style:none}
.clearfix {clear:both;overflow:hidden;}
a {color:#0089D1;font-family:"arial";font-size:12px;text-decoration:none;}
.album {margin:0;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif;}
.tagMenu {height:28px;line-height:28px;background:#efefef;position:relative;border-bottom:1px solid #999;}
.tagMenu h2 {font-size:12px;padding-left:15px;}
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
.tabmain ul.albumlist {clear:both;}
.tabmain ul.albumlist li {float:left;margin-left:35px;*margin-left:30px;margin-top:15px;text-align:center;}
.tabmain ul.albumlist li img {border:1px solid #DDD;width:100px;height:100px;overflow:hidden;}
.tabmain ul.albumlist li img {border:0;}
#albumlistid{border:1px solid #DDD;width:100px;height:100px;overflow:hidden;}
.tabmain ul.albumlist li span {display:inline-block;padding-top:5px;overflow:hidden;width:100px;height:18px;}
.albumlist span input {width:98px;border:1px solid #DDD;}
.delblock {margin-top:40px;z-index:100;position:absolute;background-color:#EF9024;}
.delblock a {color:#FFF;}
/* Page */
.pager {margin-top:15px;text-align:center;}
.page_square {margin:0 3px;padding:3px 5px;display:inline-block;}
.phover {color:#FFF;background-color:#0468B4;}

.notice {color:#FF0000;margin-top:10px;margin-left:10px;}
.wp-fileclose-div{ position:absolute;display:inline-block; width:13px; height:16px;z-index:100; margin-left:88px;}
.wp-fileclose-div a.close{ display:inline-block; width:13px; height:16px;background:url(template/images/wp-tools_icon.png) no-repeat -68px -17px;}
.wp-fileclose-div a.close:hover{ background:url(template/images/wp-tools_icon.png) no-repeat -85px -17px;}

-->
</style>
<?php
// Tabs
$_f = trim(ParamHolder::get('_f'));
switch($_f) {
	case 'flash': // Flash
		$flvcur = true;
		$medcur = $filcur = $imgcur = false;
		$basepath = 'upload/flash';
		$icon = 'template/images/flash.png';
		$title = __('Upload Flash');
		break;
	case 'media': // 多媒体
		$medcur = true;
		$flvcur = $filcur = $imgcur = false;
		$basepath = 'upload/media';
		$icon = 'template/images/media.png';
		$title = __('Upload Media');
		break;
	case 'file': // 文件
		$filcur = true;
		$flvcur = $medcur = $imgcur = false;
		$basepath = 'upload/file';
		$icon = 'template/images/folder.png';
		$title = __('Upload File(s)');
		break;
	case 'image': // 图片
	default:
		$imgcur = true;
		$flvcur = $medcur = $filcur = false;
		$basepath = 'upload/image';
		$icon = 'template/images/album.png';
		$title = __('Upload Picture(s)');
		break;
}

// 路径导航
$navshow = false;
$path = '';
$post_p = trim(ParamHolder::get('_p'));
if (!empty($post_p)) {
	$navshow = true;
	$path = trim(urldecode($post_p));
	// 自动生成路径用
	$search = $basepath;
	$basepath = substr($path, strpos($path, '|')+4); // 消除"../"
}
// 重置ROOT链接
if (empty($_f)) $_f = 'image';

// 按修改日期降序
$filelist = array();
if (file_exists('../'.$basepath)) {
	$sum = 0;
	$dh = dir('../'.$basepath);
	$REQUEST_URI = $_SERVER['REQUEST_URI'];
	if(empty($REQUEST_URI)){
		$ret_uri='/';
	}else{
		$num=strpos($REQUEST_URI,"admin/index.php?");
		$ret_uri=substr($REQUEST_URI,0,$num);
	}
	while (false !== ($album = $dh->read())) {
		if (in_array($album, array('.', '..', '.svn'))) continue;
		$thumb = "../$basepath/$album";
		// 转码
		if (preg_match("/^WIN/i", PHP_OS)) {
			if (preg_match("/[\x80-\xff]./", $thumb)) $thumb = iconv('GBK', 'UTF-8//IGNORE', $thumb);
			if (preg_match("/[\x80-\xff]./", $album)) $album = iconv('GBK', 'UTF-8//IGNORE', $album);
		}
		// 构造数组
		$fortitle = $thumb;
		if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $thumb)) {
   	       $fortitle = iconv('UTF-8', 'GBK//IGNORE', $thumb);
		}
	   	$ftime = filemtime($fortitle);
		$ftime = date('Y-m-d H:i', $ftime);
		if (is_dir($thumb)) {
			$filelist[] = array('type' => 'dir',
						  'fpath' => $thumb,
						  'fname' => $album,
						  'ftime' => $ftime);
   	   	} else {
   	   		// 文件类型
		    $path_parts = pathinfo($fortitle);
		    $ftype = strtoupper($path_parts['extension']).' '.__('File');
		    // 文件大小
			$fsize = filesize($fortitle);
			$kb = round($fsize/1024, 1);
			if ($kb > 1000) {
				$fsize = round($kb/1024, 1).' M';
			} else {
				$fsize = $kb.' KB';
			}
			//$thumb=$ret_uri.$thumb;
			if(substr($thumb,0,3)=='../'){
				$temp=substr($thumb,3);
			}
   	   		$filelist[] = array('type' => 'file',
						  'fpath' => $thumb,
						  'fname' => $album,
				          'ftype' => $ftype,
				          'fsize' => $fsize,
				          'data' => $ret_uri.$temp,
						  'ftime' => $ftime);
   	   	}
		$sum++;
	}
}
usort($filelist, "sort_query");

// 分页 >>
$pagenum = 5;// 1 | 2 | 3 | 4 ...| 10
$post_page = trim(ParamHolder::get('page'));
$page = (!isset($post_page) || (intval($post_page) <= 0)) ? 1 : $post_page;
$pstart = ($page - 1)*PAGE_SIZE;
$pend = $page*PAGE_SIZE;
$pagesum = ceil($sum/PAGE_SIZE);

// usort用
function sort_query($arr1, $arr2) {
    if ($arr1['ftime'] == $arr2['ftime']) return 0;
    return ($arr1['ftime'] < $arr2['ftime'] ) ? 1 : -1;
}

// 详见SVN
function pagerLinks($page, $pagesum, $pagenum) {
    $prev_page = ($page == 1) ? 1: $page - 1;
    $next_page = ($page == $pagesum) ? $pagesum : $page + 1;
    
    // set pages
	$page_list='';
    $page_list .= '<a class="page_square" href="#" onclick="byPage(1)">'.__('First').'</a>';
	$page_list .= '<a class="page_square" href="#" onclick="byPage('.$next_page.')">'.__('Next').'</a>';
	
	$startPage = ($page - ceil($pagenum/2) + 1 > 0) ? $page - ceil($pagenum/2) + 1 : 1;
	$endPage = $startPage + $pagenum;
	if ($endPage > $pagesum + 1) $endPage = $pagesum + 1;
	if ($startPage + 1 >= $pagenum) {
		$page_list .= '&nbsp;<a href="#" class="page_square" onclick="byPage(1)">1</a>...&nbsp;';
	}
   for($i=$startPage; $i<$endPage; $i++) {			  
		if ($i == $page) {
			$page_list .= '<a href="#" class="page_square phover" onclick="byPage('.$i.')">'.$i.'</a>';
		} else {
			$page_list .= '<a href="#" class="page_square" onclick="byPage('.$i.')">'.$i.'</a>';
		}
    }
    if ($endPage < $pagesum + 1) {
		$page_list .= '...';
        $page_list .= '<a href="#" class="page_square" onclick="byPage('.$pagesum.')">'.$pagesum.'</a>';
	}
    $page_list .= '<a class="page_square" href="#" onclick="byPage('.$prev_page.')">'.__('Previous').'</a>';
    $page_list .= '<a class="page_square" href="#" onclick="byPage('.$pagesum.')">'.__('Last').'</a>';
	
	return $page_list;
}
?>

<div class="album">
  	<div class="tagMenu">
      <h2><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_list', array('_f'=>$_f)); ?>"><?php echo $title;?></a></h2>
      <ul class="menu">
          <li <?php if($imgcur){?>class="current"<?php }?>><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'image')); ?>"><span class="bgimage">图片</span></a></li>
          <li <?php if($flvcur){?>class="current"<?php }?>><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'flash')); ?>"><span class="bgflash">Flash</span></a></li>
          <li <?php if($medcur){?>class="current"<?php }?>><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'media')); ?>"><span class="bgmedia">多媒体</span></a></li>
          <li <?php if($filcur){?>class="current"<?php }?>><a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>'file')); ?>"><span class="bgfolder">文件</span></a></li>
       </ul>
	</div>
	<div class="tabmain"><form name="pagefrm" id="pagefrm" method="post" action="">
	<input type="hidden" name="_p" value="<?php echo urlencode($path);?>" /><input type="hidden" name="page" value="" /></form>
    <div class="notice"><?php _e('Notice: ');?>1、<?php _e('Double-click the icon open folder(or browse the file)');?><br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、<?php _e('Double-click the filename rename files');?><br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、<?php _e('Move the mouse to file-icon deleting files');?></div>
<?php
if ($navshow) {
	$navArr = explode('/', str_ireplace("{$search}/", '', $basepath));
	
	$ln = count($navArr);
	$navstr = $curnav = $tempdir = '';
	for($i=0; $i<$ln; $i++) {
		if ($i == $ln-1) { //当前目录
			$curnav = $navArr[$i];
		} else {
			$tempdir .= $navArr[$i].'/';
			$navstr .= '<a href="#" onclick="goPrev(\'dir|'.$search.'/'.substr($tempdir,0,-1).'\')">'.$navArr[$i].'</a> &gt; ';
		}
	}  
?>
	   <span class="local_nav"><?php _e('Current Position');?> <a href="<?php echo Html::uriquery('mod_filemanager', 'admin_dashboard', array('_f'=>$_f)); ?>">root</a> &gt; <?php echo $navstr.$curnav;?></span><?php }?>
	   <form name="albumfrm" id="albumfrm" method="post" action=""><input type="hidden" name="_p" value="" /></form>
       <ul class="albumlist">
<?php
if ($sum) {
	for($i=0; $i<$sum; $i++) {
		// 分页开始
		if (($i >= $pstart) && ($i < $pend)) {
			if ($filelist[$i]['type'] == 'dir') {
				echo '<li><div id="albumlistid" class="dir|'.urlencode($filelist[$i]['fpath']).'"><img name="picautozoom" src="'.$icon.'" /></div><span class="dir">'.$filelist[$i]['fname'].'</span></li>';
			} else {
				if ($imgcur) {$picon = $filelist[$i]['fpath'];$type = "pic";}
				if ($flvcur) {$picon = 'template/images/flayer.png';$type = "flash";}
		   	    if ($filcur) {
					$f_ext = substr($filelist[$i]['fname'],strrpos($filelist[$i]['fname'],'.')+1,strlen($filelist[$i]['fname']));
					if($f_ext=='doc' || $f_ext=='docx'){
						$picon = 'template/images/icon_word.png';
						$filelist[$i]['fname'] = str_replace('docx','doc',$filelist[$i]['fname']);
					}elseif($f_ext=='xls' || $f_ext=='xlsx'){
						$picon = 'template/images/icon_excel.png';
					}elseif($f_ext=='ppt' || $f_ext=='pptx'){
						$picon = 'template/images/icon_ppt.png';
						$filelist[$i]['fname'] = str_replace('pptx','ppt',$filelist[$i]['fname']);
					}elseif($f_ext=='wps'){
						$picon = 'template/images/icon_wps.png';
					}elseif($f_ext=='pdf'){
						$picon = 'template/images/icon_pdf.png';
					}elseif($f_ext=='zip' || $f_ext=='rar'){
						$picon = 'template/images/blank.png';
					}
					
					$type = "file";
				}
		   	    if ($medcur) {$picon = 'template/images/player.png';$type = "media";}
		   	    echo "<li><div id=albumlistid class=\"{$type}|".urlencode($filelist[$i]['fpath'])."\" title=\"".__('Type')." ".$filelist[$i]['ftype']."&#13;".__('Size')." ".$filelist[$i]['fsize']."&#13;".__('Date Modified')." ".$filelist[$i]['ftime']."\"><img name=\"picautozoom\" src=\"{$picon}\" data=".$filelist[$i]['data']."></div><span class=\"file\">".$filelist[$i]['fname']."</span></li>";
			}
		} else continue;
	}
} else {
	echo '<li>&nbsp;</li>';
}
?>
	   </ul>
	</div>
	<div class="clearfix"></div>
	<div class="pager"><?php if($pagesum > 1) echo pagerLinks($page, $pagesum, $pagenum);?>
	</div>
</div>

<script language="javascript">
// Trim
String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,"");
}

function delpic(obj) {
	var param = $(obj).parent().parent().attr('class');
	var tag = param.substr(0, param.indexOf('|'));
	var msg = (tag == 'dir') ? "<?php _e('Are you sure to delete the folder?');?>" : "<?php _e('Are you sure to delete the file?');?>";
	if (confirm(msg)) {
		$.ajax({
	        type: "GET",
	        url: "index.php?_m=mod_filemanager&_a=file_delete&_r=_ajax&_p="+param,
	        success: function(response) {
	        	var o_result = _eval_json(response);

			    if (!o_result) {
			        return onfailed(response);
			    }
			    
			    if (o_result.result == "OK") {
			        reloadPage();
			    } else if (o_result.result == "ERROR") {
			    	switch (o_result.errmsg) {
						case "-1":
							alert("<?php _e('Delete failed!');?>");
							break;
						case "-2":
							alert("<?php _e('File does not exist!');?>");
							break;
					}
			        return false;
			    }
	        },
	        error: function(response) {
	        	alert("<?php _e('Delete failed!');?>");
	    		return false;
	        }
	    });
	}
}

function goPrev(path) {
	$('#albumfrm input').val(path.replace(/\|/g, '|../'));
	document.getElementById('albumfrm').submit();
}

function byPage(page) {
	$('#pagefrm input:last').val(page);
	document.getElementById('pagefrm').submit();
}

$(function(){
	var reg = /<[^>].*?>/;
	var filter = /^[^/\\:\*\?,\"<>\|]+$/;
	// 消除虚线框
	$('a').bind('focus', function(){
		if(this.blur) this.blur();
	});
	// 鼠标滑过"删除"
	$('.albumlist div').hover(function(){
		$(this).prepend('<span class="delblock"><a href="javascript:;" onclick="selectPic(this);return false;" role="button">'+"<?php _e('Select');?>"+'</a></span>');
		$(this).prepend('<div class="wp-fileclose-div"><a href="#" onclick="delpic(this)" class="close" title="'+"<?php _e('Delete');?>"+'"></a></div>');
	},function(){
		$('.delblock').remove();
		$('.wp-fileclose-div').remove();
	});
	// 双击跳转
	$('.albumlist div').bind('dblclick', function(){
		var act = '';var jump = false;
		var param = $(this).attr('class');
		var tag = param.substr(0, param.indexOf('|'));		
		if (tag == 'dir') {
			jump = true;
		} else if (tag != 'file') {
			jump = true;
			act = 'index.php?_m=mod_filemanager&_a=admin_detail';
		}
		if (jump) {
			$('#albumfrm input').val(param);
			document.getElementById('albumfrm').action = act;
			document.getElementById('albumfrm').submit();
		}	
	});
	// 重命名
	$('.albumlist span').bind('dblclick', function(){
		var vl = $(this).html();
		if (!reg.test(vl)) {
			var ext = cls = '';
			var pstr = $(this).parent().find('div').attr('class');
			if (pstr.substr(0, pstr.indexOf('|')) != 'dir') {
				ext = vl.substr(vl.lastIndexOf('.'));
				cls = 'style="width:70px"';
				vl = vl.substr(0, vl.lastIndexOf('.'));
			}
			var restr = '<input type="text" name="new_name" '+cls+' value="'+vl+'" />'+ext;
			$(this).empty().append(restr).find('input').select();
		}
	}).bind('focusout', function(){
		var ext = '';
		var _this = $(this);
		var cdir = encodeURI("../<?php echo $basepath;?>/");
		var vl = odstr = _this.html();
		var pstr = _this.parent().find('div').attr('class');
		if (pstr.substr(0, pstr.indexOf('|')) != 'dir') ext = vl.substr(vl.lastIndexOf('.'));
		// 合法性检测
		if (reg.test(vl)) {
			var txt = _this.find('input');
			if (!txt.val().trim().length) {
				alert("<?php _e('Please input characters!');?>");
				window.setTimeout(function(){txt.focus();}, 0);
				return false;
			} else if (!filter.test(txt.val())) {
				alert("<?php _e('Filename cannot contain the following any one of the characters');?>"+' \n/\:,*?"<>|');
				window.setTimeout(function(){txt.select();}, 0);
				return false;
			}
			odstr = txt.val()+ext;
		}
		// Ajax提交
		$.ajax({
	        type: "GET",
	        url: "index.php?_m=mod_filemanager&_a=file_rename&_r=_ajax&_p="+pstr+"&_f="+odstr,
	        success: function(response) {
	        	var o_result = _eval_json(response);

			    if (!o_result) {
			        return onfailed(response);
			    }
			    
			    if (o_result.result == "OK") {
			        // 重置Class属性
			        _this.parent().find('div').attr('class', pstr.substr(0, pstr.indexOf('|')+1)+cdir+encodeURI(odstr));
			        _this.empty().append(odstr);
			    } else if (o_result.result == "ERROR") {
			    	switch (o_result.errmsg) {
						case "-1":
							alert("<?php _e('Request failed!');?>");
							window.setTimeout(function(){txt.select();}, 0);
							break;
						case "-2":
							alert("<?php _e('File does not exist!');?>");
						    window.setTimeout(function(){txt.select();}, 0);
							break;
						case "-3":
							alert("<?php _e('File name already exists!');?>");
							window.setTimeout(function(){txt.select();}, 0);
							break;
						case "-4":
							alert("<?php _e('Rename failed!');?>");
						    window.setTimeout(function(){txt.select();}, 0);
							break;
						case "-5":
							alert("<?php _e('Rename file extension type error!');?>");
						    window.setTimeout(function(){txt.select();}, 0);
							break;
					}
			        return false;
			    }
	        },
	        error: function(response) {
	        	alert("<?php _e('Request failed!');?>");
	        	window.setTimeout(function(){txt.select();}, 0);
	    		return false;
	        }
	    });
	});
});
</script>
<?php Html::includeJs('/picAutoZoom.js');?>

<script type="text/javascript">
var onsuccess = function( json){
	parent._imageChooserDefered.resolve(json);
};
// 选择图片
var selectPic = function(obj){
	var $img = $(obj).closest('div').find('img');
	var data = {
		fname: $img.attr('data')
	};
	onsuccess(data);
}
</script>
</script>