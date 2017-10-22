<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Class for handling mail templates and sending mail
 *
 * @package mailer
 */
class Mailer {
    /**
     * Sending mail to a single recipient
     *
     * @access public
     * @static
     * @param string $recipient The recipient E-mail address
     * @param string $template The E-mail template used for the mail
     * @param array $params Parameters used for replacing %%...%% entities in template
     * @param string $recipient_name The friendly name for recipient
     */
    public static function send($recipient, $template, 
        $params = array(), $recipient_name = '') {
        try {
            /* get mail template info and check essential parameters */
            $template_info =& self::_loadTemplate($template);
            if (!isset($template_info['type']) || 
                !isset($template_info['subject']) || 
                !isset($template_info['sender_name']) || 
                !isset($template_info['body'])) {
                $error = 'Missing mail template parameters!'."\n";
                throw new MailerException($error);
            }

            /* replate variable entities in template */
            if (sizeof($params) > 0) {
                $repl_entity = array();
                $repl_value = array();
                foreach ($params as $key => $value) {
                    $repl_entity[] = '%%'.$key.'%%';
                    $repl_value[] = $value;
                }
                $template_info['subject'] = str_replace($repl_entity, $repl_value, 
                    $template_info['subject']);
                $template_info['body'] = str_replace($repl_entity, $repl_value, 
                    $template_info['body']);
            }

            /* begin send mail */
            $mail = new PHPMailer();
            $mail->SetLanguage('en', P_LIB.'/phpmailer/language/');

            /* set debug flag */
            if (ENABLE_SMTP_DEBUG) {
                $mail->SMTPDebug = true;
            }

            if (USE_SMTP) {
                /* using SMTP for sending mail */
                $mail->IsSMTP();
                $mail->Host = SMTP_SERVER;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASS;
            } else {
                /* using PHP mail() function */
                $mail->IsMail();
            }

            /* set mail charset explictly */
            $mail->CharSet = MAIL_CHARSET;

            $mail->From = SMTP_USER;
            $mail->FromName = trim($template_info['sender_name']);
            $mail->AddAddress($recipient, $recipient_name);
            $mail->WordWrap = 80;
            if (trim($template_info['type']) == '1') {
                $mail->IsHTML(true);
            }

            $mail->Subject = $template_info['subject'];
            $mail->Body = $template_info['body'];

            if (!$mail->Send()) {
                $error = $mail->ErrorInfo."\n";
                throw new MailerException('Sending mail failed!'."\n"
                    .$error);
            } else {
                return true;
            }
        } catch (MailerException $ex) {
            throw new MailerException($ex->getMessage());
        }
    }
    
    /**
     * Parse E-mail template
     *
     * @access private
     * @static
     * @param string $template The E-mail template used for the mail
     */
    private static function &_loadTemplate($template) {
        $template_info = array();
        
        $fp = @fopen(P_MTPL.'/'.$template.'.mtpl', 'r');
        if ($fp) {
            $tpl_section = '';
            while (!feof($fp)) {
                $line = fgets($fp);
                switch (trim($line)) {
                    case '==SENDER_NAME==':
                        $tpl_section = 'sender_name';
                        break;
                    case '==IS_HTML==':
                        $tpl_section = 'type';
                        break;
                    case '==SUBJECT==':
                        $tpl_section = 'subject';
                        break;
                    case '==BODY==':
                        $tpl_section = 'body';
                        break;
                    default:
                        if (empty($tpl_section)) {
                            continue;
                        }
                        
                        if (!isset($template_info[$tpl_section])) {
                            $template_info[$tpl_section] = '';
                        }
                        $template_info[$tpl_section] .= $line;
                }
            }
            
            fclose($fp);
            
            if (sizeof($template_info) == 0) {
                $error = 'Mail template "'.$template.'" format error!'."\n";
                throw new MailerException($error);
            } else {
                return $template_info;
            }
        } else {
            $error = 'Mail template "'.$template.'" does not exist!'."\n";
            throw new MailerException($error);
        }
    }
}

/**
 * Exception class for handling mail errors
 *
 * @package mailer
 */
class MailerException extends Exception {
}
?>