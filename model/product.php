<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Product object
 * 
 */
class Product extends RecordObject {
    public $belong_to = array('ProductCategory');
    public $has_many = array('ProductPic');
    
    protected $no_validate = array(
        'isEmpty' => array(
            array('name', 'Missing product name!'), 
            array('recommended', 'Missing recommend status!'),
            array('create_time', 'Missing create time!'),
            array('product_category_id', 'Missing category!'),
            array('s_locale', 'Missing locale!'),
            array('pub_start_time', 'Missing start time!'),
            array('pub_end_time', 'Missing end time!'),
            array('published', 'Missing publish status!'),
            array('for_roles', 'Missing access property!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'recommended', 'Invalid recommend status!'),
            array('/^0|1$/', 'published', 'Invalid publish status!'),
            array('/^(\{\w+\})+$/', 'for_roles', 'Invalid access property!')
        ),
        'isNumeric' => array(
            array('create_time', 'Invalid time!'),
            array('product_category_id', 'Invalid category!'),
            array('pub_start_time', 'Invalid start time!'),
            array('pub_end_time', 'Invalid end time!')
        )
    );
    
    public static function explain_publish(&$product) {
        $now = time();
        $txt_publish = __('Published');
        
        if ((intval($product->pub_start_time) != -1 && 
        	intval($product->pub_start_time) >= $now) || 
        	(intval($product->pub_end_time) != -1 && 
        	intval($product->pub_end_time) < $now)) {
            $txt_publish = __('Unpublished');
        } else {
            if (intval($product->published) == 0) {
                $txt_publish = __('Unpublished');
            }
        }
        
        return $txt_publish;
    }
	public static function getMaxOrder($parent_category_p_id) {
        $db =& MySqlConnection::get();
        $sql = "SELECT MAX(i_order) AS max_order FROM ".Config::$tbl_prefix."products";
        $rs =& $db->query($sql, array($parent_category_p_id));
        if ($rs->getRecordNum() == 0) {
            return 0;
        } else {
            $row =& $rs->fetchRow();
            return intval($row['max_order']);
        }
    }
}
?>