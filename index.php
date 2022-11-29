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
require 'controllers/CartController.php';
require 'controllers/PurchasesController.php';

session_start();
//Si hay usuario registrado, se le pasa a twig para que pinte los datos necesarios
if(isset($_SESSION['auth'])){
	$twig->addGlobal("USER",$_SESSION['auth']);
}

//Generar la respuesta según el controlador solicitado y la acción
$response = "";

//Acciones por defecto en caso de que no se especifiquen
$controller = "articles";
$action = "index";
$param = null;

//Lectura de los parámetros de ruta, que el htaccess incluye en el valor path
if(isset($_GET['path'])){
	$path = explode("/",$_GET['path']);
	if(isset($path[0])){
		$controller = $path[0];
		if(isset($path[1])){
			$action = $path[1];
			if(isset($path[2])){
				$param = $path[2];
			}
		}
	}
}


//Se le otorgan a Twig las variables de las rutas para que pinte el menú
$twig->addGlobal("CONTROLLER",$controller);
$twig->addGlobal("ACTION",$action);

$controller = ucfirst($controller).'Controller';
if(class_exists($controller)){
	$controllerObject = new $controller();
	if(method_exists($controllerObject, $action)){
		$response = $controllerObject::$action($param);
	}
}
//Si es algo incorrecto, carga por defecto la portada
if(empty($response) && $response !== 0){
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