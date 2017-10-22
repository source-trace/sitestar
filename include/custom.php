<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
/**
* ˵��
* page:
*/
$custom = array(
	"index"=>array(
		"mi_category"=>"frontpage",
		"path"=>"",
		"m"=>"frontpage",
		"a"=>"index",
		"flag"=>false,
	),
	"company"=>array(
		"mi_category"=>"company_info",
		"path"=>"",
		"m"=>"mod_static",
		"a"=>"view",
		"flag"=>false,
		'where'=>'company',
		"param"=>"sc_id"
	),
	"article_list"=>array(
		"mi_category"=>"article_list",
		"path"=>"al",
		"m"=>"mod_article",
		"a"=>"fullist",
		"flag"=>true,
		"param"=>"caa_id"
	),
	"product_list"=>array(
		"mi_category"=>"product_list",
		"path"=>"pl",
		"m"=>"mod_product",
		"a"=>"prdlist",
		"flag"=>true,
		"param"=>"cap_id"
	),
	"message"=>array(
		"mi_category"=>"message",
		"path"=>"",
		"m"=>"mod_message",
		"a"=>"form",
		"flag"=>false
	),
	"contact"=>array(
		"mi_category"=>"contact_info",
		"path"=>"",
		"m"=>"mod_static",
		"a"=>"view",
		"flag"=>false,
		'where'=>'contact',
		"param"=>"sc_id"
	),
	"static"=>array(
		"mi_category"=>"static",
		"path"=>"s",
		"m"=>"mod_static",
		"a"=>"view",
		"flag"=>true,
		'where'=>'static',
		"param"=>"sc_id"
	),
	"article"=>array(
		"mi_category"=>"article",
		"path"=>"a",
		"m"=>"mod_article",
		"a"=>"article_content",
		"flag"=>true,
		"param"=>"article_id"
	),
	"product"=>array(
		"mi_category"=>"product",
		"path"=>"p",
		"m"=>"mod_product",
		"a"=>"view",
		"flag"=>true,
		"param"=>"p_id"
	),
	"friendlink"=>array(
		"mi_category"=>"link_list",
		"path"=>"",
		"m"=>"mod_friendlink",
		"a"=>"fullist",
		"flag"=>false
	),
	"download_list"=>array(
		"mi_category"=>"download_list",
		"path"=>"dl",
		"m"=>"mod_download",
		"a"=>"fullist",
		"flag"=>true,
		"param"=>"cad_id"
	),
	"download"=>array(
		"mi_category"=>"download",
		"path"=>"d",
		"m"=>"mod_download",
		"a"=>"download",
		"flag"=>true,
		"param"=>"dw_id"
	),
	"user"=>array(
		"mi_category"=>"user",
		"path"=>"u",
		"m"=>"mod_user",
		"a"=>"edit_profile",
		"flag"=>true,
	),
	"order"=>array(
		"mi_category"=>"order",
		"path"=>"order",
		"m"=>"mod_order",
		"a"=>"userlistorder",
		"flag"=>true,
	),
	"navigation"=>array(
		"mi_category"=>"navigation",
		"path"=>"navigation",
		"m"=>"mod_navigation",
		"a"=>"index",
		"flag"=>true,
	),
	"bulletin"=>array(
		"mi_category"=>"bulletin",
		"path"=>"bulletin",
		"m"=>"mod_bulletin",
		"a"=>"bulletin_content",
		"param"=>"bulletin_id",
		"flag"=>true,
	),
);
?>