<?php

interface Model{

	public static function list();

	public static function find($id);

	public function save();

	private function create();

	private function update();

	public function delete();

}