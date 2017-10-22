<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class OrderProduct extends RecordObject {
    public $belong_to = array('OnlineOrder');
}
?>
