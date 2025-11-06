<?php

class Token {

    public $value='';

    public function __construct(){
        for($i=0;$i<12;$i++){
                $c=rand(48,90);
                if($c>57 && $c<65){
                        $c-=10;
                }
                $this->value.=chr($c);
        }
    }

    public function get(){
        return $this->value;
    }

}