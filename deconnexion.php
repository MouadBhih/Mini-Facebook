<?php
include('boite_outils.php');
deconnexion();
// On charge la page login.php
charger_page("index.php");
// Au cas ou la redirection ne marche pas, on met un lien vers l'accueil
?>
<html>
<head><title>D�connexion</title></head>
<body>
	<p>Vous �tes d�connect�s.</p>
	<p><a href='index.php'>Revenir � l'accueil</a>.</p>
</body>
</html>
