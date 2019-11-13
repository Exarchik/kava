<?php

require_once("configuration.php");

$admLogin = $config->basicLogin;
$admPass = $config->basicPassword;

if (isset($_REQUEST['l']) && $_REQUEST['l'] == 'logout') {
    unset($_SERVER['PHP_AUTH_PW']);
}

if ($admLogin != $_SERVER['PHP_AUTH_USER'] || $admPass != $_SERVER['PHP_AUTH_PW']) {
    header('WWW-Authenticate: Basic realm="Kava"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Необходима авторизация");
}

require_once("libs/sourceAdmin.php");
$sourceAdmin = new SourceAdmin($db, new PrepareData($db), $config, $_REQUEST);

$controller = new Controller($db);
$app = new App($db, App::APP_TYPE_ADMIN);
$app->process();