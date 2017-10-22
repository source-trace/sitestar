<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class DeliveryAddress extends RecordObject {
    public $belong_to = array('User');
}
?>
