<?php
class Topinyin{
	/**
	 * 汉字转换为拼音
	 *
	 * @param number
	 * @return unknown
	 */
	public static function transfer($num){
		$words = include(P_INC.'/to_pinyin.php');
		if($num>0&&$num<160){
			return chr($num);
		}elseif($num<-20319||$num>-10247){
			return "";
		}else{
			for($i=count($words)-1;$i>=0;$i--){
				if($words[$i][1]<=$num) break;
			}
			return $words[$i][0];
		}
	}
	
	/**
	 * 将字符转换成utf-8格式的
	 *
	 * @param string $_C
	 * @return string
	 */
	public static function to_utf8($_C) { 
        $_String = ''; 
        if($_C < 0x80) $_String .= $_C; 
        elseif($_C < 0x800) 
        { 
                $_String .= chr(0xC0 | $_C>>6); 
                $_String .= chr(0x80 | $_C & 0x3F); 
        }elseif($_C < 0x10000){ 
                $_String .= chr(0xE0 | $_C>>12); 
                $_String .= chr(0x80 | $_C>>6 & 0x3F); 
                $_String .= chr(0x80 | $_C & 0x3F); 
        } elseif($_C < 0x200000) { 
                $_String .= chr(0xF0 | $_C>>18); 
                $_String .= chr(0x80 | $_C>>12 & 0x3F); 
                $_String .= chr(0x80 | $_C>>6 & 0x3F); 
                $_String .= chr(0x80 | $_C & 0x3F); 
        } 
        return iconv('UTF-8', 'GB2312', $_String); 
	}
	
	/**
	 * 将传入的字符串转换并返回
	 *
	 * @param string $str
	 * @return strings
	 */
	public static function get_pinyin($str){
	    $ret="";
	    $str = self::to_utf8($str);
	    for($i=0;$i<strlen($str);$i++){
		    $p=ord(substr($str,$i,1));
		    if($p>160){
			    $q=ord(substr($str,++$i,1));
			    $p=$p*256+$q-65536;
	    	}
	    	$ret.=self::transfer($p);
	    }
	    return $ret;
	}
}

?>