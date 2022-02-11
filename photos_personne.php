<?php
include('boite_outils.php');

$personne = $_GET['personne'];

$connect = connexion();

?>
<html>
<head>
  <?php
  print "<title>Photos de $personne</title>"; 
  ?>
</head>
<body>
<h1>A faire: ajouter les dates dans le r�sum� des photos et trier les photos par dates</h1>
<?php 
print "<h2>Photos de $personne</h2>\n"; 
print "<ol>\n";
$requete = "SELECT id, description FROM photo WHERE proprietaire = '$personne'";
$resultat = $connect->prepare($requete);
$resultat->execute();

while ($nuplet = $resultat->fetch(PDO::FETCH_ASSOC)) {
	$id_photo = $nuplet['id'];
	$description_courte = substr(stripslashes($nuplet['description']),0,30);
	if (strlen($nuplet['description']) > 30) {
		$description_courte = $description_courte.'...';
	}
	print "<li><a href='photo.php?id=$id_photo'>$description_courte</a></li>";
}

print "</ol>\n";
?>
</body>
</html>
<?php
$connect = null;
?>
