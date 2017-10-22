<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModLang extends Module {
    protected $_filters = array(
        'check_login' => '{langbar}'
    );
    
    public function langbar() {
        $o_lang = new Language();
        $langs = $o_lang->findAll();
        $this->assign('langs', $langs);
    }
    
}
?>