<!DOCTYPE html> <!-- page est Ã©crite en HTML5 -->
<html>
	<head> <!-- En-tÃªte de page, les commentaires sont lisibles version code -->
		<meta charset="utf-8" /> <!-- L'encodage (charset)-->
		<title>BBC Poker</title> <!-- le titre s'affiche dans l'onglet -->
		<link href="exp1/homestyle.css" rel="stylesheet" media="all" type="text/css" /> 
	</head>
	<body> <!-- corps de page -->
<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);

$regulartaskcount=1;
include "exp2/regulartasks.php";
include "exp5/nav1.php";
?>			

	<h1>Welcome to the BBC website!</h1>
	<p><a href="http://www.pokerth.net/" title="Visit PokerTH" ><img src="exp1/NavBBC/pokerthLogo.png" /></a><br><br>
	<a href="http://bbc.pokerth.net/login.php" title="Login"  class="link">Login</a><br><br />
	<a href="http://www.pokerth.net/download.html" class="link" title="Download">PokerTH download</a>
	</p>
	<a href="/exp1/BBChalloffame3.php" title="Hall of Fame" ><img src="exp1/NavBBC/HoF.png" /></a> 
</body>
</html>