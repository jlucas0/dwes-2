<?php
require_once 'models/Purchase.php';

class PurchasesController{

	public static function index(){

		//Verificar quién es

		$purchases = [];

		return $GLOBALS["twig"]->render(
            'purchases/index.twig', 
            ['purchases' => $purchases]
        );
	}

	public static function new(){
		//Ver quién es
		if(isset($_SESSION['auth'])){

			//Generar la venta
			$purchase = new Purchase();
			$purchase->setUser($_SESSION['auth']->getId());
			//Procesar el carrito actual
			foreach($_SESSION['carts'][$GLOBALS['cartId']] as $articleId => $amount){
				$article = Article::find($articleId);
				$article->amount = $amount;
				//Introducir las líneas
				$purchase->addArticle($article);
			}
			$purchase->save();
			//Borrar el carro
			unset($_SESSION['carts'][$GLOBALS['cartId']]);
			//Redirigir al listado de compras
			header("Location:".URL."/purchases");
			exit;
		}
		
	}

}