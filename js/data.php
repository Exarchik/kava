<?php
header("Content-Type: script/javascript; charset=utf-8");

$conf_path = "../configuration.php";

if (file_exists($conf_path)){
	require_once($conf_path);

    $preperaData = new PrepareData($db);

    echo "var napoi = ".$preperaData->getNapoiData(true)."\n";
    echo "var snack = ".$preperaData->getSnackData(true)."\n";
    echo "var books = ".$preperaData->getBooksData(true)."\n";
    echo "var surnames = ".$preperaData->getPersonsData(true)."\n";
}

echo "var access_key = 'fG78tgio1e';";
