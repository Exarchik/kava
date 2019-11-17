<?php
require_once 'config.php';

class KavaConfig {
	public $host;
    public $user;
    public $password;
    public $db;
	public $base_link;
	
	// HTTP BASIC AUTHENTICATION логин/пароль от админки
	public $basicLogin;
	public $basicPassword;

	public function __construct($host, $user, $password, $db, $base_link, $basicLogin, $basicPassword)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->db = $db;
		$this->base_link = $base_link;

		$this->basicLogin = $basicLogin;
		$this->basicPassword = $basicPassword;
	}
}

$config = new KavaConfig($cfg_host, $cfg_user, $cfg_password, $cfg_db, $cfg_base_link, $cfg_basicLogin, $cfg_basicPassword);

require_once("const.php");

require_once(_LIBS.'db'.DS.'PDODecorator.php');

$db = new PDODecorator($config->host, $config->db, $config->user, $config->password);

require_once("functions.php");

require_once(_LIBS.'DataHelper.php');

require_once(_LIBS."prepareData.php");
require_once(_LIBS."source.php");

require_once(_LIBS."controllers/Controller.php");

require_once(_LIBS."app.php");
