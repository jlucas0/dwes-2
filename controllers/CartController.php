<?php
require_once 'models/Article.php';
require_once 'controllers/Controller.php';

class CartController implements Controller{

	//Muestra el carrito de la sesión actual con los artículos añadidos
	public static function index(){

		$articles = [];
		$price = 0;

		if(isset($_SESSION['carts'])){
			
			if(isset($_SESSION['carts'][CARTID])){
				foreach($_SESSION['carts'][CARTID] as $id=>$amount){
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

	//Introduce un artículo nuevo al carrito
	public static function add($id){
		//Buscar que exista el producto
		$article = Article::find($id);
		if($article){
			
			if(!isset($_SESSION['carts'])){
				$_SESSION['carts'] = [];
			}
			if(!isset($_SESSION['carts'][CARTID])){
				$_SESSION['carts'][CARTID] = [];
			}

			//Si ya está el artículo, se incrementa en una unidad
			if(isset($_SESSION['carts'][CARTID][$id])){
				$_SESSION['carts'][CARTID][$id]++;
			}
			//Si no, se añade
			else{
				$_SESSION['carts'][CARTID][$id] = 1;
			}
			//Redirigir a la vista del carro
			header("Location: ".URL."/cart");
			exit;
		}
	}

	//Actualiza las cantidades de los artículos, enviados por POST, y devuelve el precio actualizado
	public static function update(){
		//Buscar que exista el producto
		$article = Article::find($_POST['article']);
		$price = 0;
		if($article){
			
			//Si la cantidad es 0, eliminar el artículo
			if($_POST['amount'] == 0){
				unset($_SESSION['carts'][CARTID][$article->getId()]);
			}
			//Si no, actualizar la cantidad
			else{
				$_SESSION['carts'][CARTID][$article->getId()] = $_POST['amount'];
			}

			foreach($_SESSION['carts'][CARTID] as $id=>$amount){
				$article = Article::find($id);
				$price += $article->getPrice() * $amount;
			}
		}
		return $price;
	}

	//No relevantes
	public static function create(){}
	public static function edit(){}
	public static function delete(){}

}