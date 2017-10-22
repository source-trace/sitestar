<?php


define('IN_CONTEXT', 1);

define('ROOT', dirname(__FILE__));
define('P_LIB', ROOT.'/library');

/* Captcha configrations you can edit */
define('CAPTCHA_WIDTH', 65); // The captcha image with
define('CAPTCHA_HEIGHT', 18); // The captcha image height
define('CAPTCHA_FSIZE', 3); // The font size displayed on captcha image
define('CAPTCHA_TXT_LEFT', 3); // The text left padding
define('CAPTCHA_TXT_TOP', 2); // the text bottom padding
/* Stop editing here */

include_once(ROOT.'/config.php');
include_once(P_LIB.'/rand_math.php');

include_once(P_LIB.'/param.php');
SessionHolder::initialize();

include_once(P_LIB.'/captcha_image.php');
CaptchaImage::showCaptcha(RandMath::genRandExpr());
?>
