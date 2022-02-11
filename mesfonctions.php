<?php
// Ce fichier est une biblioth�que de fonctions.
// Contrairement � la boite � outils, il s'agit d'un fichier
// que vous devez comprendre et que vous serez amen� � 
// modifier et � enrichir.

// Cette fonction g�n�re un morceau de HTML qui affiche une photo,
// avec le propri�taire, la date ainsi que la description, qui sont 
// pass�s en arguments.
function affiche_photo($proprietaire,$date_photo,$description,$fichier) {
	// La balise <div> permet de grouper un morceau de HTML.
	print "<div>\n";
	// On affiche la photo dans un paragraphe centr�
	print "<p align='center'><img src='$fichier'></p>\n";
	// $date_affichee est une cha�ne repr�sentant la date sous la forme
	// jj/mm/aaaa
	$date = strtotime($date_photo);
	$date_affichee = date('d/m/Y',$date);
	// On affiche le propri�taire et la date de la photo
	print "<p>Photo de $proprietaire prise le $date_affichee.</p>\n";
	// On affiche la description de la photo dans un paragraphe
	print "<p>$description</p>\n";
	print "</div>\n";	
}

// Cette fonction g�n�re un morceau de HTML qui affiche un commentaire
// avec son auteur et le moment auquel il a �t� ajout�:
function affiche_commentaire($auteur,$date_commentaire,$contenu) {
	print "<div>\n";
	// Un paragraphe avec l'auteur en gras et la date de d�pot entre parenth�ses.
	print "<p><b>$auteur</b> ($date_commentaire):</p>\n";
	// Le contenu du coimmentaire affich� dans un paragraphe.
	print "<p>$contenu</p>";
	print "</div>";
}

?>