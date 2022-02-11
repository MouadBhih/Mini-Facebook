<?php
// Fichier générant la page d'accueil.

// Vos fichiers php, sauf lorsque cela est explicitement précisé, doivent
// systématiquement débuter par cette inclusion de fichier.
include('boite_outils.php');

// On se connecte à la base de donnée
// La fonction mysql_error() renvoie le message d'erreur en cas de problème.
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de sélection de la base: '.mysql_error());
?>
<html>
<head>
  <title>Accueil</title>
  <!-- La ligne suivante est obligatoire si vous utilisez un formulaire 
       avec des dates (fonction input_date de la boite à outils) -->
  <script  type="text/javascript" language="JavaScript" src="fonctions.js"> </script>
</head>
<body>
<?php
// Un message de bienvenue qui affiche le login de l'utilisateur
print "<h2>Bienvenue $login !</h2>";	
?>  
<h3>Voir les photos de:</h3>
<ul>
<?php
	// On souhaite générer une liste des personnes ayant des photos sur ce site,
	// avec un lien pour accéder à la liste de leurs photos
	// Les balises de début et de fin de liste ont été écrites à
	// l'extérieur des ? 
	
	// La requête récupère l'ensemble des utilisateurs de la base.
	$requete = "SELECT login FROM utilisateur";
	// La variable $resultat contient le résultat de la requête.
	$resultat = mysql_query($requete,$connect)
	  	or die("Echec lors de la requête: ".$requete.": ".mysql_error());
	// La boucle suivante parcours la relation résultat.
	// A chaque tour de boucle, la variable $nuplet contient
	// le nuplet examiné sous la forme d'un tableau associatif.
	while ($nuplet = mysql_fetch_assoc($resultat)) {
		// On stocke dans la variable $personne
		// la valeur de l'attribut login.
		$personne = $nuplet['login'];
		// Afin d'éviter les problèmes d'espaces et autres caractères spéciaux
		// lors de la transmission de la requête avec la méthode GET
		// (avec laquelle le paramètre apparaît directement dans l'adresse)
		// on utilise la fonction rawurlencode
		$personne_param = rawurlencode($personne);
		// On ajoute une entrée (balise <li>), avec un lien
		// vers la page photo_personne.php
		// avec un paramètre personne dont la valeur est donnée
		// par la variable $personne_param.
		// Le texte affiché pour le lien est le nom de la personne
		// qui est stocké dans la variable $personne.
		print '<li><a href="photos_personne.php?personne='
			.$personne_param.'">'.$personne.'</a></li>'."\n";
	}
?>
</ul>
<hr>
<h3>Ajouter une photo à ma collection</h3>
<!--
	Un formulaire pour ajouter une photo.
	L'attribut enctype="multipart/form-data" de la balise <form> 
	est indispensable dans la mesure où l'on souhaite envoyer un fichier.
-->
<form action="ajoute_photo.php" method="POST" enctype="multipart/form-data" name="add_photo">
		<!-- La balise <input> suivante est un composant qui sert à transmettre
		  un fichier. Le fichier sert accessible depuis le paramètre appelé photo.
		  -->
	<p>Fichier de la photo: <input type="file" name="photo" size=30></p>
		<!-- La description de la photo est fournie via un composant
		  <textarea> qui sera utilisé pour remplir le paramètre description. -->
	<p>Description de la photo:</p>
	<p><textarea name="description" rows="10" cols="60">Entrez la description de la photo ici.
	</textarea></p>
	  <!-- Le composant permettant de saisir la date est un composant pour un 
	    champ texte amélioré avec une fenêtre de calendrier qui utilise des 
	    fonctions Javascript. Le tout est caché dans la fonction input_date() 
	    fournie dans la boite à outils. Il suffit de savoir qu'un appel à 
	    input_date(), avec comme premier argument le nom du paramètre dans 
	    lequel on stocke la date et comme deuxième argument la valeur de 
	    l'attribut name de la balise <form> (ici add_photo), permet d'ajouter un 
	    composant pour la saisie d'une date. -->
	<p>Date de la photo: <? input_date('date_photo','add_photo'); ?></p>
	<p>
			<!-- Crée un bouton pour envoyer les données du formulaire -->
	  <input type="submit" value="Ajouter la photo">
	    <!-- Crée un bouton pour vider le formulaire -->
	  <input type="reset" value="Annuler">
	</p>
</form>
<hr>
<!-- Lien vers la page de déconnexion -->
<p><a href="deconnexion.php">Se déconnecter</a>.</p>
</body>
</html>
<?php
// Fermeture de la connexion au SGBD.
mysql_close($connect);
?>
