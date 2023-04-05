<?php
$email = 'd.kreindlin@iberis-pro.ru';
//$email = 'krolss@mail.ru';
$name = 'iberis-group.ru.1vps.tech';
if($email!=''){
	if((isset($_GET['mail'])&&$_GET['mail']!="")&&(isset($_GET['review'])&&$_GET['review']!="")){
			$to = $email;
			$subject = 'Отзыв на iberis-group.ru';
			$message = '
					<html>
						<head>
							<title>'.$subject.'</title>
						</head>
						<body>
							<h3>Имя: '.$_GET["name"].'</h3>
							<h3>Почта: '.$_GET["mail"].'</h2>
							<h3>Отзыв: '.$_GET["review"].'</h2>
						</body>
					</html>';
			$headers  = "Content-type: text/html; charset=utf-8 \r\n";
			$headers .= "From: ".$name." <from@".$name.">\r\n";
			mail($to, $subject, $message, $headers);
	}
}
?>