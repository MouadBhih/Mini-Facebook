<?php
// Fichier g�n�rant la page d'accueil.

// Vos fichiers php, sauf lorsque cela est explicitement pr�cis�, doivent
// syst�matiquement d�buter par cette inclusion de fichier.
include('boite_outils.php');

// On se connecte � la base de donn�e
// La fonction mysql_error() renvoie le message d'erreur en cas de probl�me.
$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
	or die('Echec de connection au SGBD: '.mysql_error());
mysql_select_db($bd_base,$connect) 
	or die('Echec de s�lection de la base: '.mysql_error());
?>
<html>
<head>
  <title>Accueil</title>
  <!-- La ligne suivante est obligatoire si vous utilisez un formulaire 
       avec des dates (fonction input_date de la boite � outils) -->
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
	// On souhaite g�n�rer une liste des personnes ayant des photos sur ce site,
	// avec un lien pour acc�der � la liste de leurs photos
	// Les balises de d�but et de fin de liste ont �t� �crites �
	// l'ext�rieur des ? 
	
	// La requ�te r�cup�re l'ensemble des utilisateurs de la base.
	$requete = "SELECT login FROM utilisateur";
	// La variable $resultat contient le r�sultat de la requ�te.
	$resultat = mysql_query($requete,$connect)
	  	or die("Echec lors de la requ�te: ".$requete.": ".mysql_error());
	// La boucle suivante parcours la relation r�sultat.
	// A chaque tour de boucle, la variable $nuplet contient
	// le nuplet examin� sous la forme d'un tableau associatif.
	while ($nuplet = mysql_fetch_assoc($resultat)) {
		// On stocke dans la variable $personne
		// la valeur de l'attribut login.
		$personne = $nuplet['login'];
		// Afin d'�viter les probl�mes d'espaces et autres caract�res sp�ciaux
		// lors de la transmission de la requ�te avec la m�thode GET
		// (avec laquelle le param�tre appara�t directement dans l'adresse)
		// on utilise la fonction rawurlencode
		$personne_param = rawurlencode($personne);
		// On ajoute une entr�e (balise <li>), avec un lien
		// vers la page photo_personne.php
		// avec un param�tre personne dont la valeur est donn�e
		// par la variable $personne_param.
		// Le texte affich� pour le lien est le nom de la personne
		// qui est stock� dans la variable $personne.
		print '<li><a href="photos_personne.php?personne='
			.$personne_param.'">'.$personne.'</a></li>'."\n";
	}
?>
</ul>
<hr>
<h3>Ajouter une photo � ma collection</h3>
<!--
	Un formulaire pour ajouter une photo.
	L'attribut enctype="multipart/form-data" de la balise <form> 
	est indispensable dans la mesure o� l'on souhaite envoyer un fichier.
-->
<form action="ajoute_photo.php" method="POST" enctype="multipart/form-data" name="add_photo">
		<!-- La balise <input> suivante est un composant qui sert � transmettre
		  un fichier. Le fichier sert accessible depuis le param�tre appel� photo.
		  -->
	<p>Fichier de la photo: <input type="file" name="photo" size=30></p>
		<!-- La description de la photo est fournie via un composant
		  <textarea> qui sera utilis� pour remplir le param�tre description. -->
	<p>Description de la photo:</p>
	<p><textarea name="description" rows="10" cols="60">Entrez la description de la photo ici.
	</textarea></p>
	  <!-- Le composant permettant de saisir la date est un composant pour un 
	    champ texte am�lior� avec une fen�tre de calendrier qui utilise des 
	    fonctions Javascript. Le tout est cach� dans la fonction input_date() 
	    fournie dans la boite � outils. Il suffit de savoir qu'un appel � 
	    input_date(), avec comme premier argument le nom du param�tre dans 
	    lequel on stocke la date et comme deuxi�me argument la valeur de 
	    l'attribut name de la balise <form> (ici add_photo), permet d'ajouter un 
	    composant pour la saisie d'une date. -->
	<p>Date de la photo: <? input_date('date_photo','add_photo'); ?></p>
	<p>
			<!-- Cr�e un bouton pour envoyer les donn�es du formulaire -->
	  <input type="submit" value="Ajouter la photo">
	    <!-- Cr�e un bouton pour vider le formulaire -->
	  <input type="reset" value="Annuler">
	</p>
</form>
<hr>
<!-- Lien vers la page de d�connexion -->
<p><a href="deconnexion.php">Se d�connecter</a>.</p>
</body>
</html>
<?php
// Fermeture de la connexion au SGBD.
mysql_close($connect);
?>
