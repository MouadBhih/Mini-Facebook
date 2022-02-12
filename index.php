<?php
include("boite_outils.php");

$connect = connexion();
?>
<html>
<head>
  <title>Accueil</title>
  <script src="fonctions.js"> </script>
</head>
<body>
<?php
print "<h2>Bienvenue $login !</h2>";	
?>  
<h3>Voir les photos de:</h3>
<ul>
<?php
	$requete = "SELECT login FROM utilisateur";
	$resultat = $connect->prepare($requete);
	$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$resultat->execute();
	while ($nuplet = $resultat->fetch(PDO::FETCH_ASSOC)) {
		$personne = $nuplet['login'];
		$personne_param = rawurlencode($personne);
		print '<li><a href="photos_personne.php?personne='
			.$personne_param.'">'.$personne.'</a></li>'."\n";

			
	}
?>
</ul>
<hr>
<h3>Ajouter une photo a ma collection</h3>
<form action="ajoute_photo.php" method="POST" enctype="multipart/form-data" name="add_photo">
	<p>Fichier de la photo: <input type="file" name="photo" size=30></p>
	<p>Description de la photo:</p>
	<p><textarea name="description" rows="10" cols="60">Entrez la description de la photo ici.
	</textarea></p>
	<p>Date de la photo: <? input_date('date_photo','add_photo'); ?></p>
	<p>
	  <input type="submit" value="Ajouter la photo">
	  <input type="reset" value="Annuler">
	</p>
</form>
<hr>
<p><a href="deconnexion.php">Se deconnecter</a>.</p>
</body>
</html>
<?php
    $connect = null;
?>
