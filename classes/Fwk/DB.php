<?php
namespace Fwk;

class DB {

    public static $pdo=null;

    public static function get(){
        if(!static::$pdo)
        try {
            static::$pdo=new \PDO('mysql:host='.$_ENV['DB_HOST'].';port='.$_ENV['DB_PORT'].';dbname='.$_ENV['DB_DB'].';charset=UTF8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], array( \PDO::ATTR_PERSISTENT => true));
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        return static::$pdo;
    }

}