<?php
require 'models/Article.php';

class ArticlesController{

	public static function index(){

		$articles = [];

		return $GLOBALS["twig"]->render(
            'articles/index.twig', 
            ['articles' => $articles]
        );
	}

	public static function details(){
		return $GLOBALS["twig"]->render(
            'articles/details.twig'
        );
	}

}