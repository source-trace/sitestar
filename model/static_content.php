<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Static content object
 * 
 */
class StaticContent extends RecordObject {
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'published', 'Invalid publish status!')
        ),
        'isNumeric' => array(
            array('create_time', 'Invalid time!')
        )
    );
	public static function getSC($locale) {
        $db =& MySqlConnection::get();
        $sql = "SELECT id FROM ".Config::$tbl_prefix."static_contents WHERE s_locale=? and published='1' order by id desc";
        $rs =& $db->query($sql, array($locale));
        if ($rs->getRecordNum() == 0) {
            return 0;
        } else {
            $row =& $rs->fetchRows();
            return $row;
        }
    }
}
?>