<?php
include("boite_outils.php");
include("mesfonctions.php");
?>
<html>
<head><title>Ajout de la photo ...</title></head>
<body>
<?php
	if (isset($_POST['description'])) {
		$description = addslashes($_POST['description']);
	} else {
		$description = "";
	}

	if (isset($_POST['date_photo'])) {
		$date_photo = verifie_date($_POST['date_photo']);
	} else {
		$date_photo = date('Y-m-d');
	}
		
	$fichier = sauve_photo('photo');
	
	if ($fichier != null) {
		$connect = connexion();
        if(isset($login)){
		    $requete = "INSERT INTO photo(fichier,date_photo,description,proprietaire) VALUES ('$fichier','$date_photo','$description','$login')";
        }
        $resultat = $connect->prepare($requete);
        $resultat->execute();
		print "<h3>Photo ajoutee:</h3>";
		affiche_photo($login,$date_photo,stripslashes($description),$fichier);
	}	else {
		print "<p><b>Echec de l'ajout de la photo !!!</b></p>";
	}
?>
<hr>
<p><a href='index.php'>Retour a l'accueil</a></p>
</body>
</html>
<?php
$connect = null;
?>