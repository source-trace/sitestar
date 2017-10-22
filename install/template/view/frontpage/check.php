<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SiteStar建站之星安装程序</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery.min.js"></script>
<script>
jQuery(function($){
	$("#js-pre-step").click(function(){
		location.href = "index.php";
	});
	$("#js-recheck").click(function(){
		location.href = "index.php?_m=frontpage&_a=check";
	});
	$("#js-submit").click(function(){
		var tgstr = document.getElementsByTagName('input');
		var nmstr = document.getElementsByName('chkresult[]');
		var num = (nmstr.length) ? nmstr.length : tgstr.length;

		if (num > 0) {
			alert('很抱歉，安装环境检测失败，不能进行下一步安装。');
			return false;
		} else {
			this.href="index.php?_m=frontpage&_a=setting&default_tpl=jixie-110118-a16";
		}
	});
});
</script>
</head>

<body>
    <div id="in">
    	<div id="top"><span>SiteStar建站之星安装程序</span></div>
        <div id="banner"></div>
        <div id="center">
        
        	<div id="left">
            	<ul>
                	<li>欢迎您使用SiteStar</li>
                    <li class="hov">检查系统环境</li>
                    <li>配置系统</li>
                    <li>完成安装</li>
                </ul>
            </div>
            
            <div id="right">
            	<div id="right_bor">
				<table  cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="80%" ><h4>系统环境</h4></td>
                                <td width="20%" class="hr1" >&nbsp;</td>
                            </tr>
                              <tr>
                                <td class="icon1">操作系统</td>
                                <td class="l1"><?php echo PHP_OS;?></td>
                            </tr>
                              <tr>
                                <td class="icon2">PHP版本</span></td>
                                <td class="l2"><?php echo PHP_VERSION;?></td>
                            </tr>
							<tr>
                                <td class="icon2">是否支持MySQL</td>
                                <td class="l2"><?php if(function_exists('mysql_connect')){?><img src="images/r.gif" width="17" height="13" alt="e" /><?php } else {?><input type="hidden" name="chkresult[]" value="1" /><img src="images/e.gif" width="12" height="12" alt="e" /><?php }?></td>
                              </tr>
                              <tr>
                                <td ><h5>目录权限检测</h5></td>
                                <td class="hr2" >&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="icon2">站点根目录写入权限&nbsp;&nbsp;&nbsp;&nbsp;(注：不符合要求将导致系统安装失败)</td>
                                <td class="l2"><?php if (is_writable(ROOT)){?><img src="images/r.gif" width="17" height="13" alt="e" /><?php } else {?><input type="hidden" name="chkresult[]" value="1" /><img src="images/e.gif" width="12" height="12" alt="e" /><?php }?></td>
                              </tr>
                              <tr>
                                <td class="icon1">cache</td>
                                <td class="l1"><?php if(is_writable("../cache")){?><img src="images/r.gif" width="17" height="13" alt="e" /><?php }else{?><input type="hidden" name="chkresult[]" value="4" /><img src="images/e.gif" width="12" height="12" alt="e" /><?php }?></td>
                              </tr>
                              <tr>
                                <td class="icon2">navigation</td>
                                <td class="l2"><?php if(is_writable("../navigation")){?><img src="images/r.gif" width="17" height="13" alt="e" /><?php }else{?><input type="hidden" name="chkresult[]" value="5" /><img src="images/e.gif" width="12" height="12" alt="e" /><?php }?></td>
                              </tr>
                              <tr>
                                <td class="icon2">upload</td>
                                <td class="l2"><?php if(is_writable("../upload")){?><img src="images/r.gif" width="17" height="13" alt="e" /><?php }else{?><input type="hidden" name="chkresult[]" value="6" /><img src="images/e.gif" width="12" height="12" alt="e" /><?php }?></td>
                              </tr>
                              <tr>
                                <td class="icon3">template</td>
                                <td class="l3"><?php if(is_writable("../template")){?><img src="images/r.gif" width="17" height="13" alt="e" /><?php }else{?><input type="hidden" name="chkresult[]" value="7" /><img src="images/e.gif" width="12" height="12" alt="e" /><?php }?></td>
                              </tr>
                              <tr>
                                <td><h6>&nbsp;</h6></td>
                                <td class="hr3">&nbsp;</td>
                              </tr>
                      </table> 
            	</div>    
          </div>
            
        </div>
        <div id="footer">
        	 <div class="button"><a href="#" id="js-submit">下一步 配置安装</a></div>
            <div class="button2"><a href="#" id="js-recheck">重新检查</a></div>
           
			<div class="button"><a href="#" id="js-pre-step">上一步 版权声明</a></div>
      	</div>
    </div>
</body>
</html>
