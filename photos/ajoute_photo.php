<?php
include('boite_outils.php');
include('mesfonctions.php');

// Ce fichier a pour fonction d'ajouter les données concernant 
// une photo dans la base de données et de sauver cette photo sur le disque 
// du serveur.
// Enfin la page affiche la photo avec ses informations, ce qui permet à 
// l'utilisateur de vérifier sa saisie.
?>
<html>
<head><title>Ajout de la photo ...</title></head>
<body>
<?php
	// Les paramètres sont envoyés avec la méthode POST
	// On les stocke dans des variables sauf le fichier
	// qui est traité directement par sauve_photo()
	// à laquelle on passe le nom du paramètre
	// contenant la photo.
	
	// La fonction isset() permet de vérifier que le paramètre a bien été défini.
	
	// La fonction addslashes permet d'ajouter des \ avant les caractères 
	// spéciaux afin d'éviter les problèmes lors de l'insertion des données 
	// dans la base.
	
	// La fonction verifie_date() permet de vérifier et de corriger la forme
	// de la date
	
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
		
	// La fonction sauve_photo sauvegarde le fichier stocké dans le paramètre 
	// photo et renvoie le nom du fichier sur le serveur ou null en cas d'erreur.
	$fichier = sauve_photo('photo');
	
	// Si il n'y a pas d'erreur:
	if ($fichier != null) {
		// On ouvre une connexion au SGBD
		$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
			or die('Echec de connection au SGBD: '.mysql_error());
		mysql_select_db($bd_base,$connect) 
			or die('Echec de sélection de la base: '.mysql_error());
		
		// La requête d'insertion ne donne pas de valeur pour l'identifiant 
		// de la photo: celui-ci est automatiquement généré par le SGBD.
		// Le propriétaire de la photo est celui qui l'a ajoutée, c'est-à-dire
		// $login.
		$requete = 
		"INSERT INTO photo(fichier,date_photo,description,proprietaire)
		 VALUES('$fichier','$date_photo','$description','$login')";
		mysql_query($requete,$connect) 
		  	or die("Echec de la requête: $requete: ".mysql_error());
		// Comme la requête est une insertion, il est inutile de parcourir 
		// le résultat: on se contente de l'exécuter.
		
		// On affiche la photo ajoutée grâce à la fonction affiche_photo.
		// Il faut retirer les \ ajoutés à la description pour l'affichage
		// (avec la fonction stripslashes()).
		// Si vous avez des erreurs d'affichage sur cette page (des \ qui 
		// apparaissent), ajoutez un deuxième appel à stripslashes().
		print "<h3>Photo ajoutée:</h3>";
		affiche_photo($login,$date_photo,stripslashes($description),$fichier);
	}	else {
		print "<p><b>Echec de l'ajout de la photo !!!</b></p>";
	}
?>
<hr>
<p><a href='index.php'>Retour à l'accueil</a></p>
</body>
</html>
<?php
// fermeture de la connection au SGBD
mysql_close($connect);
?>