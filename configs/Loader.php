<?php

class Loader{
	public static function init(){
		//Carga de parámetros
		self::paramLoader();
		$GLOBALS['twig']->addGlobal("URL",URL);

		//Definir identificador general para el carrito
		$cartId = "guest";
		//Si hay usuario registrado, se le pasa a twig para que pinte los datos necesarios y se cambia el id del carrito
		if(isset($_SESSION['auth'])){
			$GLOBALS['twig']->addGlobal("USER",$_SESSION['auth']);
			$cartId = $_SESSION['auth']->getId();
		}

		define("CARTID",$cartId);

		//Indicarle a twig cuántos artículos hay en el carro para mostrar el icono
		if(isset($_SESSION['carts']) && isset($_SESSION['carts'][CARTID])){
			$GLOBALS['twig']->addGlobal("CART_COUNT",count($_SESSION['carts'][CARTID]));
		}

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

		//Definir las constantes para que estén globalmente accesibles
		define("CONTROLLER",$controller);
		define("ACTION",$action);
		define("PARAM",$param);


		//Se le otorgan a Twig las variables de las rutas para que pinte el menú
		$GLOBALS['twig']->addGlobal("CONTROLLER",$controller);
		$GLOBALS['twig']->addGlobal("ACTION",$action);


	}

	public static function paramLoader(){
		$content = file_get_contents("parameters");
		$content = explode("\r\n",$content);
		foreach($content as $param){
			$param = explode("=", $param);
			define($param[0], $param[1]);
		}
	}
}