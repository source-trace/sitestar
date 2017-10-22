<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Online QQ object
 * 
 */
class OnlineQq extends RecordObject {
    protected $no_validate = array(
        'isEmpty' => array(
            array('account', 'Missing QQ account!'),
            array('qqname', 'Missing QQ name!'), 
            array('published', 'Missing publish status!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'published', 'Invalid publish status!')
        )
    );
}
?>