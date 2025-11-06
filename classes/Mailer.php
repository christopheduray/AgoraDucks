<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PPE;


class Mailer{

    static $cache=[];
    static $cid_idx=1;
    static $mail=null;

    public static function send($dest,$subject,$msg){
        $mail=new PHPMailer();
        try {
            $mail->CharSet = "UTF-8";
            $mail->addAddress($dest);
            $mail->isHTML(true);
            $mail->Subject=$subject;
            $mail->SetFrom($_ENV['MAIL_FROM']);
            
            static::$mail=$mail;
            $msg=static::processImages($msg);
            $mail->Body=$msg;
            
            $mail->send();
        } catch (PPE $e) {
            echo $e->errorMessage();
            die();
        }
    }


    public static function processImages($msg){
        return preg_replace_callback("|img src=\"".$_ENV['PUBLIC_URL']."([^\"]+)\"|",
                [static::class, 'getImageCache' ],
                $msg);
    }

    public static function getImageCache($matches){
        if(!array_key_exists($matches[1],static::$cache)){
            $cid_name='ducky_'.static::$cid_idx;
            static::$cache[$matches[1]]=$cid_name;
            static::$mail->addEmbeddedImage(__DIR__.'/../'.$matches[1],$cid_name);
            static::$cid_idx++;
        }
        return "img src=\"cid:".static::$cache[$matches[1]]."\"";
    }


}