<?php
function affiche_photo($proprietaire,$date_photo,$description,$fichier) {
	print "<div>\n";
	print "<p align='center'><img src='$fichier'></p>\n";
	$date = strtotime($date_photo);
	$date_affichee = date('d/m/Y',$date);
	print "<p>Photo de $proprietaire prise le $date_affichee.</p>\n";
	print "<p>$description</p>\n";
	print "</div>\n";	
}

function affiche_commentaire($auteur,$date_commentaire,$contenu) {
	print "<div>\n";
	print "<p><b>$auteur</b> ($date_commentaire):</p>\n";
	print "<p>$contenu</p>";
	print "</div>";
}
?>