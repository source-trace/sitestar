<?php 
if (!defined('IN_CONTEXT')) die('access violation error!');
//加载页面头部
include_once('view/common/header.php');
?>

<div id="main_div">
<div id="top">
<div id="header">
		<div id="logo"><?php 
				if (Content::countModules('logo') > 0 || Toolkit::editMode()) {
					echo Content::loadModules('logo');
				} else { ?>
					<a href="/"><img src="<?php echo P_TPL_WEB; ?>/images/logo.jpg" border="0" /></a>
			<?php }?>
            </div>
	   <!-- 站点语言模块Start -->
       <div class="switch_langbar"><?php include_once(P_INC.'/language_switch.php');?></div>
       <!-- 站点语言模块End -->
	</div>
</div>

    <div id="banner">
	<?php 
		if (Content::countModules('banner') > 0 || Toolkit::editMode()) 
		{
			echo Content::loadModules('banner');
		} 
		else 
		{ ?>
		<img src="<?php echo P_TPL_WEB; ?>/images/banner.jpg" border="0" />
		<?php }?>
	</div>

<div class="web_bg">
<div id="mainmain">

  <div id="nav">
	<?php if (Content::countModules('nav') > 0 || Toolkit::editMode()) Content::loadModules('nav'); ?>
	</div>  

	<div id="main_con">
    
		<div id="left">
		<?php if (Content::countModules('left') > 0 || Toolkit::editMode()) Content::loadModules('right'); ?>
		</div>
		<?php if($_flat_module_class_name == 'frontpage'){?>
		<div id="right">
			<?php if (Content::countModules('right') > 0 || Toolkit::editMode()) Content::loadModules('left'); ?>
		</div>
		<?php }else{?>
		<div id="right">
			<?php include_once($_content_); ?>
		</div>
		<?php }?>
       

        <div class="blankbar"></div>
	</div>
    </div>
    </div>
    
<?php 
//加载页面尾部
include_once('view/common/footer.php');
?>