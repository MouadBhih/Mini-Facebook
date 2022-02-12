<?php
if(isset($_POST["from_date"], $_POST["to_date"]))
{
include('boite_outils.php');

$personne = $_GET['personne'];

$connect = connexion();
$output = '';
print "<ol >\n";
$query ="SELECT * FROM photo WHERE date_photo BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."'";
$resultat = $connect->prepare($query);
$resultat->execute();
/*if (mysqli_num_rows($resultat) > 0)
{*/
while ($nuplet = $resultat->fetch(PDO::FETCH_ASSOC)) {
	$id_photo = $nuplet['id'];
	$description_courte = substr(stripslashes($nuplet['description']),0,30);
	if (strlen($nuplet['description']) > 30) {
		$description_courte = $description_courte.'...';
	}
	print "<li><a href='photo.php?id=$id_photo'>$description_courte</a></li>";
}
//}
/*else{
    echo "nothing";
}*/

print "</ol>\n";


                                


}
?>