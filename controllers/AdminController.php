<?php
require_once 'models/User.php';

class AdminController{

	//Muestra la vista de login
	public static function login(){
		$loginError = $_SESSION['loginError'] ?? "";
		unset($_SESSION['loginError']);
		return $GLOBALS["twig"]->render(
            'admin/login.twig',
            ['error' => $loginError]
        );
	}

	//Verifica el acceso de la vista de login
	public static function access(){

		$user = null;		
		$error = "";

		//Comprobar el email
		$email = $_POST['email'] ?? "";
		if(!$email){
			$error = 'El email es obligatorio.';
		}
		else if(!$user = User::findByEmail($email)){
			$error = 'Usuario no encontrado.';
		}
		if($user){
			//Comprobar el password
			$password = $_POST['password'] ?? "";
			if(!$password){
				$error = 'El password es obligatorio.';
			}else if(!password_verify($_POST['password'], $user->getPassword())){
				$error = 'El password es incorrecto.';
			}
			//Registrarlo
			if(empty($error)){
				$_SESSION['auth'] = $user;
				header("Location: ".URL."/admin");
				exit;
			}

		}


		$_SESSION['loginError'] = $error;
		header("Location: ".URL."/admin/login");
		exit;
	}

	public static function index(){
		
		if(!isset($_SESSION['auth']) || !$_SESSION['auth']->isAdmin()){
			header("Location: ".URL."/admin/login");
			exit;
		}
		$articles = Article::list();
		return $GLOBALS["twig"]->render(
            'admin/index.twig', 
            ['articles' => $articles]
        );
	}

	public static function logout(){
		if(isset($_SESSION['auth']) && !$_SESSION['auth']->isAdmin()){
			unset($_SESSION['auth']);
		}
		header("Location: ".URL."/admin/login");
		exit;
	}

}