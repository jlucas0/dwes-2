<?php

require_once 'models/Model.php';
require_once 'configs/Database.php';

class Article implements Model{

	private $id, $title, $price, $picture, $description, $highlight;

	public static function list(){
		$db = Database::connect();

		$result = $db->query("SELECT * FROM articles");

		$data = [];

		while($row = $result->fetch_object()){
			$article = new Article();
			$article->setId($row->id);
			$article->setTitle($row->title);
			$article->setPrice($row->price);
			$article->setPicture($row->picture);
			$article->setDescription($row->description);
			$article->setHighlight($row->highlight);
			$data[] = $article;
		}

		return $data;
	}

	public static function find($id){

	}

	public function save(){

	}

	public function delete(){

	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getPrice(){
		return $this->price;
	}

	public function setPrice($price){
		$this->price = $price;
	}

	public function getPicture(){
		return $this->picture;
	}

	public function setPicture($picture){
		$this->picture = $picture;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getHighlight(){
		return $this->highlight;
	}

	public function setHighlight($highlight){
		$this->highlight = $highlight;
	}

}