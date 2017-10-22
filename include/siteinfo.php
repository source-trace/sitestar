<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

$curr_locale = SessionHolder::get('_LOCALE', DEFAULT_LOCALE);
$o_siteinfo = new SiteInfo();
$curr_siteinfo =& $o_siteinfo->find("s_locale=?", array($curr_locale));
if ($curr_siteinfo)
SessionHolder::set('_SITE', $curr_siteinfo);
else {
    $o_siteinfo->site_name = '';
    $o_siteinfo->keywords = '';
    $o_siteinfo->description = '';
    SessionHolder::set('_SITE', $o_siteinfo);
}
?>