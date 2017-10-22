<?php


if (!defined('IN_CONTEXT')) die('access violation error!');
/*
if (!defined('LC_MESSAGES')) {
    define('LC_MESSAGES', 6);
}
if(!SessionHolder::get('_LOCALE_FLAG', '')){
	SessionHolder::set('_LOCALE',DEFAULT_LOCALE);
	SessionHolder::set('_LOCALE_FLAG',DEFAULT_LOCALE);
}
$curr_locale = SessionHolder::get('_LOCALE', '');

$available_langs = array();
$o_lang = new Language();
$arr_langs =& $o_lang->findAll();
if (sizeof($arr_langs) > 0) {
    foreach ($arr_langs as $lang) {
        $lang_key = strtolower(str_replace('_', '-', $lang->locale));
        $available_langs[$lang_key] = $lang->locale;
    }
}

if (strlen(trim($curr_locale)) == 0) {
    $curr_locale = DEFAULT_LOCALE;
    $client_accept_langs = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    if (strlen(trim($client_accept_langs)) > 0) {
        $arr_client_accept_langs = explode(',', $client_accept_langs);
        foreach ($arr_client_accept_langs as $client_lang) {
            $arr_client_accept_langs_parts = explode(';', $client_lang);
            $client_lang_r = trim($arr_client_accept_langs_parts[0]);
            if (isset($available_langs[$client_lang_r])) {
                $curr_locale = $available_langs[$client_lang_r];
                break;
            }
        }
    }
    if (intval(AUTO_LOCALE) == 1) SessionHolder::set('_LOCALE', $curr_locale);
}

$r_lang = trim(ParamHolder::get('_l', $curr_locale));
if (isset($available_langs[strtolower(str_replace('_', '-', $r_lang))])) {
    $curr_locale = $r_lang;
}
SessionHolder::set('_LOCALE', $curr_locale);

_setlocale(LC_MESSAGES, $curr_locale);
$domain = 'messages';
_bindtextdomain($domain, P_LOCALE);
_bind_textdomain_codeset($domain, LOCALE_CHARSET);
_textdomain($domain);
*/
$curr_locale = DEFAULT_LOCALE;
$r_lang = trim(ParamHolder::get('_l', ''));
if($r_lang){
	SessionHolder::set('SS_LOCALE', $r_lang);
}
if(SessionHolder::get('SS_LOCALE')){
	$curr_locale = SessionHolder::get('SS_LOCALE');
}
SessionHolder::set('_LOCALE', $curr_locale);
$lang = include_once P_LOCALE.'/'.$curr_locale.'/lang.php';
function __($msgid){
	global $lang;
	if(array_key_exists($msgid,$lang)){
		return $lang[$msgid];
	}else{
		return $msgid;
	}
	
}

function _e($msgid){
	global $lang;
	if(array_key_exists($msgid,$lang)){
		echo $lang[$msgid];
	}else{
		echo $msgid;
	}
}
?>