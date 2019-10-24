<?php
function registrationCorrect() 
{	
	if ($_POST["login"] == "") 
		return false;
		//echo "Введите login. <a href='/'>Исправить</a>"; //не пусто ли поле логина
	if ($_POST["password"] == "")
		//echo "Введите пароль. <a href='/'>Исправить</a>"; //не пусто ли поле пароля
		return false;
	if ($_POST["confirm_password"] == "")
		//echo "Введите второй пароль. <a href='/'>Исправить</a>"; //не пусто ли поле подтверждения пароля
		return false;
	if ($_POST["email"] == "") 
		//echo "Введите email. <a href='/'>Исправить</a>"; //не пусто ли поле e-mail
		return false;
	if ($_POST["firstname"] == "") 
		//echo "Введите имя. <a href='/'>Исправить</a>"; //не пусто ли поле имени
		return false;
	if (strlen($_POST["password"]) < 5) 
		//echo "Пароль должен содержать не меньше 5 символов. <a href='/'>Исправить</a>"; //не меньше ли 5 символов длина пароля
		return false;
 	if ($_POST["password"] != $_POST["confirm_password"]) 
		//echo "Пароли не совпадают. <a href='/'>Исправить</a>"; //равен ли пароль его подтверждению 
		return false;
		//header("Location:index.php"); //если выполнение функции дошло до этого места, возвращаем true
	if (!preg_match('/^([a-z0-9])(\w|[.]|-|_)+([a-z0-9])@([a-z0-9])([a-z0-9.-]*)([a-z0-9])([.]{1})([a-z]{2,4})$/is', $_POST['email'])) 
		return false; //соответствует ли поле e-mail регулярному
	if (findLogin($_POST["login"]))
		return false;
	return true;
}

function createXML($log, $password, $em, $nam, $salt)
{
	$dom = new DomDocument('1.0','utf-8'); //Создает XML-строку и XML-документ при помощи DOM 
	if(file_exists('users.xml'))
	{
		$sxml = simplexml_load_file('users.xml');
		$xml_str = $sxml->asXML();
		$dom->loadXML($xml_str);
		//$dom->loadXML('users.xml');
		
		$user = $dom->createElement('user'); //добавление элемента <user> в <users>
		$users = $dom->getElementsByTagName('users');
		$users[0]->appendChild($user);
		
	}
	else
	{
		$users = $dom->appendChild($dom->createElement('users')); //добавление корня - <users> 
		$user = $users->appendChild($dom->createElement('user')); //добавление элемента <user> в <users>
	} 
	
	$Login = $user->appendChild($dom->createElement('login')); // добавление элемента <login> в <user> 
	$Login->appendChild($dom->createTextNode($log));
	$Password = $user->appendChild($dom->createElement('password'));
	$Password->appendChild($dom->createTextNode($password));
	$Email = $user->appendChild($dom->createElement('email')); 
	$Email->appendChild($dom->createTextNode($em));
	$Firstname = $user->appendChild($dom->createElement('firstname')); 
	$Firstname->appendChild($dom->createTextNode($nam));
	$Salt = $user->appendChild($dom->createElement('salt')); 
	$Salt->appendChild($dom->createTextNode($salt));
	//генерация xml 
	$dom->formatOutput = true; // установка атрибута formatOutput // domDocument в значение true 
	//$test1 = $dom->saveXML(); // save XML as string or file // передача строки в test1 
	$dom->save('users.xml'); // сохранение файла 
	
}

function findLogin($log)
{
	$found = false;
	$userLogin = simplexml_load_file("users.xml");	
	foreach($userLogin->user as $item)
	{
		if($item->login == $log)
		{
			$found = true;
			break;
		}
	}
	return $found;
}

function enterLogin ()
{ 
$error = ""; //массив для ошибок 	
$found=false;
if ($_POST['login'] != "" && $_POST['password'] != "") //если поля заполнены 
{ 		
	$login = $_POST['login']; 
	$password = $_POST['password'];
	
	$userLogin = simplexml_load_file("users.xml");
	foreach($userLogin->user as $item)
	{
		if($item->login == $login && $item->password == md5(md5($password).$item->salt))
		{
			setcookie ("login", $item->login, time() + 50000); 						
		    setcookie ("password", md5($item->login.$item->password), time() + 50000);
			$found = true;
			break;
		}		
	}
	if(!$found)			
	{
		$error = "Неверный логин или пароль"; 										
		return $error; 
	}
} 	
else 	
	{ 		
		$error = "Поля не должны быть пустыми!"; 				
		return $error; 	
	}	
}

function clearCookie () 
{ 	
SetCookie("login", ""); //удаляем cookie с логином 	
SetCookie("password", ""); //удаляем cookie с паролем  	
}

?>