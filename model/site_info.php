<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Site information object
 * 
 */
class SiteInfo extends RecordObject {
    protected $no_validate = array(
        'isEmpty' => array(
            array('s_locale', 'Missing locale!')
        )
    );
}
?>