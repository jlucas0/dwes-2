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

	public static function details($id){

		//Obtener el artículo
		$article = Article::find($id);

		if($article){
			return $GLOBALS["twig"]->render(
	            'articles/details.twig',
	            ['article' => $article]
    	    );
		}
		header("HTTP/1.0 404 Not Found");
		exit;
	}

}