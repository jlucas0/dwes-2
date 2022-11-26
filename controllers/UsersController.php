<?php
require_once 'models/User.php';

class UsersController{

	public static function login(){

	}

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
				$_SESSION['auth'] = $user;
			}
		}

		return json_encode($errors);
	}

	public static function logout(){
		if(isset($_SESSION['auth'])){
			session_destroy();
		}
		header("Location: ".URL);
	}

}