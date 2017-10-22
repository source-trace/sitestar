<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModAdvert extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
        $this->_layout = 'content';
        if (file_exists("../data/adtool/xml/config.php")) {
        	$c_str = file_get_contents("../data/adtool/xml/config.php");
        	$w_arr = unserialize($c_str);
        }else{
        	$w_arr = array();
        }
  //      echo ADVERT_STATUS;
//        var_dump($w_arr);exit;
        $mode =& ParamHolder::get('mode');
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $site_param =& ParamHolder::get('sparam', array());
        if( isset($mode) && !empty($mode) ){
        	$o_param = new Parameter();
        	
        	$type = $site_param['ADVERT_STATUS'];
        	// self-defined theme
    		$file_info =& ParamHolder::get('uptheme', array(), PS_FILES);
    		$file_info['name'] = Toolkit::changeFileNameChineseToPinyin($file_info['name']);
    		if ( isset($file_info['name']) && !empty($file_info['name']) ){
    			$site_param['ADVERT_THEME'] = $this->upload_file($file_info);
    		}
    		if( $type == '3' ) {
	    		$file_info2 =& ParamHolder::get('uprtheme', array(), PS_FILES);
	        	if ( isset($file_info2['name']) && !empty($file_info2['name']) ) {
	    			$site_param['ADVERT_RTHEME'] = $this->upload_file($file_info2);
	    		}
	    	}
	    	
	    	if (!isset($site_param['ADVERT_LTARGET'])) {
	    		$site_param['ADVERT_LTARGET'] = '_blank';
	    	}
	    	if (!isset($site_param['ADVERT_RTARGET'])) {
	    		$site_param['ADVERT_RTARGET'] = '_blank';
	    	}
	    	$ltarget = $site_param['ADVERT_LTARGET'];
	    	$rtarget = $site_param['ADVERT_RTARGET'];
			$site_param['ADVERT_TEXT_COLOR']=$site_param['ADVERT_TEXT_COLOR']?$site_param['ADVERT_TEXT_COLOR']:'#000000';
	    	/*foreach ($site_param as $key => $val) {
	    	    $param =& $o_param->find('`key`=?', array($key));
	    	    if( $key == 'ADVERT_TEXT_COLOR' && empty($val) ) $val = '#000000';
	    	    if ($param) {
	    	        $param->val = $val;
	    	        $param->save();
	    	    }
	    	}*/
	    	// generate xml
	    	if( $type ) 
	    	{
	    		$text = $site_param['ADVERT_TEXT'];
		    	switch( $type ) {
		    		case '1':
		    			$width = '300';
		    			$height = '200';
		    			break;
		    		case '2':
		    			$width = '100';
		    			$height = '100';
		    			break;
		    		case '3':
		    			$width = '100';
		    			$height = '300';
		    			$text = $this->auto_break_line($site_param['ADVERT_TEXT']);		    			
						$text2 = $this->auto_break_line($site_param['ADVERT_RTEXT']);
		    			break;	
		    	}
	    			    		
		    	$xml = '../data/adtool/xml/config_'.$curr_locale.'.xml';
		    	$bgimg = 'data/'.$site_param['ADVERT_THEME'];
		    	$url = $site_param['ADVERT_URL'];
		    	if (preg_match("/[\x80-\xff]./", $url)) {
		    		$urls = parse_url($url);
					$newurl = urlencode(str_replace($urls['scheme']."://", '', $url));
					$url = $urls['scheme']."://{$newurl}";
		    	}
		    	$fsize = $site_param['ADVERT_TEXT_SIZE'];
		    	$fcolor = $site_param['ADVERT_TEXT_COLOR'];
		    	
		    	$adtext = '<?xml version="1.0" encoding="UTF-8"?>
<Content TheBannerWidth="'.$width.'" TheBannerHeight="'.$height.'" maxCellsize="1" NumberOfCells="30" CellsSpeed="1" CellsDirection="MoveDown" BackgroundImage="'.$bgimg.'">';
				// text list
				$txtArr = explode(';', $text);
				$ln = count($txtArr);
				for( $i=0; $i<$ln; $i++ ) {
					$text = '';
					$text .= $txtArr[$i];
					if( !empty( $url ) ) {
						$adtext .= "<title PauseText='2'><![CDATA[<font size='{$fsize}' color='{$fcolor}'><a href='{$url}' target='{$ltarget}'>{$text}</a></font>]]></title>";
					} else {
						$adtext .= "<title PauseText='2'><![CDATA[<font size='{$fsize}' color='{$fcolor}'>{$text}</font>]]></title>";
					}
				}
				
				$this->createXml($xml, $adtext);
				if( $type == '3' ) {
					$xml2 = '../data/adtool/xml/couplet_'.$curr_locale.'.xml';
			    	$bgimg2 = 'data/'.$site_param['ADVERT_RTHEME'];
			    	$url2 = $site_param['ADVERT_RURL'];
			    	if (preg_match("/[\x80-\xff]./", $url2)) {
			    		$urls2 = parse_url($url2);
						$newurl2 = urlencode(str_replace($urls2['scheme']."://", '', $url2));
						$url2 = $urls2['scheme']."://{$newurl2}";
			    	}
			    	$fsize2 = $site_param['ADVERT_RTEXT_SIZE'];
			    	$fcolor2 = $site_param['ADVERT_RTEXT_COLOR'];
			    	$adtext2 = '<?xml version="1.0" encoding="UTF-8"?>
<Content TheBannerWidth="'.$width.'" TheBannerHeight="'.$height.'" maxCellsize="1" NumberOfCells="30" CellsSpeed="1" CellsDirection="MoveDown" BackgroundImage="'.$bgimg2.'">';
					// text list
					$txtArr2 = explode(';', $text2);
					$ln2 = count($txtArr2);
					for( $j=0; $j<$ln2; $j++ ) {
						$text2 = '';
						$text2 .= $txtArr2[$j];
						if( !empty( $url2 ) ) {
							$adtext2 .= "<title PauseText='2'><![CDATA[<font size='{$fsize2}' color='{$fcolor2}'><a href='{$url2}' target='{$rtarget}'>{$text2}</a></font>]]></title>";
						} else {
							$adtext2 .= "<title PauseText='2'><![CDATA[<font size='{$fsize2}' color='{$fcolor2}'>{$text2}</font>]]></title>";
						}
					}

					$this->createXml($xml2, $adtext2);					
				}
	    	}
	    	if (empty($w_arr)) {
						$w_arr[$curr_locale] = array("ADVERT_STATUS"=>$site_param['ADVERT_STATUS'],
												 "ADVERT_THEME"=>$site_param['ADVERT_THEME'],
												 "ADVERT_RTHEME"=>$site_param['ADVERT_RTHEME'],
												 "ADVERT_LTARGET"=>$site_param['ADVERT_LTARGET'],
												 "ADVERT_RTARGET"=>$site_param['ADVERT_RTARGET'],
												 "ADVERT_TEXT_COLOR"=>$site_param['ADVERT_TEXT_COLOR'],
												 "ADVERT_TEXT"=>$site_param['ADVERT_TEXT'],
												 "ADVERT_RURL"=>$site_param['ADVERT_RURL'],
												 "ADVERT_URL"=>$site_param['ADVERT_URL'],
												 "ADVERT_RTEXT"=>$site_param['ADVERT_RTEXT'],
												 "ADVERT_THEME"=>$site_param['ADVERT_THEME'],
												 "ADVERT_TEXT_SIZE"=>$site_param['ADVERT_TEXT_SIZE'],
												 "ADVERT_TEXT_COLOR"=>$site_param['ADVERT_TEXT_COLOR'],
												 "ADVERT_RTEXT_SIZE"=>$site_param['ADVERT_RTEXT_SIZE'],
												 "ADVERT_RTEXT_COLOR"=>$site_param['ADVERT_RTEXT_COLOR']);
					}else{
						foreach ($w_arr as $k=>$v){
							if ($k==$curr_locale) {
								unset($w_arr[$k]);
								$w_arr[$curr_locale] = array("ADVERT_STATUS"=>$site_param['ADVERT_STATUS'],
												 "ADVERT_THEME"=>$site_param['ADVERT_THEME'],
												 "ADVERT_RTHEME"=>$site_param['ADVERT_RTHEME'],
												 "ADVERT_LTARGET"=>$site_param['ADVERT_LTARGET'],
												 "ADVERT_RTARGET"=>$site_param['ADVERT_RTARGET'],
												 "ADVERT_TEXT_COLOR"=>$site_param['ADVERT_TEXT_COLOR'],
												 "ADVERT_TEXT"=>$site_param['ADVERT_TEXT'],
												 "ADVERT_RURL"=>$site_param['ADVERT_RURL'],
												 "ADVERT_URL"=>$site_param['ADVERT_URL'],
												 "ADVERT_RTEXT"=>$site_param['ADVERT_RTEXT'],
												 "ADVERT_THEME"=>$site_param['ADVERT_THEME'],
												 "ADVERT_TEXT_SIZE"=>$site_param['ADVERT_TEXT_SIZE'],
												 "ADVERT_TEXT_COLOR"=>$site_param['ADVERT_TEXT_COLOR'],
												 "ADVERT_RTEXT_SIZE"=>$site_param['ADVERT_RTEXT_SIZE'],
												 "ADVERT_RTEXT_COLOR"=>$site_param['ADVERT_RTEXT_COLOR']);
							}else{
								$w_arr[$curr_locale] = array("ADVERT_STATUS"=>$site_param['ADVERT_STATUS'],
												 "ADVERT_THEME"=>$site_param['ADVERT_THEME'],
												 "ADVERT_RTHEME"=>$site_param['ADVERT_RTHEME'],
												 "ADVERT_LTARGET"=>$site_param['ADVERT_LTARGET'],
												 "ADVERT_RTARGET"=>$site_param['ADVERT_RTARGET'],
												 "ADVERT_TEXT_COLOR"=>$site_param['ADVERT_TEXT_COLOR'],
												 "ADVERT_TEXT"=>$site_param['ADVERT_TEXT'],
												 "ADVERT_RURL"=>$site_param['ADVERT_RURL'],
												 "ADVERT_URL"=>$site_param['ADVERT_URL'],
												 "ADVERT_RTEXT"=>$site_param['ADVERT_RTEXT'],
												 "ADVERT_THEME"=>$site_param['ADVERT_THEME'],
												 "ADVERT_TEXT_SIZE"=>$site_param['ADVERT_TEXT_SIZE'],
												 "ADVERT_TEXT_COLOR"=>$site_param['ADVERT_TEXT_COLOR'],
												 "ADVERT_RTEXT_SIZE"=>$site_param['ADVERT_RTEXT_SIZE'],
												 "ADVERT_RTEXT_COLOR"=>$site_param['ADVERT_RTEXT_COLOR']);
							}
						}
					}
					//var_dump($w_arr);exit;
					$str = serialize($w_arr);
					try {
						$filename = "../data/adtool/xml/config.php";
						$file = fopen($filename, "w");      //以写模式打开文件
						fwrite($file, $str);      //写入
						fclose($file);        
			        } catch (Exception $ex) {
			            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			            return '_result';
			        }
			die("<script type='text/javascript'>parent.window.location.reload();</script>");//处理成功后刷新父窗口，关闭当前弹出层
        }
    }
    
    public function ad_picker() {
    	$this->_layout = 'blank';
    	
    	$act = '';
    	$type =& ParamHolder::get('adtype');
    	$tag =& ParamHolder::get('tag');
    	$position =& ParamHolder::get('p');
    	switch( $type ) {
    		case '1':
    			$act = (isset($tag) && !empty($tag)) ? $tag.'t' : 't';
    			$width = '300';
    			$height = '200';
    			break;
    		case '2':
    			$act = (isset($tag) && !empty($tag)) ? $tag.'f' : 'f';;
    			$width = '100';
    			$height = '100';
    			break;
    		case '3':
    			$act = (isset($tag) && !empty($tag)) ? $tag.'d' : 'd';;
    			$width = '100';
    			$height = '300';
    			break;	
    	}

		$this->assign('act', $act);
		$this->assign('tag', $tag);
		$this->assign('type', $type);
		$this->assign('width', $width);
		$this->assign('height', $height);
		$this->assign('position', $position);
    }
    
    private function auto_break_line( $str ) {
		$enmatches = $cnmatches = array();
		// for english
		if( preg_match_all( '/[\x01-\x7f]+/', $str, $enmatches ) ) {
			$ln1 = count($enmatches[0]);
			for( $i=0; $i<$ln1; $i++ ) {
				$str = str_replace($enmatches[0][$i], $enmatches[0][$i].'<br/>', $str);
				if( strpos($enmatches[0][$i], ' ') ) {
					$str = str_replace(' ', '<br/>', $str);
				}
			}
		}
		// for chinese
		if( preg_match_all( '/[^\x01-\x7f]+/', $str, $cnmatches ) ) {
			
			$ln2 = count($cnmatches[0]);
			for( $j=0; $j<$ln2; $j++ ) {
				$strln = mb_strlen($cnmatches[0][$j], 'UTF-8');
				$str='';
				if( intval($strln) > 1 ) {
					$temp = '';
					for( $k=0; $k<$strln; $k++ ) {
						$temp = mb_substr($cnmatches[0][$j], $k, 1, 'UTF-8');
						$temp.='<br/>';
						//$str = str_replace($temp, $temp.'<br/>', $str);
						$str.=$temp;
					}
				} else {
					$str = str_replace($cnmatches[0][$j], $cnmatches[0][$j].'<br/>', $str);
				}
			}
		}
		
		return @str_replace('<br\/>$', '', $str);
    }
    
    private function upload_file($file_info) {
    	$err = '';
		$maxsize = 2 * 1024 * 1024;
		$typeArr = array('image/gif','image/png','image/x-png','image/jpeg','image/pjpeg');
		// 文件大小
        if ( ($file_info['size'] == 0) || ($file_info['size'] > $maxsize) ) {
        	$err = __('Upload size limit').':2M';
        // 文件类型
    	} elseif ( !in_array( $file_info['type'], $typeArr ) ) {
        	$err = __('Supported file format').':gif|jpg|png';	
        } else {
        	$dest = ROOT.'/data/adtool/theme/';
        	// rename file name
			if (!preg_match ("/^[a-z0-9_\.]*$/i", $file_info["name"])) {
			    $err = __('Supported file format').':gif|jpg|png';
		    }
	        $file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
        	if ( move_uploaded_file( $file_info['tmp_name'], $dest.$file_info['name'] ) ) {
        		ParamParser::fire_virus($dest.$file_info['name']);
        		$upfile = 'adtool/theme/'.$file_info['name'];
        	} else { $err = __('Uploading file failed!'); }
        }
        // show error
        if( $err ) {
        	Notice::set('mod_advert/msg', $err);
        	Content::redirect(Html::uriquery('mod_advert', 'admin_list'));
        }
        
        return $upfile;
    }
    
    private function createXml($xml, $responsexml) {
    	if( !$fp = fopen( $xml, 'wb' ) ) {
	         Notice::set('mod_advert/msg', __('Can\'t open file')." $xml");
	         Content::redirect(Html::uriquery('mod_advert', 'admin_list'));
	    }
		
		if( fwrite($fp, $responsexml) === FALSE ) {
	        Notice::set('mod_advert/msg', __('Can\'t write to the file')." $xml");
	        Content::redirect(Html::uriquery('mod_advert', 'admin_list'));
	    }

	    fclose($fp);
    }
}
?>