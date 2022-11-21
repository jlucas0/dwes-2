<?php

interface Model{

	public static function list();

	public static function find($id);

	public function save();

	public function delete();

}