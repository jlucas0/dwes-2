<?php

class Database{

	public static function connect(){
		return new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
	}


	public static function disconnect($conexion){
		return mysqli_close($conexion);
	}

}