<?php

$conf_path = "../configuration.php";
if (file_exists($conf_path)){
	require_once($conf_path);
	$_CONF 			= new KavaConfig();
	
	// 'persons','napoi','kava'
	$_TYPE			= $_GET['type']?$_GET['type']:'persons';
	
	$servername 	= $_CONF->host;			//"localhost"; 
	$dbname	   		= $_CONF->db;			//"deps17";
	$username 		= $_CONF->user;			//"u_deps17";
	$password 		= $_CONF->password;		//"vxzIEgUH";
	//$prefix			= $_CONF->dbprefix;		//"vjprf_";
	//$live_site		= $_CONF->live_site;	//"http://deps.ua"; 

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = "SET NAMES utf8";
	$result = $conn->query($sql);

	if ($_TYPE=='persons'){
		$sql = "SELECT * FROM `_kava_persons` ";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			header("Content-Type: script/javascript;  charset=utf-8");
			// output data of each row
			echo "var surnames = [\n\r";
			while($row = $result->fetch_assoc()) {
				echo "'".$row['fio']."',\n\r"; 
			}
			echo "];";
		}
	}elseif ($_TYPE=='napoi'){
		$sql = "SELECT * FROM `_kava_foodrink` WHERE `type`='napoi' ";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			header("Content-Type: script/javascript;  charset=utf-8");
			// output data of each row
			echo "var napoi = [\n\r";
			while($row = $result->fetch_assoc()) {
				echo " {name: '".$row['name']."', price: ".$row['price'].", img: '".$row['img']."' },\n\r";
			}
			echo "];";
		}
	}elseif ($_TYPE=='snack'){
		$sql = "SELECT * FROM `_kava_foodrink` WHERE `type`='snack' ";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			header("Content-Type: script/javascript;  charset=utf-8");
			// output data of each row
			echo "var snacks = [\n\r";
			while($row = $result->fetch_assoc()) {
				echo " {name: '".$row['name']."', price: ".$row['price'].", img: '".$row['img']."' },\n\r";
			}
			echo "];";
		}
	}
}

?>