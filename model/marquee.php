<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Marquee object
 * 
 */
class Marquee extends RecordObject {

	public static function getImg() {
        $db =& MySqlConnection::get();
        $sql = "SELECT id,img_name FROM ".Config::$tbl_prefix."marquees WHERE img_type=? ";
        $rs =& $db->query($sql, array(1));
		while($row =& $rs->fetchRow()){
			$rows[] = $row;
		}
		return $rows;
    }

	public static function getDirectionArray(){
		$arr = array('right'=>__('Right'),'left'=>__('Left'),'top'=>__('Top'),'down'=>__('Down'));
		return $arr;
	}
	public static function getSpeedArray(){
		$arr = array('quick'=>__('Quick'),'general'=>__('General'),'slow'=>__('Slow'));
		return $arr;
	}
	public static function getClassArray(){
		$arr = array('text'=>__('Text'),'pic'=>__('Pic'),'picText'=>__('pic and text'));
		return $arr;
	}
}
?>