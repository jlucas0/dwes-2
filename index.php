<?php

 // Carga de TWIG
require 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

// Carga de dependencias
require 'configs/database.php';


//Redirigir según el controlador solicitado y la acción
if(isset($_GET['controller'])){
    $controller = ucfirst($_GET['controller']).'Controller';
    if(class_exists($controller)){
    	$controller_object = new $controller();
		if(isset($_GET['action'])){
			$action = $_GET['action'];
			$controller_object->$action();
		}
    }else{
    }
}else{
}