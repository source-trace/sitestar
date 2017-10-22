<style type="text/css">
<!--

html{height:100%;background-attachment:fixed;}
body{ height:100%;  margin:0; padding:0;_background-attachment: fixed;}

ul,li,img{ margin:0; padding:0; list-style:none; border:none}
.clear{ clear:both;}

#cm{background:url(images/cm_bg.gif) repeat-x; height:33px;width:100%; margin:0 auto; overflow:hidden;display: block;z-index: 1000;overflow: visible;position: fixed;top: 0px; /* position fixed for IE6 */_position: absolute;_top: expression(documentElement.scrollTop + "px");}
#cm2{background:url() repeat-x; height:33px;width:100%; margin:0 auto; overflow:hidden;display: none;z-index: 1000;overflow: visible;position: fixed;top: 0px; /* position fixed for IE6 */_position: absolute;_top: expression(documentElement.scrollTop + "px");}

/*#cm li{ float:left;}*/
#cm li.navtitle,#cm li.fg { float:left;}
#cm li a{color:#000;}
#cm .fg{  border-left:solid 1px #9ac6ff; border-right:solid 1px #fff;height:14px; margin-top:9px;_margin-top:8px;height:14px; font-size:14px;}
#cm .xl{ background:url(images/jt.gif) no-repeat; width:5px; height:3px; position:absolute; margin: 13px 0px 0px 73px;*margin: 5px 0px 0px 70px}

#cm .addlink,#cm .achick{ line-height:33px;*line-height:31px;padding:0px 16px;*padding:0px 15px; _padding:9px 15px; font-size:12px; color:#173a7a; text-decoration:none; display:block;*width:70px; text-align:center}
#cm a.addlink:hover{ background:url(images/ahover.gif) no-repeat 5px 2px;*background:url(images/ahover.gif) no-repeat 3px 2px; *width:70px; color:#173a7a}
#cm .achick{ background:url(images/achick1.gif) no-repeat 5px 2px;*background:url(images/achick1.gif) no-repeat 3px 2px;*width:70px; color:#FFF}
#cm img{ vertical-align:middle; margin-top:-4px;*margin-top:0px;_margin-top:-3px;}
#user a{ float:left; padding:}

#user{ background:url(images/ulb.gif) repeat-x; height:25px; float:left; width:290px;*width:290px;_width:315px; margin-left:15px;margin-top:4px; _margin-top:4px;overflow:hidden}
	#user .ulf{ background:url(images/ulf.gif) no-repeat; width:4px; height:25px; float:left}
	#user .urf{ background:url(images/ulr.gif) no-repeat; width:3px; height:25px; float:right}
	#user span{ float:left; color:#234d8f; line-height:25px;*line-height:20px;_line-height:19px;}
	#user .ulink{ color:#FFF; padding:0px 4px;*padding:4px 6px;_padding:6px 10px; line-height:25px; *line-height:16px;  font-size:12px; text-decoration:none}
	#user a.ulink:hover{color:#FFF; padding:0px 4px;*padding:4px 15px;_padding:6px 10px;   background:#11479d}
	
#oper{ height:7px; background:url(images/ss.gif) repeat-x; text-align:center; width:100%; clear:both}
#oper .op{ background:url(images/cc.gif) no-repeat; display:block; width:34px; height:7px; font-size:7px; margin:0 auto;}
.nav,.nav_two{ border:solid 1px #718bb7; background:#f0f0f0;*background:#f0f0f0 url(images/nvbg.gif) repeat-y 8px 0px;_background:#f0f0f0 url(images/nvbg.gif) repeat-y 8px 0px; width:130px;position:absolute; text-align:left; margin:0px 0px 0px 5px;*margin:5px 0px 0px 3px;_margin:0px 0px 0px 3px;  padding:3px; }
.nav_two{margin:-25px 0px 0px 118px;*margin:-30px 0px 0px 118px;_margin:-26px 0px 0px 118px;}
.nav_two li a {cursor:move;}
.nav li{ width:128px;_width:120px;}
.navlink,.navmo{ padding:0px 7px; *padding:0px 7px 0px 9px;_padding:6px 7px 3px 9px;font-size:12px; width:128px;_width:116px; height:24px; _height:16px;line-height:24px; text-decoration:none; color:#222222; display:block;}
a.navlink:hover,a.navmo:hover{color:#222222; padding:0px 6px;*padding:0px 4px 0px 8px;_padding:5px 6px 2px 8px; border:solid 1px #aaccf6;width:116px; height:22px; _height:16px;line-height:22px;background:#dbecf4; text-decoration:none;}
.navlink img,.navmo img{ margin-right:10px; }
.navmo{ background:url(images/jj.gif) no-repeat 110px 8px;*background:url(images/jj.gif) no-repeat 111px 7px}
a.navmo:hover{background:#dbecf4 url(images/jj.gif) no-repeat 109px 7px;*background:#dbecf4 url(images/jj.gif) no-repeat 110px 6px}
a.navlink:hover{color:#222222;}

-->
</style>
</head>
<body>

<script language="javascript">
	$(document).ready(function(){
		$(".nav").css("display","none")
		$(".navtitle > a").click(function(){
			$(".navtitle > a").each(function(){//
				$(this).removeAttr("class").addClass("addlink");
			});
			$(".nav").css("display","none");
			$(".nav_two").css("display","none");
			$(this).removeAttr("class").addClass("achick");
			$(this).next(".nav").css("display","block")
			.mouseover(function(){
				$(this).css("display","block")
			})
			/*.bind("mouseleave",function(){
				setTimeout(function(){//
					$(".nav").css("display","none");
				},500);
			})*/
			//.bind("mouseout",function(){$(this).css("display","none")});
			
			$(this).next(".nav").find(".navmo").mouseover(function(){
				setTimeout(function(){
					$(".nav_two").css("display","none");
				},100);
				$(this).next()
				.mousemove(function(){
					var self=$(this);
					setTimeout(function(){
						self.css("display","block")
					},100);
				})
				var self=$(this);
				setTimeout(function(){
					self.next().css("display","block")
				},100);
				//.bind("mouseleave",function(){ $(this).css("display","none")});
			}).bind("mouseleave",function(){
				var self=$(this);
				setTimeout(function(){
					self.parent().parent().find(".nav_two").css("display","none")
				},100);
		
			})
		
		});
		$("#main_div").click(function(){
			$(".nav").css("display","none");
			$(".nav_two").css("display","none");
		});
		//伸缩条
		$("#oper > a").click(function(){
				if($("#vv").css("display")=="block"){
					$("#vv").css("display","none");
					$("#cm").attr("id","cm2")
				}else{
					$("#vv").css("display","block");
					$("#cm2").attr("id","cm")
				}
					
			})
		
	})
	
// for delete page
<?php 
if(MOD_REWRITE=='3'){
	$curr_locale = trim(SessionHolder::get('_LOCALE'));
	$_path=strtolower(ParamHolder::get('_path', ''));
    $o_url = new MenuItem();
    $url_res = $o_url->find('url=? and s_locale=?',array($_path,$curr_locale));
	if (!empty($url_res)) {//用户自定义的链接处理
		$u_link = $url_res->link;
	}else{
	   	include(P_INC.'/custom.php');
		if(strrpos($_path,'-')){
			$arr = explode("-",$_path);
		   	$_id = intval($arr[1]);
		}
		foreach($custom as $k=>$v){
			if ($k == $_path) {//系统定义的以key为查询条件,如message.html,contact.html
				$_m = $custom[$k]['m'];
				$_a = $custom[$k]['a'];
				if ($_path=='company') {
					$sc_id = 2;
				}elseif($_path=='contact'){
					$sc_id = 1;
				}
				
				$u_link = Html::uriquery($_m,$_a,array('sc_id'=>$sc_id));
			}elseif($v['path']==$arr[0] && strpos($_path,'-')){//url处理，这类是pl-25.html等添加的页面的处理
				$_m = $v['m'];
				$_a = $v['a'];
				if ($_id!=0) {
					$u_link = Html::uriquery($_m,$_a,array($v['param']=>$_id));
				}else{
					$u_link = Html::uriquery($_m,$_a);
				}
				
			}
		}
	}
}
		?>
function delpage() {
	if(confirm("<?php _e('Are you sure delete this node and its sub nodes?');?>")) {
		<?php 
if(MOD_REWRITE=='3'){ ?>
		var params = {query:encodeURIComponent("<?php echo $u_link;?>")};
<?php }else{ ?>

		var params = {query:encodeURIComponent(this.location.href)};
<?php } ?>
		var url = "admin/index.php?_m=mod_menu_item&_a=del_page&_r=_ajax";
	    // Reform query string
	    for (key in params) {
	       url += "&" + key + "=" + params[key];
	    }
	    console.log(params);
	    console.log(url);
	    $.ajax({
	        type: "GET",
	        url: url,
	        success: onsuccess,
	        error: onfailed
	    });
	}
}

function onsuccess(response) {
    var o_result = _eval_json(response);

    if (!o_result) {
        return onfailed(response);
    }
    
    if (o_result.result == "ERROR") {
        switch(o_result.errmsg) {
        	case '-1':
        		alert("<?php _e('Unable to get URL!');?>");
        		break;
        	case '-2':
        		alert("<?php _e('Delete failed!');?>");
        		break;
        	case '-3':
        		alert("<?php _e('Can not delete! it has a children category');?>");
        		break;
        }
        return false;
    } else if (o_result.result == "OK") {
        //reloadPage();
        goto_d("<?php echo Html::uriquery2('frontpage', 'index');?>");
    } else {
        return onfailed(response);
    }
}

function onfailed(response) {
	alert("<?php _e('Request failed!');?>");
    return false;
}
</script>
<div id="cm" style="display:block;">
<div id="vv" style="width:960px;margin:0 auto;">
  <ul id="wl_nav">
<?php if(!Role::isAddBlockModEmpty()){ ?>	
    <li class="navtitle"><a href="javascript:void(0)" class="addlink"><span class="xl"></span><img src="images/ico1.gif" /> <?php _e('Add Module');?></a>
      <div id="" class="nav" style="display:none">
        <ul>
            <?php
            if(ACL::isAdminActionHasPermission('add_block', 'article')){
            ?>
          <li><a href="javascript:void(0);" class="navmo"><img src="images/ico_al.gif" /><?php _e('Article Category translate');?></a>
            <div class="nav_two">
              <ul id="modmenu1" class="pos_wrapper">
                <li pos_num="1" id='MODBLK_id' class='modmenu_flag' widget='mod_category_a-category_a_menu'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_al1.gif" /><?php _e('Article Category Menu');?></a></li>
                <li pos_num="2" id='MODBLK_id' class='modmenu_flag' widget='mod_article-recentarticles'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_al2.gif" /><?php _e('Recent Articles');?></a></li>
                <li pos_num="3" id='MODBLK_id' class='modmenu_flag' widget='mod_article-recentshort'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_al3.gif" /><?php _e('Recent Article Intro');?></a></li>
              </ul>
            </div>
          </li>
          <?php
            }
          ?>
          <?php
            if(ACL::isAdminActionHasPermission('add_block', 'product')){
            ?>
          <li><a href="javascript:void(0);" class="navmo"><img src="images/ico_p.gif" /><?php _e('Product Category translate');?></a>
            <div class="nav_two">
              <ul id="modmenu2" class="pos_wrapper">
                <li pos_num="1" id='MODBLK_id' class='modmenu_flag' widget='mod_category_p-category_p_menu'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_p1.gif" /><?php _e('Product Category Menu');?></a></li>
                <li pos_num="2" id='MODBLK_id' class='modmenu_flag' widget='mod_product-newprd'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_p2.gif" /><?php _e('New Products');?></a></li>
                <li pos_num="3" id='MODBLK_id' class='modmenu_flag' widget='mod_product-recmndprd'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_p3.gif" /><?php _e('Recommended Products');?></a></li>
              </ul>
            </div>
          </li>
          <?php
            }
          ?>
          <?php
            if(ACL::isAdminActionHasPermission('add_block', 'effect')){
            ?>
          <li><a href="javascript:void(0);" class="navmo"><img src="images/ico_tx.gif" /><?php _e('Effect Plugins');?></a>
            <div class="nav_two">
              <ul id="modmenu3" class="pos_wrapper">
                <li pos_num="1" id='MODBLK_id' class='modmenu_flag' widget='mod_media-show_image'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_tx1.gif" /><?php _e('Image');?></a></li>
                <li pos_num="2" id='MODBLK_id' class='modmenu_flag' widget='mod_media-show_flash'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_flash.gif" />Flash</a></li>
                <li pos_num="3" id='MODBLK_id' class='modmenu_flag' widget='mod_media-flash_slide'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_flash2.gif" /><?php _e('Flash slide show');?></a></li>
                <li pos_num="4" id='MODBLK_id' class='modmenu_flag' widget='mod_marquee-marquee'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_flash3.gif" /><?php _e('Mod Marquee');?></a></li>
              </ul>
            </div>
          </li>
           <?php
            }
          ?>
          <?php
            if(ACL::isAdminActionHasPermission('add_block', 'other')){
            ?>
          <li><a href="javascript:void(0);" class="navmo"><img src="images/ico_qt.gif" /><?php _e('Other Category');?></a>
            <div class="nav_two">
              <ul id="modmenu4" class="pos_wrapper">
                <li pos_num="1"id='MODBLK_id' class='modmenu_flag' widget='mod_auth-loginform'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt1.gif" /><?php _e('User Login');?></a></li>
                <!--li pos_num="2" id='MODBLK_id' class='modmenu_flag' widget='mod_lang-langbar'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt2.gif" /><?php _e('Language Bar');?></a></li-->
                <li pos_num="3" id='MODBLK_id' class='modmenu_flag' widget='mod_static-custom_html'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt3.gif" /><?php _e('Custom HTML');?></a></li>
                <li pos_num="4" id='MODBLK_id' class='modmenu_flag' widget='mod_friendlink-friendlink'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt4.gif" /><?php _e('Friend Links');?></a></li>
                <li pos_num="5" id='MODBLK_id' class='modmenu_flag' widget='mod_qq-qqlist'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt5.gif" /><?php _e('Instant Message');?></a></li>
                <li pos_num="6" id='MODBLK_id' class='modmenu_flag' widget='mod_download-recentdownloads'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt6.gif" /><?php _e('Downloads');?></a></li>
                <li pos_num="7" id='MODBLK_id' class='modmenu_flag' widget='mod_message-form'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt7.gif" /><?php _e('Message');?></a></li>
              	<li pos_num="8" id='MODBLK_id' class='modmenu_flag' widget='mod_bulletin-recentbulletins'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt8.gif" /><?php _e('Site Bulletin');?></a></li>
    			<li pos_num="9" id='MODBLK_id' class='modmenu_flag' widget='mod_static-company_intro'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_qt9.gif" /><?php _e('Company Intro');?></a></li>
    			<!--li pos_num="10" id='MODBLK_id' class='modmenu_flag' widget='mod_search-show_search'><a href="javascript:void(0);" class="navlink" title="<?php _e('Please drag this module to page');?>"><img src="images/searchtool.png" /><?php _e('Search');?></a></li-->
              </ul>
            </div>
          </li>
          <?php
            }
          ?>
		  <?php if(EZSITE_LEVEL==2&&ACL::isAdminActionHasPermission('add_block', 'shopping')){?>
          <li><ul id="modmenu5" class="pos_wrapper"><li class='modmenu_flag' widget='mod_cart-cartstatus' id='MODBLK_id'>
              <a href="javascript:void(0);" class="navlink" style="cursor:move" title="<?php _e('Please drag this module to page');?>"><img src="images/ico_gw.gif" /><?php _e('Shopping Cart');?></a></li></ul>
          </li>
		  <?php }?>
        </ul>
      </div>
    </li>
    <li class="fg"></li>
<?php } ?>
    <?php
    if(ACL::isAdminActionHasPermission('mod_menu_item', 'add_page')){
    ?>
    <li class="navtitle"><a href="javascript:void(0)" class="addlink" onClick="popup_window('admin/index.php?_m=mod_menu_item&_a=add_page','<?php _e('Add Page');?>',false,false,true);return false;" ><img src="images/ico2.gif" /> <?php _e('Add Page');?></a></li><li class="fg"></li>
    <?php
    }else{
    ?>
    
    <?php }  ?>
    
     <?php
    if(ACL::isAdminActionHasPermission('mod_menu_item', 'del_page')){
    ?>
    <li class="navtitle"><a href="javascript:void(0)" onClick="delpage()" class="addlink"><img src="images/ico3.gif" /> <?php _e('Delete Page');?></a></li><li class="fg"></li>
    <?php
    }else{
    ?>
    
    <?php }  ?>
    
    
    <?php
    $locales = trim(SessionHolder::get('_LOCALE'));
    if(!empty($_GET)) 
    {
    	$i1 = 0;
		$url1='';
	    foreach($_GET as $k => $v)
	    {
	    	if(!empty($_GET['_l']) && empty($_GET['_m']) && empty($_GET['_a'])) 
	    	{
	    		$url1 = '_m=frontpage&_a=index&';
	    		break;
	    	}
	    	
	    	if($k == '_l')
	    	{
	    		continue;
	    	}

	    	$url1 .= $k . '=' .$v . '&';
	    }
	    
	    $url1 = substr($url1,0,strlen($url1)-1);
    }else{
    	$url1 = '_m=frontpage&_a=index';
    }

	if(MOD_REWRITE=='3'){//自定义url处理，
		include(P_INC.'/custom.php');
        $_path=strtolower(ParamHolder::get('_path', ''));
        if (strpos($_path,'-')) {//系统默认Url
        	$arr = explode("-",$_path);
        	$m_url = $arr[1];
        }else{//用户自定义URL
        	$m_url = $_path;
        }
        
		$o = new MenuItem();
		$s = $o->find("`url`='".$m_url."' and s_locale='".$locales."'");
		if(!$s){//不是用户自定义链接，则分析找系统的链接
			foreach ($custom as $k=>$pa_v){
			  	if ($m_url==$k) {//系统定义的以key为查询条件,如message.html,contact.html
					$_m = $custom[$k]['m'];
					$_a = $custom[$k]['a'];
					$url1 = '_m='.$_m.'&_a='.$_a;
					if ($m_url=='company') {
						$o_sc = new StaticContent();
						$curr_id = $o_sc->find("s_locale=? and flag=?",array($locales,"company"),"ORDER BY `id`");
						$sc_id = $curr_id->id;
					}elseif($m_url=='contact'){
						$o_sc = new StaticContent();
						$curr_id = $o_sc->find("s_locale=? and flag=?",array($locales,"contact"),"ORDER BY `id`");
						$sc_id = $curr_id->id;
					}
					if (!empty($sc_id)) {
						$url1 .= '&sc_id='.$sc_id;
					}
					
					$o_mi1 = new MenuItem();
					$res1 = $o_mi1->find("link = '$url1' AND s_locale = '$locales'");
				}elseif($pa_v['path'] == $arr[0] && strpos($_path,'-')){//系统定义的，以path为查询条件,如al-0.html,pl-23.html
					$_m = $pa_v['m'];
					$_a = $pa_v['a'];
					if ($m_url==0) {//全部显示
						$url1 = '_m='.$_m.'&_a='.$_a;
					}else{//分类显示，加上ID参数
						$url1 = '_m='.$_m.'&_a='.$_a.'&'.$pa_v['param'].'='.$m_url;
					}
					$o_mi1 = new MenuItem();
					$res1 = $o_mi1->find("link = '$url1' AND s_locale = '$locales'");
				}
			}
		}else{
			
			$res1 = $o->find("`url`='".$m_url."' and s_locale='".$locales."'");
		}
	}else{
		$locales = trim(SessionHolder::get('_LOCALE'));
		$o_mi1 = new MenuItem();
		$res1 = $o_mi1->find("link = '$url1' AND s_locale = '$locales'");
	}
    ?>
    <?php 
    if(ACL::isAdminActionHasPermission('mod_menu_item', 'admin_edit')){	
			if(empty($res1->id)) { 
    ?>
    	<li class="navtitle"><a style="color:#a6a6a6;" href="javascript:void(0)" class="addlink"><img src="images/ico4.gif" /> <?php _e('Page Property');?></a></li><li class="fg"></li>
    <?php	
			} else {
		
	?>
    <li class="navtitle"><a href="javascript:void(0)" class="addlink" onClick="popup_window('admin/index.php?_m=mod_menu_item&_a=admin_edit&mi_id=<?php echo $res1->id;?>','<?php _e('Page Property');?>',false,false,true);return false;"><img src="images/ico4.gif" /> <?php _e('Page Property');?></a></li><li class="fg"></li>
    <?php
		
		}
	}
	?>
    
   <?php
            if(ACL::isAdminActionHasPermission('mod_template', 'admin_list')){
   ?>
    <li class="navtitle"><a href="javascript:void(0)" class="addlink" onClick="popup_window('admin/index.php?_m=mod_template&_a=admin_list','<?php _e('Manage Templates');?>',950,'',true,false,false,true,true);return false;"><img style="width:16px;height:16px;" src="images/template.gif" /> <?php _e('Manage Templates');?></a></li><li class="fg"></li>
    <?php
    }else{
    ?>
 
    <?php }  ?>
    <?php 
	$per = Role::getRolePermission(SessionHolder::get("user/s_role"));
	if(Role::isActionPermission("mod_all_web","web",$per) || !Role::isAddBlockModEmpty()){ 
	?>
    <li class="navtitle"><a href="javascript:void(0)" class="addlink"><span class="xl"></span><img src="images/ico5.gif" /> <?php _e('Preferences');?></a>
      <div class="nav" style="display:none;">
        <ul>
           <?php
            if(ACL::isAdminActionHasPermission('mod_site', 'admin_list')){
            ?>     
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_site&_a=admin_list','<?php _e('Web Set');?>',false,false,true);return false;"><img src="images/help1.gif" /><?php _e('Web Set');?></a></li>
          <?php } ?>
		  <?php
            if(ACL::isAdminActionHasPermission('mod_site', 'admin_bg')){
            ?>  
		  <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_site&_a=admin_bg','<?php _e('SITE BACKGROUND');?>',false,false,true);return false;"><img src="images/site-background-set.gif" /><?php _e('SITE BACKGROUND');?></a></li>
		  <?php } ?>
          <?php
            if(ACL::isAdminActionHasPermission('mod_site', 'admin_seo')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_site&_a=admin_seo','<?php _e('SEO Set');?>',false,false,true);return false;"><img src="images/seo.gif" /><?php _e('SEO Set');?></a></li>
          <?php } ?>
		<?php
            if(ACL::isAdminActionHasPermission('mod_lang', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_lang&_a=admin_list','<?php _e('Language Manager');?>',false,false,true);return false;"><img src="images/lang.gif" /><?php _e('Language Manager');?></a></li>
          <?php } ?> 
		<?php
            if(ACL::isAdminActionHasPermission('mod_navigation', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_navigation&_a=admin_list','<?php _e('Home Navigation');?>',false,false,true);return false;"><img src="images/hnav.gif" /><?php _e('Home Navigation');?></a></li>
	<?php } ?>	  
          <?php if(EZSITE_LEVEL==2&&ACL::isAdminActionHasPermission('mod_payaccount', 'admin_list')){?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_payaccount&_a=admin_list','<?php _e('Payment Set');?>',false,false,true);return false;"><img src="images/help2.gif" /><?php _e('Payment Set');?></a></li>
		  <?php }?>
          <?php
            if(ACL::isAdminActionHasPermission('mod_backup', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_backup&_a=admin_list','<?php _e('Data Backup & Recovery');?>',false,false,true);return false;"><img src="images/help3.gif" /><?php _e('Data Backup & Recovery');?></a></li>
          <?php }?>
          <?php
            if(ACL::isAdminActionHasPermission('mod_attachment', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_attachment&_a=admin_list','<?php _e('Watemark & Thumbnail');?>',false,false,true);return false;"><img src="images/help4.gif" /><?php _e('Watemark & Thumbnail');?></a></li>
          <?php }?>
          <?php
            if(ACL::isAdminActionHasPermission('mod_advert', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_advert&_a=admin_list','<?php _e('Advert Tool');?>',false,false,true,false,false,true,true);return false;"><img src="images/atool.gif" /><?php _e('Advert Tool');?></a></li>
          <?php }?>
          <?php
            if(ACL::isAdminActionHasPermission('mod_message', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_message&_a=admin_list','<?php _e('Message Manage');?>',false,false,true);return false;"><img src="images/help5.gif" /><?php _e('Message Manage');?></a></li>
          <?php }?>
          <?php
            if(ACL::isAdminActionHasPermission('mod_filemanager', 'admin_dashboard')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_filemanager&_a=admin_dashboard','<?php _e('File Manager');?>',false,false,true);return false;"><img src="images/file.gif" /><?php _e('File Manager');?></a></li>
           <?php }?>
          <?php
            if(ACL::isAdminActionHasPermission('mod_user', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_user&_a=admin_list','<?php _e('User Manage');?>','884',false,true);return false;"><img src="images/help6.gif" /><?php _e('User Manage');?></a></li>
	<?php }?>	  
          <?php if(EZSITE_LEVEL==2 && ACL::isAdminActionHasPermission('mod_order', 'admin_list')){?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_order&_a=admin_list','<?php _e('User Order');?>',false,false,true);return false;"><img src="images/help7.gif" /><?php _e('User Order');?></a></li>
	<?php }?>
	<?php
            if(ACL::isAdminActionHasPermission('mod_statistics', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_statistics&_a=admin_list','<?php _e('Statistics');?>',false,false,true);return false;"><img src="images/help9.gif" /><?php _e('Statistics');?></a></li>
	<?php }?>
	<?php
            if(ACL::isAdminActionHasPermission('mod_bshare', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_bshare&_a=admin_list','<?php _e('Bshare');?>',700,false,true);return false;"><img src="images/bshare.gif" /><?php _e('Bshare');?></a></li>
	<?php }?>
	<?php
            if(ACL::isAdminActionHasPermission('mod_email', 'admin_list')){
            ?>
          <li><a href="" class="navlink" onClick="popup_window('admin/index.php?_m=mod_email&_a=admin_list','<?php _e('Notes/Email send');?>',800,false,true);return false;"><img src="images/message_icon.gif" /><?php _e('Notes/Email send');?></a></li>
	<?php }?>
          <?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){}else{ ?>
          <li><a href="http://www1.sitestar.cn/help/" target='_blank' class="navlink"><img src="images/help8.gif" /><?php _e('Help');?></a></li>
          <?php } ?>
        </ul>
      </div>
    </li><?php }?>
  </ul>
  <div id="user">
    <div class="urf"></div>
    <div class="ulf"></div>
    <a href="javascript:save_layout();" class="ulink">
    <img src="images/save.gif" /> <?php _e('Save Layout');?></a><span>|</span> 
	<?php
		if(MOD_REWRITE=='2'){
		$url_arr = explode("&",$_SERVER['QUERY_STRING']);
		$url_arr_num = sizeof($url_arr);
		$url_param = array();
		if($url_arr_num > 2){
			
			foreach($url_arr as $k=>$v){
				if($k < 2) continue;
				$url_tmp = explode("=",$v);
				$url_param[$url_tmp[0]]=$url_tmp[1];
			}
		}
		$url_param = array_merge($url_param,array('_v'=>'preview'));
		$url_html='';
		if(isset($url_arr[0])&&isset($url_arr[1]))
			$url_html=Html::uriquery(substr($url_arr[0],3),substr($url_arr[1],3),$url_param);
		else
			$url_html='frontpage-index-_v-preview.html';
	?>
    <a href="<?php echo $url_html; ?>" class="ulink" target="_blank"><img src="images/preview.gif" /> <?php _e('Preview');?></a><span>|</span>
	<?php }else if(MOD_REWRITE=='3'){
			$_path=strtolower(ParamHolder::get('_path', ''));
			if(empty($_path)) $_path='index';
			$prev_url = $_path.'-_v-preview'.'.html';
		?>
		<a href="<?php echo $prev_url;?>" class="ulink" target="_blank"><img src="images/preview.gif" /> <?php _e('Preview');?></a><span>|</span>
	<?php }else{?>
	<a href="?_v=preview<?php if($_SERVER['QUERY_STRING']){echo "&".$_SERVER['QUERY_STRING'];}else{ echo '&_m=frontpage&_a=index';} ?>" class="ulink" target="_blank"><img src="images/preview.gif" /> <?php _e('Preview');?></a><span>|</span>
	<?php }?>
    <a href="admin/index.php?_m=frontpage&_a=dashboard" class="ulink"><img src="admin/template/images/user.gif" /> <?php _e('Administrator admin');?></a><span>|</span>
    <a href="admin/index.php?_m=frontpage&_a=dologout" class="ulink"><img src="images/quit.gif" /> <?php _e('Logout');?></a> 
    
    </div>
	</div>
	<div id="oper" style="display:block;"> <a href="javascript:void(0)" class="op"></a></div>
</div>
<div style="height:38px;"></div>
<script type="text/javascript" language="javascript">
<!--
    function save_layout() {
        var pos_form = document.forms["SAVE_POS"];
        save_position(pos_form, on_save_pos_success, on_save_pos_failure);
    }
    
    function on_save_pos_success(response) {
        var o_result = _eval_json(response);
        if (!o_result) {
            return on_failure(response);
        }
        
        if (o_result.result == "OK") {
            alert("<?php _e('Layout saved!'); ?>");
            return true;
        } else {
            return on_failure(response);
        }
    }

    function on_save_pos_failure(response) {
        alert("<?php _e('Request failed!'); ?>");
        return false;
    }
    
    function delayHide(jq_obj) {
        setTimeout(
            function() {
                jq_obj.children("div").hide("normal");
            },
            200
        );
    }
    
    function addHoverHide() {
        $("#admintoolbar_nav li").each(
            function() {
                $(this).hover(
	                function(){
	                    $(this).children("div").show("normal");
	                },
	                function(){
	                    delayHide($(this));
	                }
                );
            }
        );
    }
    addHoverHide();
//-->
</script>