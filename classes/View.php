<?php

class View {
    public static function render(string $viewName, array $params=[]){
        foreach($params as $k=>$v) ${$k}=$v;

        include __DIR__.'/../views/header.php';
        include __DIR__.'/../views/'.$viewName.'.php';
        include __DIR__.'/../views/footer.php';

    }
}