<?php
include ('regist.php');
$checkLogin = enterLogin();
if ($checkLogin != "")
{
echo ($checkLogin);
	include_once ("login.html");
}
else 
	echo "Вы вошли в систему!";
?>