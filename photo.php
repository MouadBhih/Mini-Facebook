<?php
include('boite_outils.php');
include('mesfonctions.php');
// Ce fichier a trois usages: le principal est la visualisation d'une photo.
// Celle-ci peut �tre compl�t�e par la modifications des donn�es d'une photo
// ou par l'ajout d'un commentaire.

// Connexion au SGBD
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de s�lection de la base: '.mysql_error());
	
// La premi�re �tape consiste � traiter les �ventuelles
// modifications de donn�es � effectuer.
// Dans le cas d'un ajout de commentaire aussi bien que dans le cas d'une 
// modification des informations de la photo, les donn�es sont envoy�es du 
// formulaire sont envoy�es par la m�thode POST. Dans les deux cas, un 
// param�tre appel� 'but' permet de savoir le type de modification � effectuer 
// ('ajout_commentaire' pour un commentaire et 'maj' pour une mise � jour des 
// informations de la photo. Une fois les modifcations �ventuelles effectu�es, 
// la page de la photo est affich�e normalement (et donc avec les donn�es/
// commentaires � jour)
	
// si le param�tre 'but' a la valeur 'ajout_commentaire'	
// et a �t� transmis par la m�thode POST alors il faut ajouter un commentaire
//
if (isset($_POST['but']) && $_POST['but'] == 'ajout_commentaire') {
	// On r�cup�re toutes les infos via la m�thode POST
	$id_photo = $_POST['id'];
	$contenu = addslashes($_POST['contenu']);
	if ($id_photo != null) {
		// remarque: l'id du commentaire et sa date de d�p�t sont automatiquement
		// calcul�s par le SGBD
		// La requ�te ins�re le commentaire dans la base
		// L'auteur est l'utilisateur courant, � savoir $login
		$requete =
		"INSERT INTO commentaire(contenu,id_photo,auteur)
		 VALUES ('$contenu',$id_photo,'$login')";
		// On effectue la requ�te. Comme c'est un ajout de donn�e, il n'y a 
		// pas de r�sultat � traiter.
		mysql_query($requete,$connect)
	  	or die("Echec de la requ�te: $requete: ".mysql_error());
	}
// si le param�tre 'but' a la valeur 'maj'	
// et a �t� transmis par la m�thode POST alors il modifier 
// les informations de la photo
//
} else if (isset($_POST['but']) && $_POST['but'] == 'maj') {
	// On r�cup�re toutes les infos via la m�thode POST
	$id_photo = $_POST['id'];
	// On v�rifie la forme de la date gr�ce � la fonction verifie_date de la 
	// bo�te � outils
	$date = verifie_date($_POST['date_photo']);
	// On prot�ge la description (traitement des caract�res sp�ciaux)
	$description = addslashes($_POST['description']);
	if ($id_photo != null) {
		// La requ�te mets � jour les informations de la photo identifi�e par 
		// $id_photo avec les nouvelles valeurs pour la description et la date
		$requete =
		"UPDATE photo
		 SET description = '$description',
		     date_photo = '$date'
		 WHERE id = $id_photo";
		// On effectue la requ�te. Comme c'est une mise � jour, il n'y a 
		// pas de r�sultat � traiter.
		mysql_query($requete,$connect)
	  	or die("Echec de la requ�te: $requete: ".mysql_error());
	}
} else {
	// sinon l'identifiant de la photo est pass� en utilisant la m�thode GET
	// et on se contente d'afficher la photo
	$id_photo = $_GET['id'];
}

?>
<html>
<head><title>Photo</title></head>
<body>
<h1>A faire: faire du nom du propri�taire un lien vers la liste de ses photos. Faire de m�me pour les personnes qui ont post� des commentaires</h1>
<h1>A faire: si la personne qui regarde la page est le propri�taire de la photo, ajouter un lien ou un formulaire � c�t� de chaque commentaire permettant de supprimer ce commentaire</h1>
<?php
  // Affichage de la photo
	if ($id_photo == null) {
		print "<p><b>Aucune photo de sp�cifi�e!</b></p>";
	} else {
		// La requ�te va chercher les informations sur la photo
	  $requete = 
	  "SELECT fichier,date_photo,description,proprietaire 
	   FROM photo 
	   WHERE id = $id_photo";
	  // On ex�cute la requ�te
	  $resultat = mysql_query($requete,$connect)
	  	or die("Echec de la requ�te: $requete: ".mysql_error());
	  // Le r�sultat � au plus une r�ponse.
	  // On peut donc se contenter d'un if au lieu du while.
	  // Il suffit en effet de tester s'il n'est pas vide, c'est � dire s'il a au 
	  // moins un r�sultat.
	  if ($nuplet = mysql_fetch_assoc($resultat)) {
	  	$proprietaire = $nuplet['proprietaire'];
	  	// On utilise la fonction affiche_photo de mesfonctions.php pour 
	  	// l'affichage
	  	// La variable $proprietaire sera r�utilis�e � la fin du fichier
	  	// On utilise la fonction stripslashes pour enlever les \ (qui ont �t� 
	  	// ajout�s pout traiter les caract�res sp�ciaux).
	  	affiche_photo(
	  			$proprietaire,
	  			$nuplet['date_photo'],
	  			stripslashes($nuplet['description']),
	  			$nuplet['fichier']);
	    // Cette requ�te r�cup�re les commentaires sur la photo affich�e
	  	$requete = 
	  	"SELECT auteur, contenu, depot
	  	 FROM commentaire
	  	 WHERE id_photo = $id_photo";
	  	// Execution de la requ�te
	  	$resultat = mysql_query($requete,$connect)
		  	or die("Echec de la requ�te: $requete: ".mysql_error());
		  // Pour chaque commentaire, on l'affiche avec la fonction 
		  // affiche_commentaire d�finie dans mesfonctions.php
		  // On utilise stripslashes pour enlever les \ des commentaires.
	  	while ($nuplet = mysql_fetch_assoc($resultat)) {
	  		affiche_commentaire(
	  				$nuplet['auteur'],
	  				$nuplet['depot'],
	  				stripslashes($nuplet['contenu']));
	  	}
	  	
	  	// Cr�ation d'un formulaire pour ajouter un commentaire
	  	// apr�s l'affichage de tous les commentaires de la photo:
	  	print "<div>";
	  	// Le fichier effectuant l'ajout est photo.php (cf d�but du fichier)
	  	print "<form action='photo.php' method='POST'>\n";
	  	print "<h3>Ajouter un commentaire</h3>";
	  	// Le commentaire qui sera stock� dans le param�tre contenu
	  	print "<p><textarea name='contenu' rows='10' cols='60'></textarea></p>\n";
	  	// On ajoute deux composants invisibles pour les param�tres
	 		// id (identifant de la photo) et but (qui indique l'op�ration � 
	 		// effectuer dans photo.php).
	  	print "<input type='hidden' name='but' value='ajout_commentaire'>";
	  	print "<input type='hidden' name='id' value='$id_photo'>";
	  	// Les boutons de soumissions et vde remise � zero
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
// Si le propri�taire de la photo est l'utilisateur ($login),
// On ajoute un lien pour modifier la photo
// L'identifiant de la photo est indiqu� dans l'adresse du lien (m�thode GET)
if ($proprietaire == $login) {
	print "<p><a href='modifie_photo.php?id=$id_photo'>Modifier les informations sur cette photo</a>.</p>";
}
?>
<p><a href='index.php'>Retour � l'accueil</a></p>
</body>
</html>
<?php
mysql_close($connect);
?>
