<?php

require_once 'models/Model.php';
require_once 'configs/Database.php';

class User implements Model{

	private $id, $name, $email, $password, $admin;

	public static function list(){
		$db = Database::connect();

		$result = $db->query("SELECT * FROM users");

		$data = [];

		while($row = $result->fetch_object()){
			$user = new User();
			$user->setId($row->id);
			$user->setName($row->name);
			$user->setEmail($row->email);
			$user->setPassword($row->password);
			$user->setAdmin($row->admin);
			$data[] = $user;
		}

		Database::disconnect($db);
		return $data;
	}

	public static function find($id){
		$db = Database::connect();

		$result = $db->query("SELECT * FROM users WHERE id=$id");

		$user = null;

		if($row = $result->fetch_object()){
			$user = new User();
			$user->setId($row->id);
			$user->setName($row->name);
			$user->setEmail($row->email);
			$user->setPassword($row->password);
			$user->setAdmin($row->admin);
		}
		Database::disconnect($db);
		return $user;
	}

	public static function findByEmail($email){
		$db = Database::connect();

		$result = $db->query("SELECT * FROM users WHERE email='$email'");

		$user = null;

		if($row = $result->fetch_object()){
			$user = new User();
			$user->setId($row->id);
			$user->setName($row->name);
			$user->setEmail($row->email);
			$user->setPassword($row->password);
			$user->setAdmin($row->admin);
		}
		Database::disconnect($db);
		return $user;
	}

	public function save(){
		$db = Database::connect();

		$result = false;

		if(empty($this->id)){
			$result = $db->query("INSERT INTO users (name,email,password) VALUES ('$this->name','$this->email','$this->password')");
		}else{
			$result = $db->query("UPDATE users SET name='$this->name', email='$this->email', password='$this->password' WHERE id=$this->id");
		}

		if(mysqli_error($db)){
			$result = mysqli_error($db);
		}

		Database::disconnect($db);
		return $result;
	}

	public function delete(){
		$db = Database::connect();

		$result = false;

		$result = $db->query("DELETE FROM users WHERE id=$this->id");

		Database::disconnect($db);
		return $result;
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		$this->password = $password;
	}


	public function isAdmin(){
		return $this->admin;
	}

	public function setAdmin($admin){
		$this->admin = $admin;
	}

}