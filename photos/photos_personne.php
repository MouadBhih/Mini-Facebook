<?php
// Affiche la liste des photo d'une personne
include('boite_outils.php');

// La personne dont on souhaite afficher les photos
// est pass�e dans le param�tre personne, via la m�thode GET
$personne = $_GET['personne'];

// Connexion au SGBD
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de s�lection de la base: '.mysql_error());

?>
<html>
<head>
  <?php 
  // Dans le titre, on indique le nom de la personne � qui 
  // appartiennent les photos
  print "<title>Photos de $personne</title>"; 
  ?>
</head>
<body>
<h1>Work to do: ajouter les dates dans le r�sum� des photos et trier les photos par dates</h1>
<?php 
// On g�n�re le titre 
print "<h2>Photos de $personne</h2>\n"; 
// On affiche les photos dans une liste num�rot�e
print "<ol>\n";

// La requ�te donne l'identifiant et la description de chaque photo
// de $personne
$requete = 
"SELECT id, description 
 FROM photo 
 WHERE proprietaire = '$personne'";
// Execution de la requ�te
$resultat = mysql_query($requete,$connect)
 	or die("Echec lors de la requ�te: ".$requete.": ".mysql_error());

// On parcours le r�sultat.
// � chaque tour de boucle, $nuplet contient les valeurs pour les
// attributs du n-uplet trait�, sous forme d'un tableau associatif.
while ($nuplet = mysql_fetch_assoc($resultat)) {
	// L'identifiant de la photo
	$id_photo = $nuplet['id'];
	// La description courte est doon�e par les 30 premiers caract�res
	// de la description
	$description_courte = substr(stripslashes($nuplet['description']),0,30);
	if (strlen($nuplet['description']) > 30) {
		// On ajoute ... si la description fait plus de 30 caract�res
		$description_courte = $description_courte.'...';
	}
	// On met un lien vers photo.php en ajoutant dans l'adresse
	// l'identifiant de la photo (utilisation de methode GET pour passer un 
	// param�tre)
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
