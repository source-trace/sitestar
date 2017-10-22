<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModAttachment extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
        $this->_layout = 'content';
        
        $mode =& ParamHolder::get('mode');
        $default_watermark_image = 'images/watermark.png';
        $site_param =& ParamHolder::get('sparam', array());
        $png =& ParamHolder::get('WATERMARK_PNG', array(), PS_FILES);
        
        if( isset($mode) && !empty($mode) )
        {
        	$o_param = new Parameter();
        	
        	switch( $mode ) {
        		case 'submit':
			        if( isset($png['name']) && !empty($png['name']) ) 
			        {	
						if(!strpos($png['name'],'.png')){die("<script type='text/javascript'>alert('".__('Please upload a PNG image')."');parent.window.location.reload();</script>");}
			        	$png['name'] = iconv("UTF-8", "gb2312", $png['name']);
						if (!preg_match ("/^[a-z0-9_\.]*$/i", $png['name'])) {
							Notice::set('mod_attachment/msg', __('Watemark image upload failed!'));
							Content::redirect(Html::uriquery('mod_attachment', 'admin_list'));
						}
			        	if( !move_uploaded_file($png['tmp_name'], ROOT.'/admin/images/'.$png['name']) ) {
			        		Notice::set('mod_attachment/msg', __('Watemark image upload failed!'));
		            		Content::redirect(Html::uriquery('mod_attachment', 'admin_list'));
			        	} else {
			        		ParamParser::fire_virus(ROOT.'/admin/images/'.$png['name']);
			        		$site_param['WATERMARK_PNG'] = 'images/'.$png['name'];
			        	}
			        } else {
			        	//$site_param['WATERMARK_PNG'] = $default_watermark_image;
			        }
	        		break;
	        	case 'restore':
	        		$site_param['WATERMARK_PNG'] = $default_watermark_image;
	        		break;
	        }
	        
	        // exec sql
	    	foreach ($site_param as $key => $val) {
	    	    $param =& $o_param->find('`key`=?', array($key));
	    	    if ($param) {
	    	        $param->val = $val;
	    	        $param->save();
	    	    }
	    	}
	    	// write inc_mark_config
	    	$ismark = ($site_param['WATERMARK_STATUS'] == '0') ? 0 : 1;
	    	$isthumb = ($site_param['THUMB_STATUS'] == '0') ? 0 : 1;
	    	$fntclor = $this->hColor2RGB($site_param['WATERMARK_TEXT_COLOR']);
	    	$shadowclor = $this->hColor2RGB($site_param['WATERMARK_TEXT_SHADOW_COLOR']);
	    	
			if(empty($site_param['WATERMARK_PNG'])){
				$site_param['WATERMARK_PNG'] = WATERMARK_PNG;
			}
	    	$config = "<?php\n\$photo_markup = '$ismark';\n\$photo_markdown = '1';\n\$photo_marktype = '".$site_param['WATERMARK_TYPE']."';\n\$photo_wwidth = '".$site_param['WATERMARK_MIN_WIDTH']."';\n\$photo_wheight = '".$site_param['WATERMARK_MIN_HEIGHT']."';\n\$photo_waterpos = '".$site_param['WATERMARK_STATUS']."';\n\$photo_watertext = '".$site_param['WATERMARK_TEXT']."';\n\$photo_fontsize = '".$site_param['WATERMARK_TEXT_SIZE']."';\n\$photo_fontcolor = '".$fntclor['r'].",".$fntclor['g'].",".$fntclor['b']."';\n\$photo_marktrans = '".$site_param['WATERMARK_QUALITY']."';\n\$photo_diaphaneity = '100';\n\$photo_markimg = '".str_replace('images/','',$site_param['WATERMARK_PNG'])."';\n\$photo_thumb = '$isthumb';\n\$photo_twidth = '".$site_param['THUMB_WIDTH']."';\n\$photo_theight = '".$site_param['THUMB_HEIGHT']."';\n\$photo_thumbtrans = '".$site_param['THUMB_QUALITY']."';\n\$photo_angle = '".$site_param['WATERMARK_TEXT_ANGLE']."';\n\$photo_shadowx = '".$site_param['WATERMARK_TEXT_SHADOWX']."';\n\$photo_shadowy = '".$site_param['WATERMARK_TEXT_SHADOWY']."';\n\$photo_shadowcolor = '".$shadowclor['r'].",".$shadowclor['g'].",".$shadowclor['b']."';\n?>";
	    	
	    	$fhd = @fopen(ROOT."/data/inc_mark_config.php", "wb");
	    	@fwrite($fhd, $config);
	    	@fclose($fhd);
	    	die("<script type='text/javascript'>parent.window.location.reload();</script>");//刷新父窗口，关闭当前弹出框 hfh
	    	//Content::redirect(Html::uriquery('mod_attachment', 'admin_list'));
        }
        
        $this->assign('default_watermark_image', $default_watermark_image);
    }
    
    public function watermark_preview() {
    	$this->_layout = 'blank';
    }
    
    private function hColor2RGB($hexColor) {
	    $color = str_replace('#', '', $hexColor);
	    if (strlen($color) > 3) {
	        $rgb = array(
	            'r' => hexdec(substr($color, 0, 2)),
	            'g' => hexdec(substr($color, 2, 2)),
	            'b' => hexdec(substr($color, 4, 2))
	        );
	     } else {
	        $r = substr($color, 0, 1) . substr($color, 0, 1);
	        $g = substr($color, 1, 1) . substr($color, 1, 1);
	        $b = substr($color, 2, 1) . substr($color, 2, 1);
	        $rgb = array(
	            'r' => hexdec($r),
	            'g' => hexdec($g),
	            'b' => hexdec($b)
	        );
	    }
	     return $rgb;
	}

}
?>