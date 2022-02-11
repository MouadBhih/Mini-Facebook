<?php
include('boite_outils.php');
deconnexion();
// On charge la page login.php
charger_page("index.php");
// Au cas ou la redirection ne marche pas, on met un lien vers l'accueil
?>
<html>
<head><title>Déconnexion</title></head>
<body>
	<p>Vous êtes déconnectés.</p>
	<p><a href='index.php'>Revenir à l'accueil</a>.</p>
</body>
</html>
