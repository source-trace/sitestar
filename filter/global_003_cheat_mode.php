<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class CheatMode {
    public function execute() {
        if (ParamHolder::get('_v', false) == 'preview') {
            SessionHolder::set('page/status', 'view');
        }
    }
}
?>
