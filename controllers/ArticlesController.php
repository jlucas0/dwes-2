<?php
require_once 'models/Article.php';

class ArticlesController{

	public static function index(){

		$articles = Article::list();
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