<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$widgets = array(
    'mod_auth' => array(
        'loginform' => array(
            'name' => __('User Login'), 
            'description' => '', 
            'parameters' => array(
            )
        )
    ), 
    'mod_lang' => array(
        'langbar' => array(
            'name' => __('Language Bar'),
            'description' => '',
            'parameters' => array(
            )
        )
    ),
	'mod_message' => array(
        'form' => array(
            'name' => __('Message'),
            'description' => '',
            'parameters' => array(
            )
        )
    ),
    'mod_category_a' => array(
    	'category_a_menu' => array(
    		'name' => __('Article Category Menu'),
    		'description' => '',
    		'parameters' => array(
    			// 28/4/2010 Add >>
                array(
                    'id' => 'article_category_list[]',
                    'label' => __('Category'),
                    'tag' => 'select',
					'extra' => 'multiple="true" style="width:180px" size="7"',
					'fill_type' => 'multiple',
                    'obj_name' => 'ArticleCategory',
                    'func_name' => 'getCategoryArray'
                ),
                // 28/4/2010 Add <<
                /**
                 * for menu-drop type
                 */
                array(
                	'id' => 'article_category_type', 
                    'label' => __('List display'), 
                    'tag' => 'select',
    				'extra' => '',
    				'fill_type' => 'objfunc',
                    'obj_name' => 'ProductCategory',
                    'func_name' => 'dropType'
                )
    		)
    	)
    ),
    'mod_category_p' => array(
    	'category_p_menu' => array(
    		'name' => __('Product Category Menu'),
    		'description' => '',
    		'parameters' => array(
    			// 28/4/2010 Add >>
                array(
                    'id' => 'product_category_list[]',
                    'label' => __('Category'),
                    'tag' => 'select',
					'extra' => 'multiple="true" style="width:180px" size="7"',
					'fill_type' => 'multiple',
                    'obj_name' => 'ProductCategory',
                    'func_name' => 'getCategoryArray'
                ),
                // 28/4/2010 Add <<
                /**
                 * for menu-drop type
                 */
                array(
                	'id' => 'product_category_type', 
                    'label' => __('List display'), 
                    'tag' => 'select',
    				'extra' => '',
    				'fill_type' => 'objfunc',
                    'obj_name' => 'ProductCategory',
                    'func_name' => 'dropType'
                )
    		)
    	)
    ),
    'mod_static' => array(
        'custom_html' => array(
            'name' => __('Custom HTML'), 
            'description' => '', 
            'parameters' => array(
                array(
                    'id' => 'html', 
                    'label' => __('HTML'), 
                    'tag' => 'textarea', 
                    'extra' => 'rows="12" cols="56"'
                )
            )
        ),
        // for sitestarv1.3 09/06/2010
        'company_intro' => array(
            'name' => __('Company Introduction'),
            'description' => '',
            'parameters' => array(
            	array(
                    'id' => 'cpy_intro_number', 
                    'label' => __('Show characters number'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                )
            )
        )
    ),
    'mod_article' => array(
        'recentarticles' => array(
            'name' => __('Recent Articles'),
            'description' => '',
            'parameters' => array(
                array(
                    'id' => 'article_reclst_size', 
                    'label' => __('List Size'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput"'
                ),
				array(
                    'id' => 'article_category_list[]',
                    'label' => __('Category'),
                    'tag' => 'select',
					'extra' => 'multiple="true" style="width:180px" size="7"',
					'fill_type' => 'multiple',
                    'obj_name' => 'ArticleCategory',
                    'func_name' => 'getCategoryArray'
                )
            )
        ),
        'recentshort' => array(
            'name' => __('Recent Article Intro'), 
            'description' => '', 
            'parameters' => array(
                array(
                    'id' => 'article_reclst_size', 
                    'label' => __('List Size'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput"'
                ),
                array(
                    'id' => 'article_category_list[]',
                    'label' => __('Category'),
                    'tag' => 'select',
					'extra' => 'multiple="true" style="width:180px" size="7"',
					'fill_type' => 'multiple',
                    'obj_name' => 'ArticleCategory',
                    'func_name' => 'getCategoryArray'
                )
            )
        )
    ),
    // for bulletin
    'mod_bulletin' => array(
        'recentbulletins' => array(
            'name' => __('Site Bulletin'),
            'description' => '',
            'parameters' => array(
				array(
                    'id' => 'bulletin_type', 
                    'label' => __('Is rolling'), 
                    'tag' => 'input',
    				'type' => 'checkbox',
    				'value' => '1',
    				'extra' => ''
                ),
            	array(
                    'id' => 'bulletin_speed', 
                    'label' => __('Marquee Speed'), 
                    'tag' => 'input', 
                    'type' => 'text',
                    'value' => '40',
                    'extra' => 'class="txtinput"',
                    
                    /*'fill_type' => 'objfunc',
                    'obj_name' => 'Marquee',
                    'func_name' => 'getSpeedArray'*/
                )
            )
        )
    ),
    'mod_friendlink' => array(
        'friendlink' => array(
            'name' => __('Friend Links'),
            'description' => '',
            'parameters' => array(
                array(
                    'id' => 'friendlink_size', 
                    'label' => __('List Size'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput"'
                ),
				array(
					'id' => 'fl_type',
					'label' => __('FriendLinks Type'),
					'tag' => 'select',
					'fill_type' => 'objfunc',
					'obj_name' => 'Friendlink',
					'func_name' => 'getFLType'
				)
            )
        )
    ),
    'mod_product' => array(
    	'newprd' => array(
    		'name' => __('New Products'),
    		'description' => '',
    		'parameters' => array(
                array(
                    'id' => 'prd_newlst_size', 
                    'label' => __('Product Number'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput"'
                ),
                array(
                    'id' => 'product_category_list[]',
                    'label' => __('Category'),
                    'tag' => 'select',
					'extra' => 'multiple="true" style="width:180px" size="7"',
					'fill_type' => 'multiple',
                    'obj_name' => 'ProductCategory',
                    'func_name' => 'getCategoryArray'
                ),
                array(
                    'id' => 'prd_newlst_d', 
                    'label' => __('Display Cols'), 
                    'tag' => 'input',
					'type' => 'text',
                    'extra' => 'class="txtinput" size ="3"'
                ),
                array(
                    'id' => 'prd_newlst_price', 
                    'label' => __('Display Price'), 
                    'tag' => 'input',
    				'type' => 'checkbox',
    				'value' => '1',
    				'extra' => ''
                ),
				array(
                    'id' => 'prd_newlst_price2', 
                    'label' => __('Display Discount Price'), 
                    'tag' => 'input',
    				'type' => 'checkbox',
    				'value' => '1',
    				'extra' => ''
                ),
				array(
					'id'=>'prd_newlst_cate',
					'label'=>__('Display Category'),
					'tag'=>'input',
					'type'=>'checkbox',
					'value'=>'1',
					'extra'=>''
				)
    		)
    	),
    	'recmndprd' => array(
    		'name' => __('Recommended Products'),
    		'description' => '',
    		'parameters' => array(
                array(
                    'id' => 'prd_recmndlst_size', 
                    'label' => __('Product Number'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput"'
                ),
                array(
                    'id' => 'product_category_list[]',
                    'label' => __('Category'),
                    'tag' => 'select',
					'extra' => 'multiple="true" style="width:180px" size="7"',
					'fill_type' => 'multiple',
                    'obj_name' => 'ProductCategory',
                    'func_name' => 'getCategoryArray'
                ),
                array(
                    'id' => 'prd_recmndlst_d', 
                    'label' => __('Display Cols'), 
                    'tag' => 'input', 
					'type' => 'text', 
                    'extra' => 'class="txtinput" size ="3"'
                ),
                array(
                    'id' => 'prd_recmndlst_price', 
                    'label' => __('Display Price'), 
                    'tag' => 'input',
    				'type' => 'checkbox',
    				'value' => '1',
    				'extra' => ''
                ),
				array(
                    'id' => 'prd_recmndlst_price2', 
                    'label' => __('Display Discount Price'), 
                    'tag' => 'input',
    				'type' => 'checkbox',
    				'value' => '1',
    				'extra' => ''
                ),
                array(
					'id'=>'prd_newlst_cate',
					'label'=>__('Display Category'),
					'tag'=>'input',
					'type'=>'checkbox',
					'value'=>'1',
					'extra'=>''
				)
    		)
    	)
    ),
    'mod_qq' => array(
    	'qqlist' => array(
    		'name' => __('Instant Message'),
    		'description' => '',
    		'parameters' => array(
    			array(
	    			'id' => 'qq_show_account',
	    			'label' => __('Show Account'),
	    			'tag' => 'input',
	    			'type' => 'checkbox',
	    			'value' => '1',
	    			'extra' => ''
    			),
    			array(
    				'id' => 'qq_show_name',
    				'label' => __('Show QQ Name'),
    				'tag' => 'input',
    				'type' => 'checkbox',
    				'value' => '1',
    				'extra' => ''
    			),
    			array(
    				'id' => 'qq_show_type',
    				'label' => __('Choose QQ float type'),
    				'tag' => 'input',
    				'type' => 'floatType',
    				'extra' => ''
    			),
    			
    		)
    	)
    ),
    'mod_media' => array(
    	'show_image' => array(
    		'name' => __('Image'),
    		'description' => '',
    		'parameters' => array(
                array(
                    'id' => 'img_src', 
                    'label' => __('Select Image'), 
                    'tag' => 'imgpicker', 
                    'extra' => 'class="txtinput"'
                ),
        		array(
                    'id' => 'img_url', 
                    'label' => __('Image Url'), 
                    'tag' => 'imgurl',
        			'type' => 'text',
                    'extra' => 'class="txtinput"'
                ),
                array(
                    'id' => 'img_desc', 
                    'label' => __('Description'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="28"'
                ),
                array(
                    'id' => 'img_open', 
                    'label' => __('open image in window from self or blank'), 
                    'tag' => 'input', 
                    'type' => 'img_open', 
                    'extra' => 'class="txtinput" size="4"'
                ),
                array(
                    'id' => 'img_width', 
                    'label' => __('Width'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                ),
                
                array(
                    'id' => 'img_height', 
                    'label' => __('Height'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                )
    		)
    	),
    	'show_flash' => array(
    		'name' => __('Flash'),
    		'description' => '',
    		'parameters' => array(
                array(
                    'id' => 'flv_src', 
                    'label' => __('Select Flash'), 
                    'tag' => 'flvpicker', 
                    'extra' => 'class="txtinput"'
                ),
                 array(
                    'id' => 'flv_width', 
                    'label' => __('Width'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                ),
                array(
                    'id' => 'flv_height', 
                    'label' => __('Height'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                )
    		)
    	),
    	// sitestarv1.3 09/09/2010
    	'flash_slide' => array(
    		'name' => __('Flash slide show'),
    		'description' => '',
    		'parameters' => array(
                array(
                    'id' => 'slide_img_src1', 
                    'label' => __('Select Image'), 
                    'tag' => 'slide_imgpicker', 
                    'extra' => 'class="txtinput"'
                ),
            	array(
                    'id' => 'slide_img_order1', 
                    'label' => __('Display order'), 
                    'tag' => 'slide_input', 
            		'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                ),
                array(
                    'id' => 'slide_img_uri1', 
                    'label' => __('Image Url'), 
                    'tag' => 'slide_input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="28"'
                ),
                array(
                    'id' => 'slide_img_desc1', 
                    'label' => __('Description'), 
                    'tag' => 'slide_input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="28"'
                ),
                array(
                    'id' => 'slide_img_open', 
                    'label' => __('open image in window from self or blank'), 
                    'tag' => 'input', 
                    'type' => 'slide_img_open', 
                    'extra' => 'class="txtinput" size="4"'
                ),
                array(
                    'id' => 'slide_img_width', 
                    'label' => __('Flash slide width'), 
                    'tag' => 'slide_input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                ),
                array(
                    'id' => 'slide_img_height', 
                    'label' => __('Flash slide height'), 
                    'tag' => 'slide_input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput" size="4"'
                )
    		)
    	)
    ),
	/* update zhangjc 2009-10-14
    'mod_menu' => array(
        'leftmenu' => array(
            'name' => __('Vertical Menu'),
            'description' => '',
            'parameters' => array(
                array(
                    'id' => 'menuid', 
                    'label' => __('MenuList'), 
                    'tag' => 'select', 
                    'extra' => '',
                    'fill_type' => 'objfunc',
                    'obj_name' => 'Menu',
                    'func_name' => 'getMenu'
                )
            )
        ),
        'topmenu' => array (
            'name' => __('Horizontal Menu'),
            'description' => '',
            'parameters' => array(
                array(
                    'id' => 'menuid', 
                    'label' => __('MenuList'), 
                    'tag' => 'select', 
                    'extra' => '',
                    'fill_type' => 'objfunc',
                    'obj_name' => 'Menu',
                    'func_name' => 'getMenu'
                )
            )
        )
    ),
	
	'mod_counter' => array(
        'counter' => array(
            'name' => __('Site Counter'), 
            'description' => '', 
            'parameters' => array(
			array(
                    'id' => 'counter_title', 
                    'label' => __('Counter Title'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => ''
                )
            )
        )
    ),
	*/
	'mod_cart' => array(
        'cartstatus' => array(
            'name' => __('Shopping Cart'), 
            'description' => '', 
            'parameters' => array(
            )
        )
    ),
    'mod_marquee' => array(
        'marquee' => array(
            'name' => __('Mod Marquee'),
            'description' => '',
            'parameters' => array(
				/*array(
                    'id' => 'marquee_width', 
                    'label' => __('Marquee Width'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'size=4'
                ),
				*/
                array(
                    'id' => 'marquee_speed', 
                    'label' => __('Marquee Speed'), 
                    'tag' => 'select', 
                    'extra' => '',
                    'fill_type' => 'objfunc',
                    'obj_name' => 'Marquee',
                    'func_name' => 'getSpeedArray'
                ),
				array(
                    'id' => 'marquee_class', 
                    'label' => __('Class'), 
                    'tag' => 'select', 
                    'extra' => ' onchange="chgmultiple(this.options[this.selectedIndex].value)"',
                    'fill_type' => 'objfunc',
                    'obj_name' => 'Marquee',
                    'func_name' => 'getClassArray'
                ),
				array(
                    'id' => 'mar_direc_id', 
                    'label' => __('Direction'), 
                    'tag' => 'select', 
                    'extra' => '',
                    'fill_type' => 'objfunc',
                    'obj_name' => 'Marquee',
                    'func_name' => 'getDirectionArray'
                ),
				array(
                    'id' => 'marquee_data', 
                    'label' => __('Marquee Data'), 
                   'tag' => 'input',
	    			'type' => 'checkbox2',
	    			'value' => '1',
	    			'extra' => '',
					'page'=>array('marquee_data1','marquee_data2','marquee_data3')
                ),
				array(
                    'id' => 'mar_prd_id[]', 
                    'label' => __('Category'), 
                    'tag' => 'select', 
                    'extra' => 'multiple="true" style="width:180px" size="5"',
                    'fill_type' => 'multiple',
                    'obj_name' => 'ProductCategory',
                    'func_name' => 'getModArray',
					'cids'=>''
                ),
				/*array(
                    'id' => 'mar_article_id[]', 
                    'label' => __('Category'), 
                    'tag' => 'select', 
                    'extra' => 'multiple="true" style="width:180px" size="5"',
                    'fill_type' => 'multiple',
                    'obj_name' => 'ArticleCategory',
                    'func_name' => 'getModArray',
					'cids'=>2
                )*/
            )
        )
    ),
    'mod_download' => array(
        'recentdownloads' => array(
            'name' => __('Recent Downloads'),
            'description' => '',
            'parameters' => array(
                array(
                    'id' => 'down_reclst_size', 
                    'label' => __('List Size'), 
                    'tag' => 'input', 
                    'type' => 'text', 
                    'extra' => 'class="txtinput"'
                ),
              array(
                    'id' => 'download_category_list[]',
                    'label' => __('Category'),
                    'tag' => 'select',
					'extra' => 'multiple="true" style="width:180px" size="7"',
					'fill_type' => 'multiple',
                    'obj_name' => 'DownloadCategory',
                    'func_name' => 'getCategoryArray'
                )
            )
        )
    ),
    /*'mod_search' => array(
        'show_search' => array(
            'name' => __('Search'),
            'description' => '',
            'parameters' => array(
              array(
                    'id' => 'searchTo', 
                    'label' => __('Select To'), 
                    'tag' => 'select', 
                    'fill_type' => 'objfunc',
                    'obj_name' => 'ModSearch',
                    'func_name' => 'getModArray',
                )
            )
        )
    )*/
);
?>