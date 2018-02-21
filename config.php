<?php
//ob_start("ob_gzhandler");
error_reporting(0);
session_start();

header("Access-Control-Allow-Origin:*"); // อนูญาตให้โปรแกรมอื่นสามารถดึงข้อมูลได้
header("Content-Type: application/json; charset=UTF-8"); // แสดงภาษาไทย

//การกำหนดค่าฐานข้อมูล
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'ionic');	// ชื่อฐานข้อมูล
define("BASE_URL", "http://localhost/ionic/"); // ที่อยู่เว็บ

//เชื่อมต่อกับฐานข้อมูล
function getDB() 
{
	$dbhost=DB_SERVER;
	$dbuser=DB_USERNAME;
	$dbpass=DB_PASSWORD;
	$dbname=DB_DATABASE;
	
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbConnection->exec("set names utf8");
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
}
?>
