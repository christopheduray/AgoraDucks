
<?php
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\Data\QRMatrix;

class QRGen {

    public static function getOptions(){
        $options = new QROptions;

        // $outputType can be one of: GDIMAGE_BMP, GDIMAGE_GIF, GDIMAGE_JPG, GDIMAGE_PNG, GDIMAGE_WEBP
        $options->outputType          = QROutputInterface::GDIMAGE_PNG;
        $options->outputBase64 = false;
        $options->quality             = 90;
        // the size of one qr module in pixels
        $options->scale               = 20;
        $options->bgColor             = [255, 255, 255];
        $options->imageTransparent    = false;
        /*
        // the color that will be set transparent
        // @see https://www.php.net/manual/en/function.imagecolortransparent
        $options->transparencyColor   = [255, 255, 255];
        */
        $options->drawCircularModules = true;
        $options->drawLightModules    = true;
        $options->circleRadius        = 0.4;
        $options->keepAsSquare        = [
            QRMatrix::M_FINDER_DARK,
            QRMatrix::M_FINDER_DOT,
            QRMatrix::M_ALIGNMENT_DARK,
        ];
        /*
        $options->moduleValues        = [
            QRMatrix::M_FINDER_DARK    => [0, 63, 255], // dark (true)
            QRMatrix::M_FINDER_DOT     => [0, 63, 255], // finder dot, dark (true)
            QRMatrix::M_FINDER         => [233, 233, 233], // light (false)
            QRMatrix::M_ALIGNMENT_DARK => [255, 0, 255],
            QRMatrix::M_ALIGNMENT      => [233, 233, 233],
            QRMatrix::M_DATA_DARK      => [0, 0, 0],
            QRMatrix::M_DATA           => [233, 233, 233],
        ];
        */

        return $options;
    }


    public static function getFilename($id,$token){
        return $id.'_'.$token.'.png';
    }

    public static function getShortFilename($id,$token){
        return 's_'.$id.'_'.$token.'.png';
    }

    public static function gen($id,$token){
        $options=static::getOptions();
        $qrCode=(new QRCode($options))->render($_ENV['PUBLIC_URL']."validate.php?id=$id&token=$token");

        $fh=fopen(__DIR__.'/../storage/'.static::getFilename($id,$token),"w");
        fwrite($fh,$qrCode);
        fclose($fh);
    }

    public static function genShort($id,$token){
        $options=static::getOptions();
        $qrCode=(new QRCode($options))->render($_ENV['PUBLIC_SHORTURL']."$id/$token");

        $fh=fopen(__DIR__.'/../storage/'.static::getShortFilename($id,$token),"w");
        fwrite($fh,$qrCode);
        fclose($fh);
    }

    public static function publicURL(){
        $options=static::getOptions();
        $qrCode=(new QRCode($options))->render(preg_replace("|/[^/]+/$|","",$_ENV['PUBLIC_URL']));

        $fh=fopen(__DIR__.'/../storage/atc.png',"w");
        fwrite($fh,$qrCode);
        fclose($fh);
    }

}
