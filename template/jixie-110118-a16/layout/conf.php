<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * 页面布局配置文件
 *
 */
class LayouConfig
{
	public static $layout_param = array(
		'left' => array(                //左布局
			 'layout_php_file' => 'layout_left.php', //获取布局的php文件
			 'layout_css_file' => 'style_left.css',//获取布局的css文件,存放在上一级目录的css目录中
			 'layout_screenshot_file' => 'layout_left.gif',//获取布局的缩略图
			 'author' => 'anonymity',//布局作者
			 'description' => 'This is left align style'//布局描述
		),
		'right' => array(        //右布局
			 'layout_php_file' => 'layout_right.php',
			 'layout_css_file' => 'style_right.css',
			 'layout_screenshot_file' => 'layout_right.gif',
			 'author' => 'anonymity',
			 'description' => 'This is right align style'
		),
		'default' => array(        //默认布局key名称写死
			 'layout_php_file' => 'layout.php',
			 'layout_css_file' => 'style.css',
			 'layout_screenshot_file' => 'layout_left.gif',
			 'author' => 'anonymity',
			 'description' => 'This is default style'
		),
	);
}
?>