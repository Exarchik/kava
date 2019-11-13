<?php

// Create connection
/* OLD PDO
$db = new mysqli($config->host, $config->user, $config->password, $config->db);
$sql = "SET NAMES utf8";
$result = $db->query($sql);
*/

require_once('db/PDODecorator.php');

$db = new PDODecorator($config->host, $config->db, $config->user, $config->password);
