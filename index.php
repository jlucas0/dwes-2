<?php
//Lectura de parámetros
paramLoader('parameters');

// Carga de TWIG
require 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('views');
$twig = new \Twig\Environment($loader);

$twig->addGlobal("URL",URL);

// Carga de dependencias
require 'configs/database.php';
require 'controllers/UsersController.php';
require 'controllers/ArticlesController.php';
require 'controllers/PurchasesController.php';


//Generar la respuesta según el controlador solicitado y la acción
$response = "";

//Acciones por defecto en caso de que no se especifiquen
if(!isset($_GET['controller'])){
	$_GET['controller'] = "articles";
}

if(!isset($_GET['action'])){
	$_GET['action'] = "index";
}


$controller = ucfirst($_GET['controller']).'Controller';
if(class_exists($controller)){
	$controllerObject = new $controller();
	$action = $_GET['action'];
	if(method_exists($controllerObject, $action)){
		$response = $controllerObject::$action();
	}
}
//Si es algo incorrecto, carga por defecto la portada
if(empty($response)){
	redirect();
}

echo $response;


function paramLoader(){
	$content = file_get_contents("parameters");
	$content = explode("\r\n",$content);
	foreach($content as $param){
		$param = explode("=", $param);
		define($param[0], $param[1]);
	}
}

//Se ejecuta si se introduce una url incorrecta, para que cargue la portada
function redirect(){
	header("Location: ".URL);
	exit;
}