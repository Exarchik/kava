<?php 
class KavaConfig{
	public $host = 'localhost';
	//public $user = 'u_kafe_local';
    public $user = 'root';
	//public $password = 'Coffee_maker_159';
    public $password = '';
	//public $db = 'kava';
    public $db = 'kava_deps_04102019';
	//public $base_link = 'http://kava.deps.ua';
	public $base_link = 'http://localhost/kava';
	
	// HTTP BASIC AUTHENTICATION логин/пароль от админки
	public $basicLogin = 'admin';
	public $basicPassword = 'aQ9WQRx4';
}

$config = new KavaConfig();

require_once("const.php");

require_once("db.php");
require_once("functions.php");

require_once("libs/prepareData.php");
require_once("libs/source.php");

require_once("libs/controllers/Controller.php");

require_once("libs/app.php");
