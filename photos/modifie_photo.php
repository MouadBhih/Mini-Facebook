<?php
// Ce fichier affiche un formulaire pour modifier une photo
// Le formulaire est prérempli avec les valeurs actuelles pour la photo
include('boite_outils.php');

// connexion au SGBD
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de sélection de la base: '.mysql_error());
?>
<html>
<head>
  <title>Mise à jour d'une photo</title>
    <!-- La ligne suivante est obligatoire si vous utilisez un formulaire 
       avec des dates (fonction input_date de la boite à outils) -->
  <script  type="text/javascript" language="JavaScript" src="fonctions.js"> </script>
</head>
<body>
<h1>A faire: ajouter un lien "Annuler" pour afficher la page de la photo</h1>
<h1>si vous arriver à comprendre, je vous demande d'ajouter un lien ou un formulaire pour supprimer la photo</h1>
<?php
// L'identifiant de la photo est transmis par la méthode GET
if (isset($_GET['id'])) {
	$id_photo = $_GET['id'];
	
	// La requête récupère les informations sur la photo
	$requete = 
	  "SELECT fichier,date_photo,description,proprietaire 
	   FROM photo 
	   WHERE id = $id_photo";
	// Exécution de la requête
	$resultat = mysql_query($requete,$connect)
			or die("Echec de la requête: $requete: ".mysql_error());
	if ($nuplet = mysql_fetch_assoc($resultat)) {
		// On vérifie que l'utilisateur est bien le propriétaire de la photo
	 	if ($nuplet['proprietaire'] == $login) {
	 		$fichier = $nuplet['fichier'];
	 		$date = $nuplet['date_photo'];
	 		// On enlève les \ ajoutés pour protéger les données lors de 
	 		// l'insertion dans la base
	 		$description = stripslashes($nuplet['description']);
	 		
	 		print "<h2>Modification de la photo</h2>\n";
	 		// On crée un formulaire pour la modification des données
	 		// La mise à jour des données sera effectuée par photo.php
	 		print "<form action='photo.php' method='POST' name='maj'>\n";
	 		// Composant pour modifier la description
	 		// On ajoute $description entre les balises <textarea> de façon
	 		// à préremplir le composant avec la description courante
	 		print "<p><textarea name='description' rows='10' cols='60'>$description</textarea></p>\n";
	 		print "<p>Date de la photo: ";
	 		// Crée un composant pour saisir la date
	 		// Le paramètre ou elle sera stockée est date_photo
	 		// On ajoute le nom du formulaire comme deuxième argument
	 		// On utilise le troisième argument optionnel pour préremplir le 
	 		// composant avec la date courante.
	 		input_date('date_photo','maj',$date);
	 		print "</p>\n";
	 		// On ajoute deux composant invisibles pour les paramètres
	 		//  id (identifant de la photo) et but (qui indique l'opération à 
	 		// effectuer dans photo.php).
	 		print "<input type='hidden' name='id' value='$id_photo'>";
	 		print "<input type='hidden' name='but' value='maj'>";
	 		// Les boutons de soumission et de réinitialisation du formulaire
	 		print "<p><input type='submit' value='Envoyer'> <input type='reset' value='Annuler les changements'></p>\n";
	 		print "</form>\n";
	 		// On affiche l'image après le formulaire
	 		print "<p><img src='$fichier'></p>";
	 	} else {
	 		print "<p><b>Vous ne pouvez pas modifier les informations de cette photo !</b></p>";
		}
  } else {
  	print 'a'.$nuplet.'b';
	  print "<p><b>Photo non existante !</b></p>";
  }
} else {
	print "<p><b>Photo non spécifiée !</b></p>";
}
?>
</body>
</html>
<?php
mysql_close($connect);
?>