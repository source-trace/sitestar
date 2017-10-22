<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModOffline extends Module {
    public function index() {
        $this->_layout = 'offline';
    }
}
?>
