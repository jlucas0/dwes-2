<?php
require 'models/Article.php';

class CartController{

	public static function index(){

		$articles = [];

		return $GLOBALS["twig"]->render(
            'cart/index.twig', 
            ['articles' => $articles]
        );
	}

}