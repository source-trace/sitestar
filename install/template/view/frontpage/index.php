<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SiteStar建站之星安装程序</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery.min.js"></script>
<script>
jQuery(function($){
	$("#js-agree").click(function(){
		if($("#js-agree").attr("checked") == true){
			$("#js-submit").attr("disabled",false);
		}else{
			$("#js-submit").attr("disabled",true);
		}
	});
	$("#js-submit").click(function(){
		if($("#js-agree").attr("checked") == false){
			return false;
		}else{
			this.href="index.php?_m=frontpage&_a=check";
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
                	<li class="hov">欢迎您使用SiteStar</li>
                    <li>检查系统环境</li>
                    <li>配置系统</li>
                    <li>完成安装</li>
                </ul>
            </div>
            
            <div id="right">
            	<iframe src="template/view/frontpage/Agreement.html"></iframe>
                <div id="right_bd">
                	<input type="checkbox" name="js-agree" id="js-agree" value="sub" /><span>我已经仔细阅读，并同意上述条款中的所有内容</span>
                </div>          
            </div>
            
        </div>
        <div id="footer">
        	<div class="button"><a href="#" id="js-submit" disabled='false'>下一步 安装环境</a></div>
      	</div>
    </div>
</body>
</html>