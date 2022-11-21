<?php
require 'models/Purchase.php';

class PurchasesController{

	public static function index(){

		$purchases = [];

		return $GLOBALS["twig"]->render(
            'purchases/index.twig', 
            ['purchases' => $purchases]
        );
	}

}