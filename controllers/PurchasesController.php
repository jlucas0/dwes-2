<?php
require_once 'models/Purchase.php';
require_once 'controllers/Controller.php';

class PurchasesController implements Controller{

	//Listado de compras realizadas por el usuario actual
	public static function index(){

		//Verificar quién es
		if($_SESSION['auth']){

			return $GLOBALS["twig"]->render(
	            'purchases/index.twig', 
	            ['purchases' => Purchase::list()]
	        );
		}
	}

	//Registra una nueva compra
	public static function create(){
		//Ver quién es
		if(isset($_SESSION['auth'])){

			//Generar la venta
			$purchase = new Purchase($_SESSION['auth']->getId());
			//Procesar el carrito actual
			foreach($_SESSION['carts'][CARTID] as $articleId => $amount){
				$article = Article::find($articleId);
				$article->amount = $amount;
				//Introducir las líneas
				$purchase->addArticle($article);
			}
			$purchase->save();
			//Borrar el carro
			unset($_SESSION['carts'][CARTID]);
			//Redirigir al listado de compras
			header("Location:".URL."/purchases");
			exit;
		}
		
	}

	//No relevantes
	public static function edit(){}
	public static function update(){}
	public static function delete(){}

}