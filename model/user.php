<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * User object
 * 
 */
class User extends RecordObject {
    public $has_many = array('OnlineOrder', 'DeliveryAddress');
    
    public $has = array('UserExtend');
    
    protected $no_validate = array(
        'isEmpty' => array(
            array('login', 'Missing login name!'), 
//            array('passwd', 'Missing password!'),
//            array('email', 'Missing e-mail!'),
            array('active', 'Missing status!'),
            array('s_role', 'Missing user role!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'active', 'Invalid status!'),
            array('/^\{\w+\}$/', 's_role', 'Invalid user role!')
        ),
        'isEmail' => array(
            array('email', '不支持的email地址格式!')
        )
    );
}
?>