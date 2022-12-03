<?php
// Carga de TWIG
require 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('views');
$twig = new \Twig\Environment($loader);

// Carga de dependencias
require 'configs/Database.php';
require 'configs/Loader.php';
require 'controllers/UsersController.php';
require 'controllers/ArticlesController.php';
require 'controllers/CartController.php';
require 'controllers/PurchasesController.php';
require 'controllers/AdminController.php';

session_start();

Loader::init();

//Generar la respuesta según el controlador solicitado y la acción
$response = "";


//Ejecutar acción solicitada
$controller = ucfirst(CONTROLLER).'Controller';
if(class_exists($controller)){
	$controllerObject = new $controller();
	if(method_exists($controllerObject, ACTION)){
		$action = ACTION;
		$response = $controllerObject::$action(PARAM);
	}
}
//Si es algo incorrecto, carga por defecto la portada
if(empty($response) && $response !== 0){
	header("Location: ".URL);
	exit;
}

echo $response;