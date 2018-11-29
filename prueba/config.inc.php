<?php
global $paramBd;
$paramBd = array(
	"username" => "prueba",
	"password" => "prueba",
	"host" => "127.0.0.1",
	"dbname" => "prueba",
);

spl_autoload_register(function($className){
	if(file_exists("classes/".$className.".php")){
		require_once("classes/".$className.".php");
	}
});