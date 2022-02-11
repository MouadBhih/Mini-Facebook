<?php
// Ce fichier est une bibliothèque de fonctions.
// Contrairement à la boite à outils, il s'agit d'un fichier
// que vous devez comprendre et que vous serez amené à 
// modifier et à enrichir.

// Cette fonction génère un morceau de HTML qui affiche une photo,
// avec le propriétaire, la date ainsi que la description, qui sont 
// passés en arguments.
function affiche_photo($proprietaire,$date_photo,$description,$fichier) {
	// La balise <div> permet de grouper un morceau de HTML.
	print "<div>\n";
	// On affiche la photo dans un paragraphe centré
	print "<p align='center'><img src='$fichier'></p>\n";
	// $date_affichee est une chaîne représentant la date sous la forme
	// jj/mm/aaaa
	$date = strtotime($date_photo);
	$date_affichee = date('d/m/Y',$date);
	// On affiche le propriétaire et la date de la photo
	print "<p>Photo de $proprietaire prise le $date_affichee.</p>\n";
	// On affiche la description de la photo dans un paragraphe
	print "<p>$description</p>\n";
	print "</div>\n";	
}

// Cette fonction génère un morceau de HTML qui affiche un commentaire
// avec son auteur et le moment auquel il a été ajouté:
function affiche_commentaire($auteur,$date_commentaire,$contenu) {
	print "<div>\n";
	// Un paragraphe avec l'auteur en gras et la date de dépot entre parenthèses.
	print "<p><b>$auteur</b> ($date_commentaire):</p>\n";
	// Le contenu du coimmentaire affiché dans un paragraphe.
	print "<p>$contenu</p>";
	print "</div>";
}

?>