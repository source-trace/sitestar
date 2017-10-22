<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class Transaction extends RecordObject {
    public $belong_to = array('User');
}
?>
