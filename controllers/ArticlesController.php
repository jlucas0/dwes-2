<?php
require_once 'models/Article.php';
require_once 'controllers/Controller.php';

class ArticlesController implements Controller{

	//Muestra el listado de artículos para el catálogo frontal
	public static function index(){

		$articles = Article::list();
		return $GLOBALS["twig"]->render(
            'articles/index.twig', 
            ['articles' => $articles]
        );
	}

	//Muestra los detalles de un artículo concreto
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

	/*TODO - para el CRUD */
	public static function create(){}
	public static function edit(){}
	public static function update(){}
	public static function delete(){}
}