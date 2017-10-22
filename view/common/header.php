<?php 
if (!defined('IN_CONTEXT')) die('access violation error!');

if(isset($curr_article)){
	$site_key = $curr_article->tags;
}else{
	if(empty($result->meta_key)) 
		$site_key =  $_SITE->keywords;
	else 
		$site_key =  $result->meta_key;
}

//---------文章和产品description标识 hfh-----------------
$_pathseo = explode('-',$_path); //MOD_REWRITE 3 seo add 2013.12.11
//if(isset($_GET['_m']) && $_GET['_m'] == 'mod_article' && $_GET['_a'] == 'article_content' && !empty($_GET['article_id']))
if((isset($_GET['_m']) && $_GET['_m'] == 'mod_article' && $_GET['_a'] == 'article_content' && !empty($_GET['article_id'])) || (isset($_GET['_m']) && R_MOD == 'mod_article' && R_ACT == 'article_content' && !empty($_pathseo[1])) )
{
	//if(!empty($curr_article->intro))
	if ($curr_article->is_seo == '1')
	{
		//$site_description = strip_tags($curr_article->intro);
		$site_key = $curr_article->tags;
		$site_description = strip_tags($curr_article->description);
	}
	else
	{
		$site_key = '';
		if(empty($result->meta_desc)) 
			$site_description = $_SITE->description; 
		else 
			$site_description = $result->meta_desc;
	}
	
	if(empty($site_key))
	{
		$site_key = $_SITE->keywords;
	}
}
//elseif(isset($_GET['_m']) && $_GET['_m'] == 'mod_product' && $_GET['_a'] == 'view' && !empty($_GET['p_id']))
elseif((isset($_GET['_m']) && $_GET['_m'] == 'mod_product' && $_GET['_a'] == 'view' && !empty($_GET['p_id'])) || (R_MOD == 'mod_product' && R_ACT == 'view' && !empty($_pathseo[1])))
{
	if ($curr_product->is_seo == '1')
	{
		//$site_description = strip_tags($curr_article->intro);
		$site_key = $curr_product->meta_key;
		$site_description = strip_tags($curr_product->meta_desc);
	}else if(!empty($curr_product->introduction))
	{
		$site_description = strip_tags($curr_product->introduction);
	}
	else
	{
		if(empty($result->meta_desc)) 
			$site_description = $_SITE->description; 
		else 
			$site_description = $result->meta_desc;
	}
}
else
{
	if(empty($result->meta_desc)) 
		$site_description = $_SITE->description; 
	else
		$site_description = $result->meta_desc;
}

//页面标题
if(!empty($result->title))
{
	$page_title = $result->title;
}
//---------文章和产品description标识 hfh-----------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- 页面头部【start】 -->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
<!--base href="http://www.sitestar.com/"/-->
<?php if(ICO_PATH=='favicon.ico') {?>
<link rel="Shortcut Icon" href="<?php echo ICO_PATH;?>" />
<?php } ?>
<title><?php $_page_cat='';
if(isset($page_cat)) $_page_cat=$page_cat; 
echo isset($page_title)?$page_title:$_page_cat; 
if(empty($result->title)){ 	
	if (!ToolKit::getCorp()) {		
		if (strrpos($_SITE->site_name,"sitestar")==0) {			
			echo '--'.$_SITE->site_name;		
		}
	}else{		
		echo '--'.$_SITE->site_name; 	
	}
}else{
	
}
?></title>
<meta name="keywords" content="<?php echo $site_key;?>" />
<meta name="description" content="<?php echo $site_description;?>" />
<?php  echo empty($meta_str)?'':($meta_str."\n");?>
<script type="text/javascript" src="script/tree.js"></script>
<?php 
include_once(ROOT.'/template/'.DEFAULT_TPL.'/layout/conf.php');
$arr_params = LayouConfig::$layout_param;
if(empty($result->layout)|| $result->layout == 'default')
{
	$css_file = P_TPL_WEB.'/css/'.$arr_params['default']['layout_css_file'];
}
else
{
	$css_file = P_TPL_WEB.'/css/'.$arr_params[$result->layout]['layout_css_file'];
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $css_file;?>" />
<?php
if (SessionHolder::get('page/status', 'view') == 'edit') {
?>
<link rel="stylesheet" type="text/css" href="view/css/admin.css" />
<?php
}
include_once(P_INC.'/global_js.php');
// 放大镜
if ($_flat_module_class_name == 'mod_product'&&SessionHolder::get('page/status', 'view') != 'edit') {
	Html::includeJs('/jqzoom.js');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $script_path; ?>/jqzoom.css" />
<script language="javascript">
$(function(){
	$(".jqzoom").jqueryzoom({xzoom:300, yzoom:300});	
});
</script>
<?php } ?>


<?php 
//------------------首页页面加载特殊处理【start】--------------------
if($_flat_module_class_name == 'frontpage')
{
	//-----------------背景音乐【start】-------------------
	$o_bgmusic = new BackgroundMusic();
	$bgmusic_items = $o_bgmusic->findAll();
	if(sizeof($bgmusic_items) > 0){
		$music_path = $bgmusic_items[0]->music_path;
		if($bgmusic_items[0]->play == 2) {
			echo "<bgsound src='$music_path' loop='-1'>";
		} elseif($bgmusic_items[0]->play == 1) {
			echo "<bgsound src='$music_path' loop='1'>";
		}
	}
}
	//-----------------背景音乐【start】-------------------

//------------------首页页面加载特殊处理【end】----------------------
?>
<?php
//背景逻辑处理2013/1/17 zhangjc
if(BACKGROUND_INFO){
$local_lang=trim(SessionHolder::get('_LOCALE'));
$seria = unserialize(BACKGROUND_INFO);
$seria_arr = $seria[$local_lang];

$style='background:';
if($seria_arr['color']&&$seria_arr['color']!='transparent'){
	$style.=$seria_arr['color'].' ';
}
if($seria_arr['img']){
	$style.='url(upload/image/'.$seria_arr['img'].') '.$seria_arr['radio'].' '.$seria_arr['fixed'].' '.$seria_arr['position'];
}
}
?>

</head>
<!--  页面头部【end】 -->
<body class="body1" <?php if($style!='background:'){?>style="<?php echo $style;?>"<?php }?>>
<?php if(Toolkit::editMode()){ ?>
<div id="getValues" value="<?php echo R_ACT;?>" style="display:none;"></div>
<div id="getParams" value='<?php echo serialize($_GET);?>' style="display:none;"></div>
<?php } ?>
<?php 
Html::adminBar(); 
if($_flat_module_class_name == 'frontpage'){
	if(isset($wrap)){
		echo $wrap;//加载广告
	}
}
?>