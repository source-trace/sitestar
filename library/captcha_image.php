<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Generate captcha image with given text
 *
 * @package captcha
 */
class CaptchaImage {
    /**
     * Display the generated captcha image
     *
     * @param string $captcha_string
     */
    public static function showCaptcha($captcha_string) {
        header('Content-type: image/jpeg');
        $captcha = imagecreate(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);
        $bg_red = rand(0, 128);
        $bg_green = rand(0, 128);
        $bg_blue = rand(0, 128);
        $background = imagecolorallocate($captcha, $bg_red, $bg_green, $bg_blue);
        $color = imagecolorallocate($captcha, 255 - $bg_red, 
            255 - $bg_green, 255 - $bg_blue);
        self::signs($captcha);
        imagestring($captcha, CAPTCHA_FSIZE, 
            CAPTCHA_TXT_LEFT, CAPTCHA_TXT_TOP, 
            $captcha_string, $color);
        imagejpeg($captcha, null, 100);
        imagedestroy($captcha);
    }

    /**
     * Generate a random background image with random letters
     * 
     * this function is derived from "CAPTCHA class"
     * by Pascal Rehfeldt <Pascal@Pascal-Rehfeldt.com>
     * 
     * @param resource $image
     * @param int $cells
     */
    private static function signs (&$image, $cells = 3) {
        $w = imagesx($image);
        $h = imagesy($image);
        for ($i = 0; $i < $cells; $i++) {
            $centerX = mt_rand(1, $w);
            $centerY = mt_rand(1, $h);
            $amount = mt_rand(1, 15);
            $stringcolor = imagecolorallocate($image, 128, 128, 128);
            for ($n = 0; $n < $amount; $n++) {
                $signs = range('A', 'Z');
                $sign = $signs[mt_rand(0, count($signs) - 1)];
                imagestring($image, 2, 
                    $centerX + mt_rand(-10, 30), 
                    $centerY + mt_rand(-30, 10), 
                    $sign, $stringcolor);
            }
        }
    }
}
?>