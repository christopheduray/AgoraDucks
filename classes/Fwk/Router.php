<?php
namespace Fwk;

class Router {

    public static $uri="";
    public static $routes=[];
    public static $baseDir;

    public static function add($method,$matching,$target){
        static::$routes[]=['method'=>$method,'pattern'=>$matching,'target'=>$target];
    }

    public static function dispatch(){
        static::$uri=$_SERVER['REQUEST_URI'];
        $base=preg_replace("/index.php$/","",$_SERVER['SCRIPT_NAME']);
        static::$baseDir=$base;
        
        static::$uri=substr(static::$uri,strlen($base)-1);    // gérer les chemins relatifs pour la base
        static::$uri=preg_replace("/\?.*/","",static::$uri);
        
        $match=0;
        foreach(static::$routes as $r){
            if(preg_match("|".$r['pattern']."|",static::$uri) && $_SERVER['REQUEST_METHOD']==$r['method']){
                $match++;
                call_user_func($r['target']);
                break;
            }
        }

        if(!$match){
            header("HTTP/1.0 404 Not Found");
            echo "Page introuvable";
        }
    }

    public static function redirect($uri){
        header('Location: '.static::$baseDir.$uri);
    }

}