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
}