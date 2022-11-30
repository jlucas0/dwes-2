<?php
require_once 'models/User.php';
require_once 'controllers/Controller.php';

class UsersController implements Controller{

	//Inicia sesión
	public static function login(){
		$errors = [];
		
		$user = null;		

		//Comprobar el email
		$email = $_POST['email'] ?? "";
		if(!$email){
			$errors['email'] = 'El email es obligatorio';
		}
		else if(!$user = User::findByEmail($email)){
			$errors['email'] = 'Usuario no encontrado';
		}
		if($user){
			//Comprobar el password
			$password = $_POST['password'] ?? "";
			if(!$password){
				$errors['password'] = 'El password es obligatorio';
			}else if(!password_verify($_POST['password'], $user->getPassword())){
				$errors['password'] = 'El password es incorrecto';
			}
			//Registrarlo
			if(empty($errors)){
				$_SESSION['auth'] = $user;
				//Pasar el carrito de invitado a este usuario
				if(isset($_SESSION['carts']['guest'])){
					$_SESSION['carts'][$user->getId()] = $_SESSION['carts']['guest'];
					unset($_SESSION['carts']['guest']);
				}
			}

		}

		return json_encode($errors);
	}

	//Registra un nuevo usuario
	public static function register(){
		$errors = [];
		//Comprobar el nombre
		$name = $_POST['name'] ?? "";
		if(!$name){
			$errors['name'] = 'El nombre es obligatorio';
		}
		else if(strlen($name) > 50){
			$errors['name'] = 'El nombre es demasiado largo';
		}
		//Comprobar el email
		$email = $_POST['email'] ?? "";
		if(!$email){
			$errors['email'] = 'El email es obligatorio';
		}
		else if(strlen($email) > 50){
			$errors['email'] = 'El email es demasiado largo';
		}
		else if(User::findByEmail($email)){
			$errors['email'] = 'El email está en uso';
		}
		//Comprobar el password
		$password = $_POST['password'] ?? "";
		if(!$password){
			$errors['password'] = 'El password es obligatorio';
		}
		//Registrarlo
		if(empty($errors)){
			$user = new User();
			$user->setName($name);
			$user->setEmail($email);
			$user->setPassword(password_hash($password, PASSWORD_DEFAULT));
			$result = $user->save();
			if($result !== true){
				$errors['general'] = "Se ha producido un error al realizar el registro";
			}else{
				$user = User::findByEmail($email);
				$_SESSION['auth'] = $user;
				//Pasar el carrito de invitado a este usuario
				if(isset($_SESSION['carts']['guest'])){
					$_SESSION['carts'][$user->getId()] = $_SESSION['carts']['guest'];
					unset($_SESSION['carts']['guest']);
				}
			}
		}

		return json_encode($errors);
	}

	//Cierra sesión del usuario actual
	public static function logout(){
		if(isset($_SESSION['auth'])){
			unset($_SESSION['auth']);
		}
		header("Location: ".URL);
	}

	//Alias de register
	public static function create(){
		self::register();
	}

	//TODO - para el CRUD
	public static function edit(){}
	public static function update(){}
	public static function delete(){}

	//No relevante
	public static function index(){}

}