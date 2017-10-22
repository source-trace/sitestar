<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$_m = array();
$_g_flts = array();
$_flt_dir = dir(P_FLT);
while (false !== ($entry = $_flt_dir->read())) {
    if (!preg_match('/^global_.+\.php$/', $entry)) {
        continue;
    }
    $_g_flts[] = $entry;
}
$_flt_dir->close();

if (sizeof($_g_flts) > 0) {
    sort($_g_flts);
    foreach ($_g_flts as $_g_flt) {
        preg_match('/^global_\d+_(.+)\.php$/', $_g_flt, $_m);
        
        include_once(P_FLT.DS.$_g_flt);
        $filter_name_part = explode('_', $_m[1]);
        $filter_class_name = '';
        foreach ($filter_name_part as $name_part) {
            $filter_class_name .= ucfirst($name_part);
        }
        $filter_class = new $filter_class_name();
        $filter_class->execute();
    }
}
?>
