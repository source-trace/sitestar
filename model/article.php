<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Article object
 * 
 */
class Article extends RecordObject {
    public $belong_to = array('ArticleCategory');
    
    protected $no_validate = array(
        'isEmpty' => array(
            array('title', 'Missing article title!'), 
            array('author', 'Missing article author!'), 
            array('create_time', 'Missing create time!'),
            array('s_locale', 'Missing locale!'),
            array('pub_start_time', 'Missing start time!'),
            array('pub_end_time', 'Missing end time!'),
            array('published', 'Missing publish status!'),
            array('for_roles', 'Missing access property!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'published', 'Invalid publish status!'),
            array('/^(\{\w+\})+$/', 'for_roles', 'Invalid access property!')
        ),
        'isNumeric' => array(
            array('create_time', 'Invalid time!'),
            array('pub_start_time', 'Invalid start time!'),
            array('i_order', 'Invalid i_order!'),
            array('pub_end_time', 'Invalid end time!')
        )
    );
	public static function getMaxOrder($parent_category_p_id) {
        $db =& MySqlConnection::get();
        $sql = "SELECT MAX(i_order) AS max_order FROM ".Config::$tbl_prefix."articles";
        $rs =& $db->query($sql, array());
        if ($rs->getRecordNum() == 0) {
            return 0;
        } else {
            $row =& $rs->fetchRow();
            return intval($row['max_order']);
        }
    }

}
?>