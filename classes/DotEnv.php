<?php

class DotEnv {

	public static function load(){
		global $_ENV;
		if(!is_file(__DIR__."/../.env"))
			die(".env non trouvé");
		$fh=fopen(__DIR__."/../.env",'r');
		
		while($l=fgets($fh)){
			$l=rtrim($l);
			if(preg_match('/=/',$l)
				&& !preg_match("/^#/",$l)){
					$x=explode('=',$l);
					$_ENV[$x[0]]=$x[1];
			}
		}
	}

}
