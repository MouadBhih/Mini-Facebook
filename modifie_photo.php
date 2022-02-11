<?php
// Ce fichier affiche un formulaire pour modifier une photo
// Le formulaire est pr�rempli avec les valeurs actuelles pour la photo
include('boite_outils.php');

// connexion au SGBD
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de s�lection de la base: '.mysql_error());
?>
<html>
<head>
  <title>Mise � jour d'une photo</title>
    <!-- La ligne suivante est obligatoire si vous utilisez un formulaire 
       avec des dates (fonction input_date de la boite � outils) -->
  <script  type="text/javascript" language="JavaScript" src="fonctions.js"> </script>
</head>
<body>
<h1>A faire: ajouter un lien "Annuler" pour afficher la page de la photo</h1>
<h1>A faire: ajouter un lien ou un formulaire pour supprimer la photo</h1>
<?php
// L'identifiant de la photo est transmis par la m�thode GET
if (isset($_GET['id'])) {
	$id_photo = $_GET['id'];
	
	// La requ�te r�cup�re les informations sur la photo
	$requete = 
	  "SELECT fichier,date_photo,description,proprietaire 
	   FROM photo 
	   WHERE id = $id_photo";
	// Ex�cution de la requ�te
	$resultat = mysql_query($requete,$connect)
			or die("Echec de la requ�te: $requete: ".mysql_error());
	if ($nuplet = mysql_fetch_assoc($resultat)) {
		// On v�rifie que l'utilisateur est bien le propri�taire de la photo
	 	if ($nuplet['proprietaire'] == $login) {
	 		$fichier = $nuplet['fichier'];
	 		$date = $nuplet['date_photo'];
	 		// On enl�ve les \ ajout�s pour prot�ger les donn�es lors de 
	 		// l'insertion dans la base
	 		$description = stripslashes($nuplet['description']);
	 		
	 		print "<h2>Modification de la photo</h2>\n";
	 		// On cr�e un formulaire pour la modification des donn�es
	 		// La mise � jour des donn�es sera effectu�e par photo.php
	 		print "<form action='photo.php' method='POST' name='maj'>\n";
	 		// Composant pour modifier la description
	 		// On ajoute $description entre les balises <textarea> de fa�on
	 		// � pr�remplir le composant avec la description courante
	 		print "<p><textarea name='description' rows='10' cols='60'>$description</textarea></p>\n";
	 		print "<p>Date de la photo: ";
	 		// Cr�e un composant pour saisir la date
	 		// Le param�tre ou elle sera stock�e est date_photo
	 		// On ajoute le nom du formulaire comme deuxi�me argument
	 		// On utilise le troisi�me argument optionnel pour pr�remplir le 
	 		// composant avec la date courante.
	 		input_date('date_photo','maj',$date);
	 		print "</p>\n";
	 		// On ajoute deux composant invisibles pour les param�tres
	 		//  id (identifant de la photo) et but (qui indique l'op�ration � 
	 		// effectuer dans photo.php).
	 		print "<input type='hidden' name='id' value='$id_photo'>";
	 		print "<input type='hidden' name='but' value='maj'>";
	 		// Les boutons de soumission et de r�initialisation du formulaire
	 		print "<p><input type='submit' value='Envoyer'> <input type='reset' value='Annuler les changements'></p>\n";
	 		print "</form>\n";
	 		// On affiche l'image apr�s le formulaire
	 		print "<p><img src='$fichier'></p>";
	 	} else {
	 		print "<p><b>Vous ne pouvez pas modifier les informations de cette photo !</b></p>";
		}
  } else {
  	print 'a'.$nuplet.'b';
	  print "<p><b>Photo non existante !</b></p>";
  }
} else {
	print "<p><b>Photo non sp�cifi�e !</b></p>";
}
?>
</body>
</html>
<?php
mysql_close($connect);
?>