<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class PaymentAccount extends RecordObject {
    public $belong_to = array('PaymentProvider');
}
?>
