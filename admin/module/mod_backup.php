<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModBackup extends Module {
	// 2010/03/16 Jane Add >>
	var $dir = '../sql/backup';
	// 2010/03/16 Jane Add <<
	
	protected $_filters = array(
        'check_admin' => ''
    );
    
    public function admin_list() {
    	
    	$this->_layout = 'content';
    	$next_action = 'admin_backup';
    	$current_time = date("YmdHis");
	    $random = rand(100,999);
	    $file_list = $this->get_file_list($this->dir);
	    $file_list2 = $this->get_file_list($this->dir);
	   if (sizeof($file_list)>0) {
		    foreach ($file_list as $p=>$f){
		    	if(strstr($f['fname'],"zip")) continue;
		    	$vo = explode ( "_v", $f['fname'] );
		    	$v_id = explode ( ".sq", $vo [1] );
				// 当前分卷为$volume_id
				$v_id = intval( $v_id [0] );
				if ($v_id==1) {
					$u_file[] = $f;
				}else{
					if (empty($v_id)) {
						$fil = explode(".sq",$f['fname']);
					if (!empty($fil[0])) {
						$u_file[] = $f;
					}
					}
					
				}
				
		    }
	    }
	    if (sizeof($u_file)>0) {
		    foreach ($u_file as $key=>$file){
		    	if(strstr($file['fname'],"zip")) continue;
		    	$volume = explode ( "_v", $file['fname'] );
		    	foreach ($file_list2 as $k2=>$file2){
		    		$c_volume = explode("_v",$file2['fname']);
		    		if(strstr($c_volume[1],"zip")) continue;
		    		if ($c_volume[0]==$volume[0]) {
		    			$f_list[$key][] = $file2;
			    	}
				}
		    }
	    }
    	$file_name = 'backup_'.$current_time.".sql";
    	$this->assign('next_action', $next_action);
    	$this->assign('file_name', $file_name);
		$this->assign( 'list', $f_list);
    }
    
    public function admin_load(){
    	$files = $this->get_file_list( $this->dir );
    	$fid =& ParamHolder::get('_fid', array());
        $file = $this->dir.'/'.$fid;
        $volume = explode ( "_v", $file );
		$volume_path = $volume [0];
		if (empty ( $volume [1] )) {//没有分卷
			include_once P_LIB."/download.php";
			$o_filedownload = new file_download();
			$o_filedownload->downloadfile( $file );
		}else{
			$tmpfile = $volume_path .  "_v.zip";
			// 存在其他分卷，继续执行
			if (file_exists ( $tmpfile )) {
				include_once P_LIB."/download.php";
				$o_filedownload = new file_download();
				$o_filedownload->downloadfile( $tmpfile );
			} else {
				break;
			}
		}
		die;
    } 
    
    public function admin_backup() {
		@ini_set('memory_limit','128M');
    	$backup =& ParamHolder::get('backup', array());
    	$user = Config::$db_user;
	  	$password = Config::$db_pass;
		$db_name = Config::$db_name;
    	$f_size = 1000;
    	if(empty($backup['file_name'])) {
    		$this->assign('json', Toolkit::jsonERR(__('Missing site information!')));
            return '_result';
    	} else {
    		@chmod('../sql/backup/',0755);
    		$file_name = '../sql/backup/'.$backup['file_name'];
    		try{
				$db_host = (Config::$db_host).":".(Config::$port);
	    		$link = mysql_connect($db_host,Config::$db_user,Config::$db_pass);
				mysql_select_db(Config::$db_name, $link);
				mysql_query("SET NAMES ".Config::$mysqli_charset ,  $link);
				$res = mysql_query("show tables");
				$tables = array();$sql = "";
				while ($result = mysql_fetch_array($res, MYSQL_NUM)) {
					$tables[] = $result[0];
				}
				$drive = 1;//卷标;
    			foreach($tables as $k => $table){
    				//由于出现单张表过大的情况，这里将原来函数整合进行操作，对单张表进行分卷操作
    				$tabledump = "DROP TABLE IF EXISTS $table;\n";
					$createtable = mysql_query("SHOW CREATE TABLE $table");
					$create = mysql_fetch_row($createtable);
					$tabledump .= $create[1].";\n\n";
					
					$rows = mysql_query("SELECT * FROM $table");
					$numfields = mysql_num_fields($rows);
					$numrows = mysql_num_rows($rows);
					while ($row = mysql_fetch_row($rows)){
					  $tab_desc = mysql_query("describe $table");
					  $tab_filed = '';
					  while($tab_rows = mysql_fetch_row($tab_desc)){
						  $tab_filed .= '`'.$tab_rows[0].'`,';
					  }
					  $tab_filed = substr($tab_filed,0,-1);
					  
					  $comma = "";
					  $tabledump .= "INSERT INTO $table($tab_filed) VALUES(";
					  for($i = 0; $i < $numfields; $i++){
						  $tabledump .= $comma."'".mysql_escape_string($row[$i])."'";
						  $comma = ",";
					  }
					  $tabledump .= ");\n";
					  //单张表过大也进行分卷
			    	  if (strlen($tabledump) > $f_size*1000) {
			    		$d_file_name = substr($file_name,0,strlen($file_name)-4)."_v".$drive.".sql";
			    		@chmod($d_file_name,0755);
						if (!$handle = fopen($d_file_name, 'a')) {
						     echo "Can not open $d_file_name";
						     exit;
						}
						if (fwrite($handle, $tabledump) === FALSE) {
						    echo "Can not write $d_file_name";
							exit;
						}
						fclose($handle);
						$tabledump = '';
						$drive++;
						$z_arr[] = $d_file_name;
			    	  }
					}
					$tabledump .= "\n";
					if ($tabledump != "") {//写入文件，单张表进行追加
	    				$f_file_name = substr($file_name,0,strlen($file_name)-4)."_v".$drive.".sql";
	    				@chmod($f_file_name,0755);
					    if (!$handle = fopen($f_file_name, 'a')) {
					         echo "Can not open $f_file_name";
					         exit;
					    }
					    if (fwrite($handle, $tabledump) === FALSE) {
					        echo "Can not write $f_file_name";
					        exit;
					    }
					    fclose($handle);
					    
    			  }
    			  
    			  
    			}
    			$z_arr[] = $f_file_name;
    			$z = new zipper();
    			$a = $z->CompileFile($z_arr,$this->dir."/".substr($backup['file_name'],0,strlen($backup['file_name'])-4)."_v.zip","array");
	        	$o_backup = new Backup();
	        	$time = time();
	        	$file_name = ROOT."/sql/backup/{$backup['file_name']}";
	        	$o_backup->set(array('create_time' => $time,'file_name' => $file_name));
	        	$o_backup->save();
    		}catch(Exception $ex) {
    			 $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            	 return '_result';
    		}
	        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_backup', 'admin_list'))));
        	return '_result';
    	}
    }
    
    public function admin_delete()
    {
    	$files = $this->get_file_list( $this->dir );
    	$fid =& ParamHolder::get('_fid', '');
        $backup = $this->dir.'/'.$fid;
        if (strstr($backup,"zip")) {
    		$backup = str_replace("v.zip","v1.sql",$backup);
    	}
        if (!file_exists($backup)) {
        	$this->assign('json', Toolkit::jsonERR(__('File does not exist!')));
            return '_result';
        } else {
        	$volume = explode ( "_v", $backup );
			$volume_path = $volume [0];
			$volume_id = explode ( ".sq", $volume [1] );
			// 当前分卷为$volume_id
			$volume_id = intval( $volume_id [0] );
			if (empty ( $volume [1] )) {//没有分卷
				chmod($backup, 0755);
        		@unlink($backup);
			}else{
				while ( $volume_id ) {//循环删除分卷文件
					$tmpfile = $volume_path . "_v" . $volume_id . ".sql";
					if (file_exists ( $tmpfile )) {
						chmod($tmpfile, 0755);
        				@unlink($tmpfile);
					} else {
//						echo $tmpfile;
						break;
					}
					$volume_id++;
				}
				$zip = $volume_path . "_v" . ".zip";//删除ZIP压缩文件
				if (file_exists ( $zip )) {
		        	chmod($zip, 0755);
		        	@unlink($zip);
				}
	        }
        }
		$this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_backup', 'admin_list'))));
        return '_result';
    } 
    
    public function import() {

    	$files = $this->get_file_list( $this->dir );
    	$fid =& ParamHolder::get('_fid', '');
        $import_file = $this->dir.'/'.$fid;
        //程序分卷前后，分卷后程序对分卷前数据库备份，恢复提示
        $import_file_array = explode('_',$import_file);
        $import_file_key=substr($import_file_array[count($import_file_array)-1],0,1);
        if($import_file_key!='v'){
		    	$this->assign('json', Toolkit::jsonERR(__('Import Different Versions Info')));
		        return '_result';
        }
        //得到第一个卷或者文件，并查找是否有下个分卷，含_v的是分卷，一并恢复
        @chmod($import_file,0755);
    	if (strstr($import_file,"zip")) {
    		$import_file = str_replace("v.zip","v1.sql",$import_file);
    	}
    	$db_host = Config::$db_host;
    	$db_host .= ":".(Config::$port);
    	$db_user = Config::$db_user;
    	$db_pass = Config::$db_pass;
    	$db_name = Config::$db_name;
    	$charset = Config::$mysqli_charset;
    	$link = mysql_connect($db_host,$db_user,$db_pass);
    	mysql_select_db($db_name,$link);
    	mysql_query("SET NAMES ".Config::$mysqli_charset ,$link);
    	
    	$volume = explode ( "_v", $import_file );
		$volume_path = $volume [0];
		
		if (empty ( $volume [1] )) {//没有分卷
	    	$mqr = @get_magic_quotes_runtime();
	    	@set_magic_quotes_runtime(0);
	    	$query = fread(fopen($import_file, "r"), filesize($import_file)); 
	    	@set_magic_quotes_runtime($mqr);
	    	$pieces  = $this->_split_sql($query);
			if(function_exists("mysqli_set_charset")){
	    		@mysqli_set_charset($link, $charset);
			}else{
				mysql_query("set character_set_client=binary");
			}
		    for ($i=0; $i<count($pieces); $i++){
				$pieces[$i] = trim($pieces[$i]);
				$pos1 = strpos($pieces[$i], "DROP TABLE IF EXISTS");
				$pos2 = strpos($pieces[$i], "CREATE TABLE");
				$pos3 = strpos($pieces[$i],"INSERT INTO");
				if(($pos1 === false) && ($pos2 === false) && ($pos3 === false)){
					continue;
				}
				if(($pos1 == 0) || ($pos2 == 0) || ($pos3 == 0)){
					if(!empty($pieces[$i]) && $pieces[$i] != "#"){
						$pieces[$i] = str_replace( "#__", '', $pieces[$i]); 
						if (!$result = @mysql_query ($pieces[$i])) {
							$errors[] = array ( mysql_error(), $pieces[$i] );
						}
					}
		    	}
			}
		}else{//循环将分卷导入
			// 存在分卷，则获取当前是第几分卷，循环执行余下分卷
			$volume_id = explode ( ".sq", $volume [1] );
			// 当前分卷为$volume_id
			$volume_id = intval( $volume_id [0] );
			while ( $volume_id ) {
				$tmpfile = $volume_path . "_v" . $volume_id . ".sql";
				// 存在其他分卷，继续执行
				if (file_exists ( $tmpfile )) {
					$mqr = @get_magic_quotes_runtime();
			    	@set_magic_quotes_runtime(0);
			    	$query = fread(fopen($tmpfile, "r"), filesize($tmpfile)); 
			    	@set_magic_quotes_runtime($mqr);
			    	$pieces  = $this->_split_sql($query);
					if(function_exists("mysqli_set_charset")){
			    		@mysqli_set_charset($link, $charset);
					}else{
						mysql_query("set character_set_client=binary");
					}
				    for ($i=0; $i<count($pieces); $i++){
						$pieces[$i] = trim($pieces[$i]);
						$pos1 = strpos($pieces[$i], "DROP TABLE IF EXISTS");
						$pos2 = strpos($pieces[$i], "CREATE TABLE");
						$pos3 = strpos($pieces[$i],"INSERT INTO");
						if(($pos1 === false) && ($pos2 === false) && ($pos3 === false)){
							continue;
						}
						if(($pos1 == 0) || ($pos2 == 0) || ($pos3 == 0)){
							if(!empty($pieces[$i]) && $pieces[$i] != "#"){
								$pieces[$i] = str_replace( "#__", '', $pieces[$i]); 
								if (!$result = @mysql_query ($pieces[$i])) {
									$errors[] = array ( mysql_error(), $pieces[$i] );
								}
							}
				    	}
					}
				} else {
					break;
				}
				$volume_id++;
			}
		}
		$this->assign('json', Toolkit::jsonOK(array()));
        return '_result';
    }
    
    public function import_file()
    {
    	//ParamHolder::get('import_file', array(), PS_FILES);不支持sql后缀
    	$file_name = $_FILES['import_file']['name'];
    	$postfix_name = substr($file_name,-3);
    	@chmod($_FILES['import_file']['tmp_name'],0755);
    	if($postfix_name != 'sql')
    	{
    		@unlink($_FILES['import_file']['tmp_name']);//删除潜在的恶意脚本
    		die(_e('File type error!'));
    	}
    	
    	$db_host = Config::$db_host;
    	$db_host .= ":".(Config::$port);
    	$db_user = Config::$db_user;
    	$db_pass = Config::$db_pass;
    	$db_name = Config::$db_name;
    	$charset = Config::$mysqli_charset;
    	
    	try{
	    	$link = mysql_connect($db_host,$db_user,$db_pass);
	    	mysql_select_db($db_name,$link);
	    	mysql_query("SET NAMES ".Config::$mysqli_charset ,$link);
	    	$mqr = @get_magic_quotes_runtime();
	    	@set_magic_quotes_runtime(0);
	    	$query = fread(fopen($_FILES['import_file']['tmp_name'], "r"), filesize($_FILES['import_file']['tmp_name'])); 
	    	@set_magic_quotes_runtime($mqr);
	    	$pieces  = $this->_split_sql($query);
	    	//@mysqli_set_charset($link, $charset);
			if(function_exists("mysqli_set_charset")){
				@mysqli_set_charset($link, $charset);
			}else{
				mysql_query("set character_set_client=binary");
			}
		    for ($i=0; $i<count($pieces); $i++){
				$pieces[$i] = trim($pieces[$i]);
				
				$pos1 = strpos($pieces[$i], "DROP TABLE IF EXISTS");
				$pos2 = strpos($pieces[$i], "CREATE TABLE");
				$pos3 = strpos($pieces[$i],"INSERT INTO");
				if(($pos1 === false) && ($pos2 === false) && ($pos3 === false))
				{
					continue;
				}
				if(($pos1 == 0) || ($pos2 == 0) || ($pos3 == 0)){
					if(!empty($pieces[$i]) && $pieces[$i] != "#"){
						$pieces[$i] = str_replace( "#__", '', $pieces[$i]); 
						if (!$result = @mysql_query ($pieces[$i])) {
							$errors[] = array ( mysql_error(), $pieces[$i] );
						}
					}
		    	}
			}
    	}catch(Exception $ex) {
    		@unlink($_FILES['import_file']['tmp_name']);//删除潜在的恶意脚本
    		die(_e('Data recovery has failed,please check out your sql file whether it is right.'));
    	}
		@unlink($_FILES['import_file']['tmp_name']);
		$return_msg = __('Backup successfully!');
		echo <<<JS
<script type="text/javascript">
alert("$return_msg");
parent.window.location.reload(); 
</script>
JS;
		die;
    }
    
    private function _split_sql($sql) {
    	$sql = trim($sql);
		$sql = @str_replace("\n#[^\n]*\n", "\n", $sql);
		$buffer = array();
		$ret = array();
		$in_string = false;
		for($i=0; $i<strlen($sql)-1; $i++){
			if($sql[$i] == ";" && !$in_string){
				$ret[] = substr($sql, 0, $i);
				$sql = substr($sql, $i + 1);
				$i = 0;
			}
		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\"){
			$in_string = false;
		}elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")){
			$in_string = $sql[$i];
		}
		if(isset($buffer[1])) {
			$buffer[0] = $buffer[1];
		}
			$buffer[1] = $sql[$i];
		}
		if(!empty($sql)){
			$ret[] = $sql;
		}
		return($ret);
    }
    

    /*
     * Description: display file list in a directory
     * Version: v1.0.0
	 * Date: 2010/03/16
	 * Author: Jane
	 * Copyright: cndns.com
     */
	private function get_file_list( $filePath )
	{
		$list = $result = array();
		$files = is_array(glob($filePath.'/*')) ? glob($filePath.'/*') : array();
		foreach( $files as $file )
		{
			if ( is_dir( $file ) ) {
				//$list = array_merge( $list, getFileList($file) );
			} else {
				$list[] = $file;
			}
		}
		
		// format array
		$cnt = sizeof( $list );
		for( $i=0; $i<$cnt; $i++ )
		{
			$file = $list[$i];
			$result[$i]['fname'] = basename( $file );
			$result[$i]['fsize'] = filesize( $file );
			$result[$i]['ftime'] = filectime( $file );
		}
		
		return $result;
	}
	
	private function data2sql($table,$file_names)
	{
		$tabledump = "DROP TABLE IF EXISTS $table;\n";
		$createtable = mysql_query("SHOW CREATE TABLE $table");
		$create = mysql_fetch_row($createtable);
		$tabledump .= $create[1].";\n\n";
		
		$rows = mysql_query("SELECT * FROM $table");
		$numfields = mysql_num_fields($rows);
		$numrows = mysql_num_rows($rows);
		while ($row = mysql_fetch_row($rows))
		{
		  $tab_desc = mysql_query("describe $table");
		  $tab_filed = '';
		  while($tab_rows = mysql_fetch_row($tab_desc)){
			  $tab_filed .= '`'.$tab_rows[0].'`,';
		  }
		  $tab_filed = substr($tab_filed,0,-1);
		  
		  $comma = "";
		  $tabledump .= "INSERT INTO $table($tab_filed) VALUES(";
		  for($i = 0; $i < $numfields; $i++)
		  {
		   $tabledump .= $comma."'".mysql_escape_string($row[$i])."'";
		   $comma = ",";
		  }
		  $tabledump .= ");\n";
		  //单张表过大也进行分卷
		  
		}
		$tabledump .= "\n";
		return $tabledump;
	}

}
?>