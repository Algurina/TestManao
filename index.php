<?php
include ('regist.php');
$regcorrect = registrationCorrect();
if ($regcorrect)
{
	$salt = mt_rand(1000, 9999);
	$password = $_POST['password'];
	$password = md5(md5($password).$salt);
	$log = $_POST['login'];
	$em = $_POST['email'];
	$nam = $_POST['firstname'];
	createXML($log, $password, $em, $nam, $salt); 
	include_once ("login.html");
	//echo "Поздравляем!Регистрация прошла успешно!";//"Здравствуйте, <b>". $nam ."</b>!<br/> Ваш логин <b>". $log . "</b>.";
}
else
	include_once ("index.html");
	//echo "Данные некорректны";
?>
