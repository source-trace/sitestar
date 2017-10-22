<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Article object
 * 
 */
class Friendlink extends RecordObject {
    protected $no_validate = array(
        'isEmpty' => array(
            array('create_time', 'Missing create time!'),
            array('s_locale', 'Missing locale!'),
            array('fl_addr', 'Missing friendlink addr!'),
            array('published', 'Missing publish status!'),
            array('for_roles', 'Missing access property!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'published', 'Invalid publish status!'),
            array('/^(\{\w+\})+$/', 'for_roles', 'Invalid access property!'),
            array('/#|(http:\/\/)?[^\s]*/i', 'fl_addr', 'invalid friendlink addr')
        ),
        'isNumeric' => array(
            array('create_time', 'Invalid time!')
        )
    );
	public function &getFLType() {
		$showType = array('1'=>__("Show Image"),'2'=>__("Show Text"));
		return $showType;
	}
}
?>