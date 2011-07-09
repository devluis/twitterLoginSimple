<?php
	session_start();
	
	include 'lib/EpiCurl.php';
	include 'lib/EpiOAuth.php';
	include 'lib/EpiTwitter.php';
	
	$consumer_key = 'aqui debes colocar tu consumer key';
	$consumer_secret = 'aqui debes colocar tu consumer secret';
	
	$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
	
	$error = null;
	
	$login  = '<a href="'.$twitterObj->getAuthenticateUrl().'"><img src="img/twitter.png" /></a>';
	
	if (isset ($_GET['oauth_token']) || (isset($_SESSION['oauth_token']) && isset ($_SESSION['oauth_token_secret']) )){
		//acceso
		if (!isset($_SESSION['oauth_token']) || !isset($_SESSION['oauth_token_secret'])){
			//viene de twitter
			$twitterObj->setToken($_GET['oauth_token']);
			$token = $twitterObj->getAccessToken();
			$_SESSION['oauth_token'] = $token->oauth_token;
			$_SESSION['oauth_token_secret'] = $token->oauth_token_secret;
			
			$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
			
		}else{
			//ya nos dio acceso
			$twitterObj->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
		}
		$user = $twitterObj->get_accountVerify_credentials();
		$datos = "Bienvenido " . utf8_decode($user->name) . " Tienes " . $user->followers_count . " seguidores en" . $user->location;
	} elseif (isset ($_GET['denied'])){
		$error = 'Debes permitir acceso a tu cuenta de twitter';
	}
?>
<html>
<head>
<title>Login Twitter</title>
<link rel="stylesheet" href="css/estilos.css" />
</head>

<body>
	<div class="encabezado">Aqui va el encabezado</div>
	<div class="sesion">
		<?php if (isset ($_GET['oauth_token']) || (isset($_SESSION['oauth_token']) && isset ($_SESSION['oauth_token_secret']) )){
			echo $datos;
		}else{
			echo $login;
		}
		echo $error;
		?>
</div>
	<div class="cuerpo">Aqui va el cuerpo de la pagina</div>
</body>
</html>