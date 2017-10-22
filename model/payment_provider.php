<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class PaymentProvider extends RecordObject {
    public $has_one = array('PaymentAccount');
}
?>
