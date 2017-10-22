<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Class for pager generation
 *
 * @package tool
 */
class Pager {
	
	public static $mod;
	public static $act;
	public static $extUrl;
	public static $status; // 伪静态下“添加页面”模块分页用
    /**
     * Generate record data by page based on object
     *
     * @access public
     * @static
     * @param string $object_name The object used for representing a record
     * @param string $where The WHERE SQL used for filtering records
     * @param array $params Parameters used for replacing place holders in the WHERE SQL
     * @param string $more_sql Additional SQL conditions to sort, group or limit records selection
     * @param string $page_param The parameter name for identifying current page number
     */
    public static function &pageByObject_old($object_name, $where = false,
        $params = false, $more_sql = false, $page_param = 'p') {
        $result = array('pager' => false, 'data' => false);

        try {
            $obj = new $object_name();
            $obj_cnt = $obj->count($where, $params);
            $curr_page =& ParamHolder::get($page_param, 1);
            $result['pager'] =& self::_genPagerLinks($curr_page, $obj_cnt, $page_param);

            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            if ($more_sql == false) {
                $more_sql = '';
            }
            $more_sql .= " LIMIT ".$start_index.", ".PAGE_SIZE;
            $result['data'] =& $obj->findAll($where, $params, $more_sql);
        } catch (RecordException $ex) {
            throw new Exception($ex->getMessage());
        }
		echo $result;
        return $result;
    }
    
    /**
     * 基于文本分页，用于文章，新闻等分页使用
     * @author: renzhen
     * @since 2011-04-21
     * @access public
     * @static
     */
    public static function &pageByText($content,$urlparams=array(), $splitstr='',$page_param = 'p'){
          $defaultparam=array('_m'=>R_MOD,'_a'=>R_ACT);
          $defaultsplitstr='#p#分页符#e#';
          $urlparams=array_merge($defaultparam, $urlparams);
          $exturl= $urlparams;
          
          unset( $exturl['_m']);
          unset($exturl['_a']);
          if(empty($splitstr)) $splitstr=$defaultsplitstr;
          $pagecontents=explode($splitstr,$content);
          $totalpagenums=count($pagecontents);
          if(MOD_REWRITE=='3'){
          		$_path=strtolower(ParamHolder::get('_path', ''));
            	if (strrpos($_path,'p-')) {
            		$_path=strtolower(ParamHolder::get('_path', ''));
            		$curr_page = substr($_path,strrpos($_path,'-')+1);
            	}else{//系统分页设置，
		        	$arr = explode("-",$_path);
		        	$curr_page=preg_replace('/[a-zA-Z_]+/','',$arr[0]);
            	}
          }else{
          		$curr_page =& ParamHolder::get($page_param, 1);
          }
          if(!preg_match('/^\d+$/',$curr_page)||intval($curr_page)==0){
              $curr_page =1;
          }elseif(intval($curr_page)>$totalpagenums){
              $curr_page =$totalpagenums;
          }
          $splitcontent=$pagecontents[$curr_page-1];
          $result=array('data'=>$splitcontent,'mod'=>$urlparams['_m'],'cur_page'=>$curr_page,
                       'act'=>$urlparams['_a'],'extUrl'=>$exturl,'total'=>$totalpagenums);
          return $result;
          
    }


    /**
     * 
     */
    public  static function &pageByObject($object_name, $where = false,$params = false, $more_sql = false, $page_param = 'p', $type = '',$is_count=''){
        $result = array('pager' => false, 'data' => false);
        // 伪静态下“添加页面”模块分页用
        Pager::$status = $type;

        try {
            $obj = new $object_name();
            if (!empty($is_count)) {//传过来的总条数，已发送短信和邮件分页使用
            	$obj_cnt = $is_count;
            }else{
            	$obj_cnt = $obj->count($where, $params);
            }

            $curr_page =& ParamHolder::get('_m', '');
            //伪静态3模式的分页处理
            if(MOD_REWRITE=='3' && empty($curr_page)){
            	$_path=strtolower(ParamHolder::get('_path', ''));
            	if (strrpos($_path,'p-')) {//用户自定义URL分页
            		$_path=strtolower(ParamHolder::get('_path', ''));
            		if(strrpos($_path,'_v-preview')) $_path = str_ireplace('-_v-preview','',$_path);//xqf 2013.12
            		$curr_page = substr($_path,strrpos($_path,'-')+1);
            	}else{//系统分页设置，
		        	$arr = explode("-",$_path);
		        	$curr_page=preg_replace('/[a-zA-Z_]+/','',$arr[0]);
            	}
            }else{
            	$curr_page =& ParamHolder::get($page_param, 1);
            }
			$curr_page = intval($curr_page);
			if(empty($curr_page) || $curr_page < 1){
	        		$curr_page=1;
	        }
            $result['pager'] =& self::_genPagerLinks($curr_page, $obj_cnt, $page_param,5);
			
            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            if ($more_sql == false) {
                $more_sql = '';
            }
            $more_sql .= " LIMIT ".$start_index.", ".PAGE_SIZE;
            $result['data'] =& $obj->findAll($where, $params, $more_sql);
            $result['mod'] = Pager::$mod;
            $result['act'] = Pager::$act;
            $result['extUrl'] = Pager::$extUrl;
        } catch (RecordException $ex) {
            throw new Exception($ex->getMessage());
        }

        return $result;
    	
    }
    /**
     * Generate record data by page based on given SQL
     *
     * @access public
     * @static
     * @param string $sql The main SQL for query records
     * @param array $params Parameters used for replacing place holders in the SQL
     * @param string $more_sql Additional SQL conditions to sort, group or limit records selection
     * @param string $page_param The parameter name for identifying current page number
     */
    public static function &pageBySql($sql, $params = false,
        $more_sql = false, $page_param = 'p') {
        $db =& MysqlConnection::get();
        $result = array('pager' => false, 'data' => false);

        try {
            // get total record num
            $sql_part = spliti('from', $sql);
            $count_sql = "SELECT COUNT(*) FROM".$sql_part[1];
            $rs =& $db->query($count_sql, $params);
            $row =& $rs->fetchRow(MYSQL_NUM);
            $rec_cnt = $row[0];
            $rs->free();

            // get current page
            $curr_page =& ParamHolder::get($page_param, 1);
            $result['pager'] =& self::_genPagerLinks($curr_page, $rec_cnt, $page_param,5);

            // get start index
            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            // generate sql
            if ($more_sql !== false) {
                $sql .= " ".$more_sql;
            }
            $sql .= " LIMIT ".$start_index.", ".PAGE_SIZE;
            // get objects
            $rs_o =& $db->query($sql, $params);
            $result['data'] =& $rs_o->fetchObjects();
            $rs_o->free();
        } catch (MysqlException $ex) {
            throw new Exception('Failed loading records!'."\n"
                                .$ex->getMessage());
        }

        return $result;
    }

    /**
     * Generate record data by page based on given SQL
     * and with a custom SQL for counting record number
     *
     * @access public
     * @static
     * @param string $count_sql The SQL for counting record number
     * @param array $count_params Parameters used for replacing place holders in the COUNT SQL
     * @param string $sql The main SQL for query records
     * @param array $params Parameters used for replacing place holders in the SQL
     * @param string $more_sql Additional SQL conditions to sort, group or limit records selection
     * @param string $page_param The parameter name for identifying current page number
     */
    public static function &pageBySql_wCount($count_sql, $count_params = false,
        $sql, $params = false, $more_sql = false, $page_param = 'p') {
        $db =& MysqlConnection::get();
        $result = array('pager' => false, 'data' => false);

        try {
            // get total record num
            $rs =& $db->query($count_sql, $count_params);
            $row =& $rs->fetchRow(MYSQL_NUM);
            $rec_cnt = $row[0];
            $rs->free();

            // get current page
            $curr_page =& ParamHolder::get($page_param, 1);
            $result['pager'] =& self::_genPagerLinks($curr_page, $rec_cnt, $page_param);

            // get start index
            $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
            // generate sql
            if ($more_sql !== false) {
                $sql .= " ".$more_sql;
            }
            $sql .= " LIMIT ".$start_index.", ".PAGE_SIZE;
            // get objects
            $rs_o =& $db->query($sql, $params);
            $result['data'] =& $rs_o->fetchObjects();
            $rs_o->free();
        } catch (MysqlException $ex) {
            throw new Exception('Failed loading records!'."\n"
                                .$ex->getMessage());
        }

        return $result;
    }

    /**
     * Generate URL for page link
     *
     * @access private
     * @static
     * @param int $curr_page The current page
     * @param int $rec_cnt Total record number of the selection
     * @param string $page_param The parameter name for identifying current page number
     */
    private static function &_genPagerLinks($curr_page, $rec_cnt, $page_param,$show_num) {
        $pager = false;
		$page_list='';
        // get total page num
        $pages = ceil($rec_cnt / intval(PAGE_SIZE));

        // correct page
        if ($pages < 1) $pages = 1;
        if ($curr_page > $pages) $curr_page = $pages;
        if ($curr_page < 1) $curr_page = 1;
        // get all pages
        $prev_page = ($curr_page == 1)?1:$curr_page - 1;
        $next_page = ($curr_page == $pages)?$pages:$curr_page + 1;
        
        // set pages
        $pager['first'] = self::_genPagerUri(1, $page_param);
        $page_list .= $curr_page==1 ? '&nbsp;'.__("First").'&nbsp;' :'<a href="'.$pager['first'].'" class="page_word">'.__("First").'</a>&nbsp;';
        $pager['prev'] = self::_genPagerUri($prev_page, $page_param);
		$page_list .= $curr_page==1 ? '&nbsp;'.__("Previous").'&nbsp;' :'&nbsp;<a href="'.$pager['prev'].'" class="page_word" >'.__("Previous").'</a>&nbsp;';
		
		$startPage = $curr_page-ceil($show_num/2)+1 >0 ? ($curr_page-ceil($show_num/2))+1:1 ;
		$endPage = $startPage + $show_num ;
		if($endPage > ($pages+1)){
			$endPage = $pages+1;
		}
		if ($startPage+1>=$show_num) {
			$page_list .= '&nbsp;<a href="'.$pager['first'].'" class="page_square" >1</a>...&nbsp;';
		}
	   for($i=$startPage;$i<$endPage;$i++){			  
			if($i==$curr_page){
				$page_list .= '<a href="'.self::_genPagerUri($i, $page_param).'" class="page_square_bg"><font color="white">'.$i.'</font></a>';
			}else{
				$page_list .= '<a href="'.self::_genPagerUri($i, $page_param).'" class="page_square">'.$i.'</a>';
			}
	    }
	    if($endPage < ($pages+1)){
			$page_list .= "...";
			$pager['last'] = self::_genPagerUri($pages, $page_param);
	        $page_list .= '<a href="'.$pager['last'].'"  class="page_square">'.$pages.'</a>';
		} 	
			
	    $pager['next'] = self::_genPagerUri($next_page, $page_param);
	    $page_list .= $pages==$curr_page?'&nbsp;'.__("Next").'&nbsp;':'&nbsp;<a href="'.$pager['next'].'" class="page_word"  >'.__("Next").'</a>&nbsp;';
	        
	    $pager['last'] = self::_genPagerUri($pages, $page_param);
	    $page_list .= $pages==$curr_page?'&nbsp;'.__("Last").'&nbsp;':'&nbsp;<a href="'.$pager['last'].'" class="page_word"  >'.__("Last").'</a>&nbsp;';
		
		$page_list .= '&nbsp;&nbsp;&nbsp;&nbsp;'.__("Skip to").'：';
		$page_list .= Html::input('text', 'p', '','class="pageinput"');
		$page_list .= "&nbsp;".__("pagePage")."&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' class='page_sure' onclick='pageLocation()'>&nbsp;".__("page sure")."&nbsp;</a>";
        $pager['curr'] = $curr_page;
        $pager['total'] = $pages;
		$ret_page_list='';
        if ($pages!=1) {
        	$ret_page_list = $page_list;
        }else{
			$ret_page_list='';
		}
		return $ret_page_list;
    }

    /**
     * Generate URL query string
     *
     * @access private
     * @static
     * @param int $page The targeted page number
     * @param string $page_param The parameter name for identifying current page number
     */
    private static function _genPagerUri($page, $page_param) {
    	$_path=strtolower(ParamHolder::get('_path', ''));
    	if (!empty($_path)) {
    		
    	
    	 if(MOD_REWRITE=='3'){
    	 	$_path=strtolower(ParamHolder::get('_path', ''));
    	 	if(strrpos($_path,'-_v-preview')) $_path = str_ireplace('-_v-preview','',$_path);//存在预览
    	 	if (strrpos($_path,'p-')) {
    	 		$_path = substr($_path,0,strrpos($_path,'p')-1);
    	 	}
    	 	$o_url = new MenuItem();
    	 	$url_res = $o_url->find('url=?',array($_path));
    	 	if ($url_res) {//用户自定义了URL后的分页操作
		    	$uri = $_path.'-'.'p'.'-'.$page.'.html';
		        return $uri;
    	 	}
    	 }
    	 }
        $uri = 'index.php?'.$page_param.'='.$page;
		$extra_o='';
        if (isset($_GET)){
            foreach ($_GET as $key => $value){
                if ($key != $page_param){
                	if (strpos($key,"keyword")) {
                		$uri .= '&amp;'.$key.'='.Toolkit::baseDecode($value);//cause double encode from GET
                	}else{
                    	$uri .= '&amp;'.$key.'='.htmlspecialchars($value);
                	}
                }
            }
        }
        if (isset($_POST)){
            foreach ($_POST as $key => $value){
                if ($key != $page_param){
                    $uri .= '&amp;'.$key.'='.htmlspecialchars($value);
                }
            }
        }
        
        $curr_page =& ParamHolder::get('_m', '');
        if (!empty($_path)) {
        if(MOD_REWRITE=='3' && empty($curr_page)){
        	include(P_INC.'/custom.php');
        	$_path=strtolower(ParamHolder::get('_path', ''));
        	$arr = explode("-",$_path);
        	$_tmp=preg_replace('/[0-9]+/','',$arr[0]);
        	foreach($custom as $k=>$v){
        		if($v['path']==$_tmp){
        			$_m=$v['m'];
        			$_a=$v['a'];
        			break;
        		}
        	}
        	$uri .='&amp;'.'_m='.$_m.'&amp;'.'_a='.$_a;
        }
        }
		// for rewrite
		$temp = $extra = array();
		$temp = explode('&amp;', $uri);
		$ln = sizeof($temp);
		if ($ln) {
			if ($page!=1) {
				$extra = array($page_param => $page);
			}else{//在静态页面下不显示页数为1的链接
				$extra = array();
			}
			
			for ($i=1; $i<$ln; $i++) {
				$ky = substr($temp[$i], 0, strrpos($temp[$i],'='));
				$vl = substr($temp[$i], strrpos($temp[$i],'=') + 1);
				switch ($ky) {
					case '_m':
						$_m = $vl;
						break;
					case '_a':
						$_a = $vl;
						break;
					default:
						if (!empty($ky))
						{
							if ($ky=='txt') {//过滤掉添加页面--自定义页面列表下分页中的txt参数
								continue;
							}
							if($ky != $page_param && $key != "prdsearch_submit") {
								if (strpos($ky,"keyword")) {
									$extra_o[$ky] = Toolkit::baseEncode($vl);
								}elseif(strpos($ky,"_sw")){
									if ($vl=="") continue;
									else $extra_o[$ky] = $vl;
								}else{
									$extra_o[$ky] = $vl;
								}
								
							}
							if (strpos($ky,"keyword")) {
								$extra[$ky] = Toolkit::baseEncode($vl);
							}elseif(strpos($ky,"_sw")){
								if (empty($vl)||$vl=='-') continue;
								else $extra[$ky] = $vl;
							}else{
								$extra[$ky] = $vl;
							}
						}
						
						break;
				}
			}
		} else {
			$_m = DEFAULT_MODULE;
			$_a = DEFAULT_ACTION;
		}
		//var_dump($extra_o)."<br>";
		//print_r($extra_o);
		Pager::$mod = $_m;
		Pager::$act = $_a;
		Pager::$extUrl = $extra_o;
		// 伪静态下“添加页面”模块分页用
		if (Pager::$status == 'popupwin') {
			$uri = 'index.php?'.Html::xuriquery($_m, $_a, $extra);
		} else {
			
			if(MOD_REWRITE=='3'){
				$extra['cat']=$arr[1];
			}
			if (!empty($_path)) {
				$uri = Html::uriquery2($_m, $_a, $extra);
			}else{
				$uri = Html::uriquery($_m, $_a, $extra);
			}
		}
        return $uri;
    }

    private static function PagerUriCustom($page, $page_param){
    	$_path=strtolower(ParamHolder::get('_path', ''));
    	$uri = $_path.'-'.$page.'.hmtl';
        return $uri;
    }
    /**
     * A public wrapper for private _genPagerLinks function
     */
    public static function &genPagerLinks($curr_page, $rec_cnt, $page_param, $show_num) {
        $pager =& self::_genPagerLinks($curr_page, $rec_cnt, $page_param, $show_num);
        return $pager;
    }
}
?>