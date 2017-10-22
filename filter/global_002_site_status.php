<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class SiteStatus {
    public function execute() {
        $module = strtolower(ParamHolder::get('_m', DEFAULT_MODULE));
        if (intval(SITE_OFFLINE) == 1) { 
            if ($module != 'mod_offline' && 
                $module != 'mod_auth' && 
                !Toolkit::editMode()) {
                Content::redirect(Html::uriquery('mod_offline', 'index'));
            }
        }
    }
}
?>
