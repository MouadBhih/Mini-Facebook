<?php
// Affiche la liste des photo d'une personne
include('boite_outils.php');

// La personne dont on souhaite afficher les photos
// est passée dans le paramètre personne, via la méthode GET
$personne = $_GET['personne'];

// Connexion au SGBD
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de sélection de la base: '.mysql_error());

?>
<html>
<head>
  <?php 
  // Dans le titre, on indique le nom de la personne à qui 
  // appartiennent les photos
  print "<title>Photos de $personne</title>"; 
  ?>
</head>
<body>
<h1>Work to do: ajouter les dates dans le résumé des photos et trier les photos par dates</h1>
<?php 
// On génère le titre 
print "<h2>Photos de $personne</h2>\n"; 
// On affiche les photos dans une liste numérotée
print "<ol>\n";

// La requête donne l'identifiant et la description de chaque photo
// de $personne
$requete = 
"SELECT id, description 
 FROM photo 
 WHERE proprietaire = '$personne'";
// Execution de la requête
$resultat = mysql_query($requete,$connect)
 	or die("Echec lors de la requête: ".$requete.": ".mysql_error());

// On parcours le résultat.
// à chaque tour de boucle, $nuplet contient les valeurs pour les
// attributs du n-uplet traité, sous forme d'un tableau associatif.
while ($nuplet = mysql_fetch_assoc($resultat)) {
	// L'identifiant de la photo
	$id_photo = $nuplet['id'];
	// La description courte est doonée par les 30 premiers caractères
	// de la description
	$description_courte = substr(stripslashes($nuplet['description']),0,30);
	if (strlen($nuplet['description']) > 30) {
		// On ajoute ... si la description fait plus de 30 caractères
		$description_courte = $description_courte.'...';
	}
	// On met un lien vers photo.php en ajoutant dans l'adresse
	// l'identifiant de la photo (utilisation de methode GET pour passer un 
	// paramètre)
	print "<li><a href='photo.php?id=$id_photo'>$description_courte</a></li>";
}

print "</ol>\n";
?>
</body>
</html>
<?
// Fermeture de la connexion au SGBD
mysql_close($connect);
?>
