<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class OnlineOrder extends RecordObject {
    public $belong_to = array('User');
    
    public $has_many = array('OrderProduct');
}
?>
