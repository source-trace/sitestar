<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class UserExtend extends RecordObject {
    public $belong_to = array('User');
}
?>
