<?php

require_once 'models/Model.php';
require_once 'configs/Database.php';

class Purchase implements Model{

	private $id, $date, $user, $articles = [];

	public function __construct($userId = null){
		$this->user = $userId;
	}

	public static function list(){
		$db = Database::connect();

		$result = $db->query("SELECT * FROM purchases WHERE user_id = ".$_SESSION['auth']->getId()." ORDER BY date DESC");

		$data = [];

		while($row = $result->fetch_object()){

			$lines = $db->query("SELECT * FROM purchased_articles JOIN articles ON article_id = id WHERE purchase_id = $row->id");

			$purchase = new Purchase();
			$purchase->setId($row->id);
			$purchase->setDate(new DateTime($row->date));
			$purchase->setUser($row->user_id);
			$lines = $db->query("SELECT purchased_articles.*,articles.title, articles.picture FROM purchased_articles JOIN articles ON article_id = id WHERE purchase_id = $row->id");
			while($line = $lines->fetch_object()){
				$article = new Article();
				$article->setTitle($line->title);
				$article->setPicture($line->picture);
				$article->setPrice($line->price);
				$article->amount = $line->amount;
				$purchase->addArticle($article);
			}
			$data[] = $purchase;
		}
		Database::disconnect($db);
		return $data;
	}

	public static function find($id){
		$db = Database::connect();

		$result = $db->query("SELECT * FROM purchases WHERE user_id = $_SESSION[auth]->getId() AND id=$id");

		$purchase = null;

		if($row = $result->fetch_object()){

			$lines = $db->query("SELECT * FROM purchased_articles JOIN articles ON article_id = id WHERE purchase_id = $row->id");

			$purchase = new Purchase();
			$purchase->setId($row->id);
			$purchase->setDate(new DateTime($row->date));
			$purchase->setUser($row->user);
			$lines = $db->query("SELECT purchased_articles.*,articles.title, articles.picture FROM purchased_articles JOIN articles ON article_id = id WHERE purchase_id = $row->id");
			while($line = $lines->fetch_object()){
				$article = new Article();
				$article->setId($line->id);
				$article->setTitle($line->title);
				$article->setPicture($line->picture);
				$article->setPrice($line->price);
				$article->amount = $line->amount;
				$purchase->addArticle($article);
			}
		}
		Database::disconnect($db);
		return $purchase;
	}

	public function save(){
		$db = Database::connect();

		$result = false;

		$result = $db->query("INSERT INTO purchases (user_id) VALUES ($this->user)");

		if(mysqli_error($db)){
			$result = mysqli_error($db);
		}else{
			$purchaseId = mysqli_insert_id($db);
			$sql = "INSERT INTO purchased_articles VALUES ";
			foreach($this->articles as $article){
				$sql .= "($purchaseId,".$article->getId().",".$article->getPrice().",".$article->amount."),";
			}
			$sql = rtrim($sql,',');
			$result = $db->query($sql);
			if(mysqli_error($db)){
				$result = mysqli_error($db);
			}
		}

		Database::disconnect($db);
		return $result;
	}

	public function delete(){
	//Mejor no borrar ventas
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
	}

	public function getArticles(){
		return $this->articles;
	}

	public function addArticle($article){
		$this->articles[] = $article;
	}

	public function getPrice(){
		$price = 0;
		foreach($this->articles as $article){
			$price += $article->getPrice()*$article->amount;
		}

		return $price;
	}
}