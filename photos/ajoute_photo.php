<?php
include('boite_outils.php');
include('mesfonctions.php');

// Ce fichier a pour fonction d'ajouter les donn�es concernant 
// une photo dans la base de donn�es et de sauver cette photo sur le disque 
// du serveur.
// Enfin la page affiche la photo avec ses informations, ce qui permet � 
// l'utilisateur de v�rifier sa saisie.
?>
<html>
<head><title>Ajout de la photo ...</title></head>
<body>
<?php
	// Les param�tres sont envoy�s avec la m�thode POST
	// On les stocke dans des variables sauf le fichier
	// qui est trait� directement par sauve_photo()
	// � laquelle on passe le nom du param�tre
	// contenant la photo.
	
	// La fonction isset() permet de v�rifier que le param�tre a bien �t� d�fini.
	
	// La fonction addslashes permet d'ajouter des \ avant les caract�res 
	// sp�ciaux afin d'�viter les probl�mes lors de l'insertion des donn�es 
	// dans la base.
	
	// La fonction verifie_date() permet de v�rifier et de corriger la forme
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
		
	// La fonction sauve_photo sauvegarde le fichier stock� dans le param�tre 
	// photo et renvoie le nom du fichier sur le serveur ou null en cas d'erreur.
	$fichier = sauve_photo('photo');
	
	// Si il n'y a pas d'erreur:
	if ($fichier != null) {
		// On ouvre une connexion au SGBD
		$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
			or die('Echec de connection au SGBD: '.mysql_error());
		mysql_select_db($bd_base,$connect) 
			or die('Echec de s�lection de la base: '.mysql_error());
		
		// La requ�te d'insertion ne donne pas de valeur pour l'identifiant 
		// de la photo: celui-ci est automatiquement g�n�r� par le SGBD.
		// Le propri�taire de la photo est celui qui l'a ajout�e, c'est-�-dire
		// $login.
		$requete = 
		"INSERT INTO photo(fichier,date_photo,description,proprietaire)
		 VALUES('$fichier','$date_photo','$description','$login')";
		mysql_query($requete,$connect) 
		  	or die("Echec de la requ�te: $requete: ".mysql_error());
		// Comme la requ�te est une insertion, il est inutile de parcourir 
		// le r�sultat: on se contente de l'ex�cuter.
		
		// On affiche la photo ajout�e gr�ce � la fonction affiche_photo.
		// Il faut retirer les \ ajout�s � la description pour l'affichage
		// (avec la fonction stripslashes()).
		// Si vous avez des erreurs d'affichage sur cette page (des \ qui 
		// apparaissent), ajoutez un deuxi�me appel � stripslashes().
		print "<h3>Photo ajout�e:</h3>";
		affiche_photo($login,$date_photo,stripslashes($description),$fichier);
	}	else {
		print "<p><b>Echec de l'ajout de la photo !!!</b></p>";
	}
?>
<hr>
<p><a href='index.php'>Retour � l'accueil</a></p>
</body>
</html>
<?php
// fermeture de la connection au SGBD
mysql_close($connect);
?>