<?php
namespace Fwk;

/* l'ORM du pauvre... */
abstract class Model {
    protected static $baseTable;
    protected $attrs=[];

    public function __set($k,$v){
        $this->attrs[$k]=$v;
    }

    public function __get($k){
        return $this->attrs[$k]??'';
    }

    public function save(){
        $fields=$this->attrs;
        if(array_key_exists('id',$fields)) unset($fields['id']);
		if(!$this->id){
			$q=DB::get()->prepare("insert into ".static::$baseTable
				." (".join(',',array_keys($fields)).") "
				." values (".join(',',
					array_map(function($e){ return ':'.$e; },array_keys($fields))
					).")");
		} else {
			$q=DB::get()->prepare("update ".static::$baseTable
				." set ".join(',',
					array_map(function($e){ return $e." = :".$e; }, array_keys($fields))
					)." where id = :id");
			$q->bindValue(':id',$this->id);
		}
        foreach($fields as $k=>$v) $q->bindValue(':'.$k,$v);
		$q->execute();

		$e=$q->errorInfo(); if((int)$e[0]) throw new \Exception(print_r($e,1));
		if(!$this->id){
			$this->id=DB::get()->lastInsertId();
		}
    }

    public function hydrate($ar){
        foreach($ar as $k=>$v) $this->{$k}=$v;
    }

    public static function load($id){
        $o=new static;
        $q=DB::get()->prepare("select * from ".static::$baseTable." where id=:id");
        $q->bindValue('id',$id);
        $q->execute();
        if($r=$q->fetch(\PDO::FETCH_ASSOC)){
            foreach($r as $k=>$v) $o->{$k}=$v;
        }
        return $o;
    }

}