<?php
require 'models/Article.php';

class ArticlesController{

	public static function index(){

		$articles = [];

		echo $GLOBALS["twig"]->render(
            'articles/index.twig', 
            ['articles' => $articles]
        );
	}

}