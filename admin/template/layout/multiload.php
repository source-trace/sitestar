<?php
$check_admin = 0;
if (!defined('IN_CONTEXT')) {
	die('access violation error!');
} else { $check_admin = 1; }
$sysroot = str_replace("\\", "/", ROOT);
$rootdir = mb_substr($sysroot, strrpos($sysroot, '/'));

// get uploadify directory
$dirlist = array();
$ftype = trim(ParamHolder::get('_f'));
if (empty($ftype)) $ftype = 'image';
switch($ftype) {
	case 'flash':
		$fext = '*.swf';
	    $fdesc = 'SWF(*.swf)';
		$base_upload_dir = '../upload/flash/';
		break;
	case 'media':
		$fext = '*.flv;*.mp3;*.mp4;*.3gp';
	    $fdesc = 'Media(*.flv, *.mp3, *.mp4, *.3gp)';
		$base_upload_dir = '../upload/media/';
		break;
	case 'file':
		$fext = '*.zip;*.rar;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.wps;*.pdf';
	    $fdesc = 'File(*.zip, *.rar, *.doc, *.docx, *.xls, *.xlsx, *.ppt, *.pptx, *.wps, *.pdf)';
		$base_upload_dir = '../upload/file/';
		break;
	case 'image':
		default:
		$fext = '*.gif;*.jpg;*.png;*.bmp';
	    $fdesc = 'Picture(*.gif, *.jpg, *.png, *.bmp)';
		$base_upload_dir = '../upload/image/';
		break;
}
$dirs = array(substr($base_upload_dir,3));// 初始化数组
getPathList($base_upload_dir, $dirs);

function getPathList($base_upload_dir, &$dirlist) {
	$handle = dir($base_upload_dir);
	while(($path = $handle->read()) !== false) {
		if (!in_array($path, array(".", "..", ".svn")) && is_dir($base_upload_dir.$path))	{
			$dirlist[] = substr($base_upload_dir,3).$path.'/';
			getPathList($base_upload_dir.$path.'/', $dirlist);
		} else continue;
	}
	$handle->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
<title>网站系统管理</title>
<style type="text/css">
<!--
* {font-size:12px;}
a {color:#FFF;text-decoration:none;}
#upMain {margin:0;padding:10px;/*height:430px;width:710px;*/}
#fileQueue {background-color:#FFF;border:1px solid #99BBE8;height:320px;_height:300px;width:100%;overflow-y:auto;}
.upBtn {height:30px;line-height:30px;margin-top:10px;text-align:center;}
.upFolder {margin:0 auto;padding:3px 0;height:25px;}
.upSubmit, .upCancel {background-color:#525252;display:inline-block;padding:0 10px;margin-left:10px;vertical-align:top;height:30px;line-height:30px;font-size:14px;}
.upSubmit {color:#FFFF00;}
.upCancel {color:#FFF;}
-->
</style>
<link href="<?php echo P_SCP;?>/multiupload/uploadify.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo P_SCP;?>/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo P_SCP;?>/multiupload/swfobject.js"></script>
<script type="text/javascript" src="<?php echo P_SCP;?>/multiupload/jquery.uploadify.js"></script>
<script type="text/javascript">
function _eval_json(s_json) {
    if (s_json.trim().length == 0) return false;
    return eval("(" + s_json + ")");
}
String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,"");
}
	
$(document).ready(function() {
	$("#uploadify").uploadify({
		'uploader'       : '../script/multiupload/uploadify.swf',
		'script'         : '../script/multiupload/uploadify.php',
		'checkScript'    : '../script/multiupload/check.php',//检测上传文件是否已存在
		'cancelImg'      : '../script/multiupload/cancel.png',
		'folder'         : "../upload/<?php echo $ftype.'|'.$check_admin;?>",
		'queueID'        : 'fileQueue',
		'auto'           : false,
		'multi'          : true,
		'alstr'          : "<?php _e('The queue is full.  The max size is ');?>",
		'cnfstr'         : "<?php _e('Sure to replace the file ');?>",
		'sizeLimit'      : "<?php echo intval(ini_get('upload_max_filesize')) * 1024 * 1024;?>",
		'fileExt'        : "<?php echo $fext;?>",
		'fileDesc'       : "<?php echo $fdesc;?>",//Picture(*.gif, *.jpg, *.png, *.bmp)
		//'buttonText'     : 'Select File(s)',
		'buttonImg'      : '../script/multiupload/selectfile.png',
		'onAllComplete'  : function(event, queueID, fileObj, response, data) {
			if (confirm("<?php _e('File uploaded successfully! Click \'OK\' to continue uploading,Click \'Cancel\' to stop uploading');?>")) return true;
			else {
				//window.parent.tb_remove();
				//window.parent.location.reload();
				window.location.href = "index.php?_m=mod_filemanager&_a=admin_dashboard&activepath="+encodeURI("<?php echo $rootdir;?>/"+$('#gtcurdir').val());
			}
		},
		//'onCancel'     : function(event, queueID, fileObj, data) {},
		'onError'        : function(event, queueID, fileObj, errorObj) {
			if (errorObj.type === "File Size") {
				var itemp = Math.round((errorObj.info/1024)*100)/100;
				var reit = itemp+'KB';
				if (itemp >= 1000) reit = Math.round((itemp/1024)*100)/100+'M';
				alert(fileObj.name+' '+errorObj.type+' Limit: '+reit);
			}
		}
	});
});

function addSort(obj) {
	$(obj).parent().find('span').css('display','inline-block');
	$(obj).css('display','none');
	$(obj).parent().find('span > input:first').focus();
}

function hidSort(obj) {
	if (!$(obj).val().trim().length) {
		$(obj).parent().css('display','none');
		$(obj).parent().find('input:first').val('');
		$(obj).parent().parent().find('a').css('display','inline-block');
	}
}

function newDir(obj) {
	var pth = $(obj).prev().attr('value');
	var basepth = $('#gtcurdir option:selected').text();
	if (pth.replace(/^\s+|\s+$/g,'').length == 0) {
		alert("<?php _e('Please input characters!');?>");
		$(obj).prev().focus();
		return false;
	} else {
		var url = "index.php?_m=mod_filemanager&_a=make_dir&_r=_ajax";
	    // Reform query string
	    var params = {basedir: basepth,newdir: pth};
	    for (key in params) {
	       url += "&" + key + "=" + params[key];
	    }

	    $.ajax({
	        type: "GET",
	        url: url,
	        success: function(response) {
	        	var o_result = _eval_json(response);

			    if (!o_result) {
			        alert('failed');
	    			return false;
			    }
			    
			    if (o_result.result == "ERROR") {
			    	switch (o_result.errmsg) {
			    		case "-1":
			    			alert("<?php _e('The folder already exists!');?>");
			    		    $(obj).prev().focus();
			    			break;
			    		case "-2":
			    			alert("<?php _e('New Folder failed!');?>");
			    			break;
			    	}
			        return false;
			    } else if (o_result.result == "OK") {
			       $(obj).prev().val('');
			       $(obj).parent().css('display','none');
			       $(obj).parent().parent().find('a').css('display','inline-block');
			       $('<option value="'+basepth+pth+'/" selected="true">'+basepth+pth+'/</option>').appendTo('#gtcurdir');
			       $('#uploadify').uploadifySettings('folder', '../'+basepth+pth+"|<?php echo $check_admin;?>");
			    } else {
			        alert("<?php _e('Request failed!');?>");
			        return false;
			    }
	        },
	        error: function(response) {
	        	alert("<?php _e('Request failed!');?>");
	    		return false;
	        }
	    });
	}
}

function uploadifyset(val) {
	var chk = "|<?php echo $check_admin;?>";
	var nval = '../'+val.substr(0,val.length-1);
	$('#uploadify').uploadifySettings('folder', nval+chk);
}
</script>
</head>

<body style="background-color:#ECEEF4;">
<?php include_once($_content_); ?>
</body>
</html>