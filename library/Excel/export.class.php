<?php

class Export_CSV {

    var $os = 'Other';
	var $targetfile = '';
	var $fields = array();
	
	function Export_CSV($fields, $targetfile) {
		$this->fields = $fields;
		$this->targetfile = $targetfile;
	}
	
	function Export() {
		$fp = fopen($this->targetfile, 'w');

		foreach ($this->fields as $line) {
			/*$line = preg_replace('/<.+?>/i', '', $line);*/
			//$line = str_replace(array("\r", "\n", "<"), array('\r', '\n', '"<"'), $line);
			$line = str_replace(array("\r", "\n"), array(' ', ' ',), $line);//导出数据内容适应于PRO版CSV
		    fputcsv( $fp, $this->encode($line) );
		}
		
		fclose($fp);
		// download csv
		include_once P_LIB."/download.php";
		$load = new file_download();
		$load->downloadfile( $this->targetfile );
		// 
		@unlink($this->targetfile);
		exit;
	}
	
	function encode($arr) {
		if( isset($_SERVER['HTTP_USER_AGENT']) && strripos($_SERVER['HTTP_USER_AGENT'], 'Windows') )
		{
			$ln = count($arr);
			for( $i=0; $i<$ln; $i++ ) {
	    		$arr[$i] = iconv('UTF-8', 'GB2312//IGNORE', $arr[$i]);
	    	}
		}
		return $arr;
	}
}
	
?>