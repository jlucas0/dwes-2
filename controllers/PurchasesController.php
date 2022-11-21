<?php
require 'models/Purchase.php';

class PurchasesController{

	public static function index(){

		$purchases = [];

		return $GLOBALS["twig"]->render(
            'purchases/list.twig', 
            ['purchases' => $purchases]
        );
	}

	public static function cart(){

		$articles = [];

		return $GLOBALS["twig"]->render(
            'purchases/cart.twig', 
            ['articles' => $articles]
        );
	}
}