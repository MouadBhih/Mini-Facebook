<?php
include('boite_outils.php');
include('mesfonctions.php');
// Ce fichier a trois usages: le principal est la visualisation d'une photo.
// Celle-ci peut être complétée par la modifications des données d'une photo
// ou par l'ajout d'un commentaire.

// Connexion au SGBD
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de sélection de la base: '.mysql_error());
	
// La première étape consiste à traiter les éventuelles
// modifications de données à effectuer.
// Dans le cas d'un ajout de commentaire aussi bien que dans le cas d'une 
// modification des informations de la photo, les données sont envoyées du 
// formulaire sont envoyées par la méthode POST. Dans les deux cas, un 
// paramètre appelé 'but' permet de savoir le type de modification à effectuer 
// ('ajout_commentaire' pour un commentaire et 'maj' pour une mise à jour des 
// informations de la photo. Une fois les modifcations éventuelles effectuées, 
// la page de la photo est affichée normalement (et donc avec les données/
// commentaires à jour)
	
// si le paramètre 'but' a la valeur 'ajout_commentaire'	
// et a été transmis par la méthode POST alors il faut ajouter un commentaire
//
if (isset($_POST['but']) && $_POST['but'] == 'ajout_commentaire') {
	// On récupère toutes les infos via la méthode POST
	$id_photo = $_POST['id'];
	$contenu = addslashes($_POST['contenu']);
	if ($id_photo != null) {
		// remarque: l'id du commentaire et sa date de dépôt sont automatiquement
		// calculés par le SGBD
		// La requête insère le commentaire dans la base
		// L'auteur est l'utilisateur courant, à savoir $login
		$requete =
		"INSERT INTO commentaire(contenu,id_photo,auteur)
		 VALUES ('$contenu',$id_photo,'$login')";
		// On effectue la requête. Comme c'est un ajout de donnée, il n'y a 
		// pas de résultat à traiter.
		mysql_query($requete,$connect)
	  	or die("Echec de la requête: $requete: ".mysql_error());
	}
// si le paramètre 'but' a la valeur 'maj'	
// et a été transmis par la méthode POST alors il modifier 
// les informations de la photo
//
} else if (isset($_POST['but']) && $_POST['but'] == 'maj') {
	// On récupère toutes les infos via la méthode POST
	$id_photo = $_POST['id'];
	// On vérifie la forme de la date grâce à la fonction verifie_date de la 
	// boîte à outils
	$date = verifie_date($_POST['date_photo']);
	// On protège la description (traitement des caractères spéciaux)
	$description = addslashes($_POST['description']);
	if ($id_photo != null) {
		// La requête mets à jour les informations de la photo identifiée par 
		// $id_photo avec les nouvelles valeurs pour la description et la date
		$requete =
		"UPDATE photo
		 SET description = '$description',
		     date_photo = '$date'
		 WHERE id = $id_photo";
		// On effectue la requête. Comme c'est une mise à jour, il n'y a 
		// pas de résultat à traiter.
		mysql_query($requete,$connect)
	  	or die("Echec de la requête: $requete: ".mysql_error());
	}
} else {
	// sinon l'identifiant de la photo est passé en utilisant la méthode GET
	// et on se contente d'afficher la photo
	$id_photo = $_GET['id'];
}

?>
<html>
<head><title>Photo</title></head>
<body>
<h1>Travail à complété: faire du nom du propriétaire un lien vers la liste de ses photos. Faire de même pour les personnes qui ont posté des commentaires</h1>
<h1>Travail to do: si la personne qui regarde la page est le propriétaire de la photo, ajouter un lien ou un formulaire à côté de chaque commentaire permettant de supprimer ce commentaire</h1>
<?php
  // Affichage de la photo
	if ($id_photo == null) {
		print "<p><b>Aucune photo de spécifiée!</b></p>";
	} else {
		// La requête va chercher les informations sur la photo
	  $requete = 
	  "SELECT fichier,date_photo,description,proprietaire 
	   FROM photo 
	   WHERE id = $id_photo";
	  // On exécute la requête
	  $resultat = mysql_query($requete,$connect)
	  	or die("Echec de la requête: $requete: ".mysql_error());
	  // Le résultat à au plus une réponse.
	  // On peut donc se contenter d'un if au lieu du while.
	  // Il suffit en effet de tester s'il n'est pas vide, c'est à dire s'il a au 
	  // moins un résultat.
	  if ($nuplet = mysql_fetch_assoc($resultat)) {
	  	$proprietaire = $nuplet['proprietaire'];
	  	// On utilise la fonction affiche_photo de mesfonctions.php pour 
	  	// l'affichage
	  	// La variable $proprietaire sera réutilisée à la fin du fichier
	  	// On utilise la fonction stripslashes pour enlever les \ (qui ont été 
	  	// ajoutés pout traiter les caractères spéciaux).
	  	affiche_photo(
	  			$proprietaire,
	  			$nuplet['date_photo'],
	  			stripslashes($nuplet['description']),
	  			$nuplet['fichier']);
	    // Cette requête récupère les commentaires sur la photo affichée
	  	$requete = 
	  	"SELECT auteur, contenu, depot
	  	 FROM commentaire
	  	 WHERE id_photo = $id_photo";
	  	// Execution de la requête
	  	$resultat = mysql_query($requete,$connect)
		  	or die("Echec de la requête: $requete: ".mysql_error());
		  // Pour chaque commentaire, on l'affiche avec la fonction 
		  // affiche_commentaire définie dans mesfonctions.php
		  // On utilise stripslashes pour enlever les \ des commentaires.
	  	while ($nuplet = mysql_fetch_assoc($resultat)) {
	  		affiche_commentaire(
	  				$nuplet['auteur'],
	  				$nuplet['depot'],
	  				stripslashes($nuplet['contenu']));
	  	}
	  	
	  	// Création d'un formulaire pour ajouter un commentaire
	  	// après l'affichage de tous les commentaires de la photo:
	  	print "<div>";
	  	// Le fichier effectuant l'ajout est photo.php (cf début du fichier)
	  	print "<form action='photo.php' method='POST'>\n";
	  	print "<h3>Ajouter un commentaire</h3>";
	  	// Le commentaire qui sera stocké dans le paramètre contenu
	  	print "<p><textarea name='contenu' rows='10' cols='60'></textarea></p>\n";
	  	// On ajoute deux composants invisibles pour les paramètres
	 		// id (identifant de la photo) et but (qui indique l'opération à 
	 		// effectuer dans photo.php).
	  	print "<input type='hidden' name='but' value='ajout_commentaire'>";
	  	print "<input type='hidden' name='id' value='$id_photo'>";
	  	// Les boutons de soumissions et vde remise à zero
	  	print "<p><input type='submit' value='Ajouter'><input type='reset' value='Vider'></p>\n";
	  	print "</form>";
	  	print "</div>\n";
	  } else {
	  	print "<p><b>Cette photo n'existe pas!</b></p>";
	  }
	} 
?>
<hr>
<?php
// Si le propriétaire de la photo est l'utilisateur ($login),
// On ajoute un lien pour modifier la photo
// L'identifiant de la photo est indiqué dans l'adresse du lien (méthode GET)
if ($proprietaire == $login) {
	print "<p><a href='modifie_photo.php?id=$id_photo'>Modifier les informations sur cette photo</a>.</p>";
}
?>
<p><a href='index.php'>Retour à l'accueil</a></p>
</body>
</html>
<?php
mysql_close($connect);
?>
