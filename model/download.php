<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Download object
 * 
 */
class Download extends RecordObject {
    public $belong_to = array('DownloadCategory');
    protected $no_validate = array(
        'isEmpty' => array(
            array('name', 'Missing download name!'), 
            array('create_time', 'Missing create time!'),
            array('s_locale', 'Missing locale!'),
            array('download_category_id', 'Missing category id!'),
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
            array('download_category_id', 'Invalid download category id!'),
            array('pub_end_time', 'Invalid end time!')
        )
    );
    public function set_d($downloads) {
        foreach($downloads as $download) {
            $o_download = new Download($download->id);
            $c_info['download_category_id'] = 1;
            $o_download->set($c_info);
            $o_download->save();
        }       
    }
}
?>