<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SiteStar建站之星安装程序</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="../script/jquery.min.js"></script>
<script src="js/install.js"></script>
</head>

<body>
    <div id="in">
    	<div id="top"><span>SiteStar建站之星安装程序</span></div>
        <div id="banner"></div>
        <div id="center">
        
        	<div id="left">
            	<ul>
                	<li>欢迎您使用SiteStar</li>
                    <li>检查系统环境</li>
                    <li class="hov">配置系统</li>
                    <li>完成安装</li>
                </ul>
            </div>
            
            <div id="right">
            	<div id="right_bor">
				<h1>数据库设定</h1>
                    <table cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="18%" class="icon">数据库主机</td>
                            <td width="82%">
                              <input type="text" name="db-host"  id="db-host" value="" />
							  (请输入您数据库的地址)

                            </td>
                          </tr>
                          <tr>
                            <td class="icon">端口号</td>
                            <td>
                              <input type="text" name="db-port" id="db-port" value="3306" />
                            </td>
                          </tr>
                          <tr>
                            <td class="icon">用户名</td>
                            <td>
                              <input type="text" name="db-user" id="db-user"  value="" />
							  (请输入您的数据库用户名)
                            </td>
                          </tr>
                          <tr>
                            <td class="icon">密码</td>
                            <td>
                              <input type="password" name="db-pwd" id="db-pwd"  value="" />
							  (请输入您的数据库密码)
                            </td>
                          </tr>
                          <tr>
                            <td class="icon">数据库名</td>
                            <td>
                              <input type="text" name="db-name" id="db-name"  value="" />
							  (请输入您的数据库名称)
                            </td>
                          </tr>
                          <tr>
                            <td class="icon">表前缀</td>
                            <td>
                              <input type="text" name="db-prefix" id="db-prefix" value="ss_" />
                              (建议您修改表前缀名)    </td>
                          </tr>
                        </table>
                <h2>管理员账号</h2>
                <table  cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="18%" class="icon">管理员用户名</td>
                    <td width="82%"><input type="text" name="js-admin-name" id="js-admin-name" value="" onkeyup="value=value.replace(/[^\w\)\(\- ]/g,'')" /></td>
                  </tr>
                  <tr>
                    <td class="icon">登录密码</td>
                    <td><input type="password" name="js-admin-password" id="js-admin-password"  value="" /></td>
                  </tr>
                  </table>
<h2>其他</h2>
                	<table cellspacing="0" cellpadding="0">
                	  <!--tr>
                	    <td width="18%" class="icon">
                	      安装体验数据
              	      </td>
                	    <td width="82%"><input type="checkbox" class="p" name="js-install-demo" id="js-install-demo" value="1" checked/>
               	        (安装体验数据后，您不必进行任何系统设置，您可以用模板数据体验SiteStar的各项功能)</td>
              	    </tr-->
					 <tr>
                	    <td class="icon">安装进度</td>
                	    <td id="install_result" style="color:#F6A126;text-align:center;">&nbsp;</td>
              	    </tr>
               	  </table>
            	</div>    
          </div>
            
        </div>
        <div id="footer">
            <div class="button2" ><a href="javascript:void(0);" id="js-install-at-once">立即安装</a></div>
			<div class="button"><a href="#" id="js-pre-step">上一步 安装环境</a></div>
      	</div>
    </div>
</body>
</html>
