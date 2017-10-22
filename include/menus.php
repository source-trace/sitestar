<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$menus = array (
    // List page type
    'article_list' => array (
        'name' => __('Article List'),
        'mod_addr' => array (
            'mod_name' => 'mod_article',
            'addr' => 'fullist'
        ),
        'is_id' => true,
        'obj_name' => 'ArticleCategory',
        'obj_field' => 'name',
        'id_category' => 'caa_id',
        'type' => 'Content List',
        'use_popup' => true
    ),
    'product_list' => array (
        'name' => __('Product List'),
        'mod_addr' => array (
            'mod_name' => 'mod_product',
            'addr' => 'prdlist'
        ),
        'is_id' => true,
        'obj_name' => 'ProductCategory',
        'obj_field' => 'name',
        'id_category' => 'cap_id',
        'type' => 'Content List',
        'use_popup' => true
    ),
    'download_list' => array (
        'name' => __('Download List'),
        'mod_addr' => array (
            'mod_name' => 'mod_download',
            'addr' => 'fullist'
        ),
        'is_id' => true,
        'obj_name' => 'DownloadCategory',
        'obj_field' => 'name',
        'id_category' => 'cad_id',
        'type' => 'Content List',
        'use_popup' => true
    ),
    /*
    'news_list' => array (
        'name' => __('News'),
        'mod_addr' => array (
            'mod_name' => 'mod_news',
            'addr' => 'fullist'
        ),
        'is_id' => false,
        'type' => 'Content List',
        'use_popup' => false
    ),
    */
    'link_list' => array (
        'name' => __('Links'),
        'mod_addr' => array (
            'mod_name' => 'mod_friendlink',
            'addr' => 'fullist'
        ),
        'is_id' => false,
        'type' => 'Content List',
        'use_popup' => false
    ),
    
    // Content page type
    'article' => array(
        'name' => __('Article Content'),
        'mod_addr' => array (
            'mod_name' => 'mod_article',
            'addr' => 'article_content'
        ),
        'is_id' => true,
        'obj_name' => 'Article',
        'obj_field' => 'title',
        'id_category' => 'article_id',
        'type' => 'Content View',
        'use_popup' => true
    ),
    'product' => array(
        'name' => __('Product Introduction'),
        'mod_addr' => array (
            'mod_name' => 'mod_product',
            'addr' => 'view'
        ),
        'is_id' => true,
        'obj_name' => 'Product',
        'obj_field' => 'name',
        'id_category' => 'p_id',
        'type' => 'Content View',
        'use_popup' => true
    ),
    'static' => array (
        'name' => __('Custom Page'),
        'mod_addr' => array (
            'mod_name' => 'mod_static',
            'addr' => 'view'
        ),
        'is_id' => true,
        'obj_name' => 'StaticContent',
        'obj_field' => 'title',
        'id_category' => 'sc_id',
        'type' => 'Content View',
        'use_popup' => true
    ),
    // Build in page type
    'frontpage' => array (
        'name' => __('Frontpage'),
        'mod_addr' => array (
        	'mod_name' => 'frontpage',
        	'addr' => 'index'
        ),
        'is_id' => false,
        'type' => 'Other Page',
        'use_popup' => false
    ),
    'company_info' => array (
        'name' => __('Company Introduction'),
        'mod_addr' => array (
            'mod_name' => 'mod_static',
            'addr' => 'view'
        ),
        'is_id' => false,
        'id_category' => 'sc_id',
        'type' => 'Other Page',
        'use_popup' => false
    ),
    'contact_info' => array (
        'name' => __('Contact Us'),
        'mod_addr' => array (
            'mod_name' => 'mod_static',
            'addr' => 'view'
        ),
        'is_id' => false,
        'id_category' => 'sc_id',
        'type' => 'Other Page',
        'use_popup' => false
    ),
    'message' => array (
        'name' => __('Contact Form'),
        'mod_addr' => array (
            'mod_name' => 'mod_message',
            'addr' => 'form'
        ),
        'is_id' => false,
        'type' => 'Other Page',
        'use_popup' => false
    ),
   'bulletins' => array (
        'name' => __('Bulletins'),
        'mod_addr' => array (
            'mod_name' => 'mod_bulletin',
            'addr' => 'bulletin_content'
        ),
        'is_id' => true,
        'type' => 'Content View',
		'id_category' => 'bulletin_id',
        'use_popup' => true
    ),
    'outer_url' => array (
        'name' => __('External Url'),
        'mod_addr' => array (
        ),
        'is_id' => false,
        'type' => 'Content View',
        'use_popup' => true
    ),
    'mod_user' => array(
    	'name' => __('Users'),
    	'mod_addr' => array (
            'mod_name' => 'mod_user',
            'addr' => 'reg_form'
        ),
        'is_id' => false,
        'type' => 'Other Page',
        'use_popup' => false
    ),
);
?>
