<?php
require_once 'models/Article.php';

class CartController{

	public static function index(){

		$articles = [];
		$price = 0;
		$cartId = "guest";

		if(isset($_SESSION['carts'])){
			if(isset($_SESSION['auth'])){
				$cartId = $_SESSION['auth']->getId();
			}
			if(isset($_SESSION['carts'][$cartId])){
				foreach($_SESSION['carts'][$cartId] as $id=>$amount){
					$article = Article::find($id);
					$article->amount = $amount;
					$price += $article->getPrice() * $amount;
					$articles[] = $article;
				}
			}
		}

		return $GLOBALS["twig"]->render(
            'cart/index.twig', 
            ['articles' => $articles,'price'=>$price]
        );
	}

	public static function add($id){
		$cartId = "guest";
		//Buscar que exista el producto
		$article = Article::find($id);
		if($article){
			//Determinar el usuario actual
			if(isset($_SESSION['auth'])){
				$cartId = $_SESSION['auth']->getId();
			}
			if(!isset($_SESSION['carts'])){
				$_SESSION['carts'] = [];
			}
			if(!isset($_SESSION['carts'][$cartId])){
				$_SESSION['carts'][$cartId] = [];
			}

			//Si ya está el artículo, se incrementa en una unidad
			if(isset($_SESSION['carts'][$cartId][$id])){
				$_SESSION['carts'][$cartId][$id]++;
			}
			//Si no, se añade
			else{
				$_SESSION['carts'][$cartId][$id] = 1;
			}
			//Redirigir a la vista del carro
			header("Location: ".URL."/cart");
			exit;
		}
	}

	public static function update(){
		$cartId = "guest";
		//Buscar que exista el producto
		$article = Article::find($_POST['article']);
		$price = 0;
		if($article){
			//Determinar el usuario actual
			if(isset($_SESSION['auth'])){
				$cartId = $_SESSION['auth']->getId();
			}
			
			//Si la cantidad es 0, eliminar el artículo
			if($_POST['amount'] == 0){
				unset($_SESSION['carts'][$cartId][$article->getId()]);
			}
			//Si no, actualizar la cantidad
			else{
				$_SESSION['carts'][$cartId][$article->getId()] = $_POST['amount'];
			}

			foreach($_SESSION['carts'][$cartId] as $id=>$amount){
				$article = Article::find($id);
				$price += $article->getPrice() * $amount;
			}
		}
		return $price;
	}

}