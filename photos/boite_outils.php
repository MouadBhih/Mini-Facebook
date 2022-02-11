<?php

// boite_outils.php contient du code qui permet de gérer l'identification de 
// manière automatique.
// Si l'utilisateur n'est pas connecté, une page d'identification 
// est envoyée à la place de la page normale.
// Une fois l'utilisateur identifié, il sera envoyé sur la page qu'il a 
// demandé à l'origine.

// IMPORTANT:
// Une fois le login vérifié, il est stocké dans la variable $login.
// Cette variable sera très souvent utilisée car elle permet de savoir quel 
// est l'utilisateur courant.
// Rmq: pour pouvoir utiliser cette variable dans la définition d'une fonction,
// il faut déclarer global $login;


// De plus, boite_outils.php définit les fonctions suivantes:


// sauve_photo($nom_param)
//
// Permet de sauvegarder sur le disque du serveur un fichier envoyé.
// La fonction renvoie le nom (relatif à la page d'accueil) du fichier
// qui est typiquement stocké dans la base.
// Si le fichier n'a pas pu être sauvé, la fonction renvoie null, ce qui
// permet de tester si le fichier a bien été sauvé.
// L'argument $nom_param est le nom du paramètre spécifié dans le formulaire
// pour envoyer la photo.
// Voir ajoute_photo.php pour un exemple d'utilisation


// input_date($nomChamp,$nomForm,$valeur)
//
// Génère un composant input pour saisir une date, avec un bouton faisant
// apparaître un petit calendrier.
// Utiliser cette fonction nécessite d'avoir ajouté dans la partie <head>
// du document généré le code suivant:
// <script  type="text/javascript" language="JavaScript" src="fonctions.js"> </script>
// L'argument $nomChamp est le nom du paramètre utilisé pour envoyer la date.
// L'argument $nomForm est le nom du formulaire 
//   (attribut name="..." dans la balise <form>).
// L'argument $valeur est optionnel et précise éventuellement une valeur de 
// départ pour la date (fonctionnalité identique à celle de l'attribut 
// value="..." dans un composant <input type="text" ...>)
// Voir index.php et modifie_photo.php pour des exemples d'utilisation.


// verifie_date($date)
//
// Vérifie que $date est une chaîne de caractères pouvant être utilisée
// pour spécifier une valeur de date dans le SGBD.
// Renvoie une chaîne de caractères avec correctement formée correspondant à 
// la date passée en argument, ou, à défaut, une chaîne qui correspond à la 
// date du jour.


//////////////////////////////////////////////////////////////////////////////
// Début du code des fonctionnalités de la boîte à outils
//////////////////////////////////////////////////////////////////////////////


// Le fichier inclus le fichier valeurs.php qui contient un certain nombre de 
// définitions de variables, en particuliers les valeurs nécessaires 
// à la connection à la base de données.
include('valeurs.php');

// On utilise systématiquement une session
session_start();

// fonction établissant une connexion à la bd
function connexion_bd() {
	global $bd_machine, $bd_port, $bd_login, $bd_password, $bd_base;
	$connect = mysql_connect("$bd_machine:$bd_port",$bd_login,$bd_password)
		or die('Echec de connection au SGBD: '.mysql_error());
	mysql_select_db($bd_base,$connect) 
		or die('Echec de sélection de la base: '.mysql_error());
	return $connect;
}

// fonction affichant un formulaire permettant de saisir un login et 
// un mot de passe avant de rediriger l'utilisateur vers la page choisie
function formulaire_login($message='') {
	$action = $_SERVER['REQUEST_URI'];
	?>
<html>
	<head><title>Saisie des identifiants</title></head>
    <body>
   		<?php 
   		if ($message) {
     		print $message;
	    }
		print "<form action='$action' method='POST'>\n";
		?> 
      <p>Connexion au site:</p>
      <table>
	      <tr>
	        <td>Identifiant:</td>
	        <td><input type="text" name="login" size="32" maxlength="128"></td>
	      </tr>
	      <tr>
	        <td>Mot de passe:</td>
	        <td><input type="password" name="password" size="32" maxlength="32"></td>
	      </tr>
	      <tr><td colspan="2" align="center">
	        <input type="submit" value="Se connecter">
	        <input type="reset" value="Effacer">
	      </td></tr>
      </table>
    </form>
    <hr>
    <p><a href="inscription.html">S'inscrire</a></p>
  </body>
</html>
    <?php
    exit;
}

// fonction vérifiant un login et un mot de passe
function verifie_login($login,$passwd) {
	$connect = connexion_bd();
	$requete = "SELECT * FROM utilisateur WHERE login='$login' AND password='$passwd'";
	$resultat = mysql_query($requete,$connect)
	  or die("Erreur lors de l'exécution de la requête: ".mysql_error());
	// si le résultat n'est pas vide
	if ($ligne = mysql_fetch_assoc($resultat)) {
		$login_ok = true;
	} else {
		$login_ok = false;
	}
	mysql_close($connect);
	return $login_ok;
}

$login='';

// Fonction qui verifie si le login et le mot de passe ont
// bien été saisis et qui dans le cas contraire affiche une 
// page de connection
// Assigne également la valeur des variables qui dépendent 
// de la session
function login_ou_reconnection() {
	global $login;
	if (isset($_SESSION['login'])) {
		$login = $_SESSION['login'];
	} else if (isset($_POST['login']) && isset($_POST['password'])) {
		$login = $_POST['login'];
		if (verifie_login($login,$_POST['password'])) {
			$_SESSION['login'] = $login;	
		} else {
			formulaire_login("<h3>Erreur d'identification</h3>\n".
							 "<p>Veuillez saisir à nouveau vos identifiants</p>");
		}
	} else {
		formulaire_login();
	}
}


function detruire_session()
{
	// On ecrase le tableau de session
	$_SESSION = array();

	// On detruit la session
	session_write_close();
}

function deconnexion() {
	detruire_session();	
}

function charger_page($page)
{	
	echo "<script language=JavaScript>
				 <!-- Hide from JavaScript-Impaired Browsers
 				 parent.location=\"" . $page . "\"
				 // End Hiding -->
				 </script>";
}
	
function genere_nom_fichier($nom_depart) {
	if (file_exists($nom_depart)) {
		$ppos = strrpos($nom_depart,'.');
		$ext = substr($nom_depart,$ppos);
		$prefix = substr($nom_depart,0,$ppos);
		$i=0;
		while(file_exists("$prefix$i$ext")) {
			$i++;
		}
		return $prefix.$i.$ext;
	} else {
		return $nom_depart;
	}
}
	
function sauve_photo($param_fichier) {
	global $login;
	if ($param_fichier == null) {
		die("Il faut spécifier le nom du paramètre dans ".
		    "lequel est stockée la photo à la fonction sauve_photo !!!");
	}	
	
	if ($_FILES[$param_fichier]['error']) {
		switch ($_FILES[$param_fichier]['error']){
	    case UPLOAD_ERR_INI_SIZE:
           	print "Le fichier depasse la limite autorisee par le serveur (fichier php.ini).";
           	break;
        case UPLOAD_ERR_FORM_SIZE:
           	print "Le fichier depasse la limite autorisee dans le formulaire HTML.";
           	break;
        case UPLOAD_ERR_PARTIAL:
           	print "L'envoi du fichier a ete interrompu pendant le transfert.";
          	break;
        case UPLOAD_ERR_NO_FILE:
           	print "Le fichier que vous avez envoye a une taille nulle.";
         	break;
	 	case UPLOAD_ERR_NO_TMP_DIR:
	 		print "Pas de repertoire temporaire defini.";
	 		break;
	 	case UPLOAD_ERR_CANT_WRITE:
	 		print "Ecriture du fichier impossible.";
	 	default:
			print "Erreur inconnue.";
		}
		return null;
	}
	else {
	 	// $_FILES[$param_fichier]['error'] vaut 0 soit UPLOAD_ERR_OK
	 	// ce qui signifie qu'il n'y a eu aucune erreur
	 	$chemin_destination = 'photos/'.rawurlencode($login);
	 	mkdir($chemin_destination);
	 	$chemin_destination = $chemin_destination.'/';
	 	$urlphoto=$chemin_destination.$_FILES[$param_fichier]['name'];
	 			$urlphoto=genere_nom_fichier($urlphoto);
	 	move_uploaded_file($_FILES[$param_fichier]['tmp_name'],$urlphoto);
	 	return $urlphoto;
	}
}


function input_date($nomChamp,$nomForm,$valeur='') 
{
  echo "<input type=\"Text\" name=\"$nomChamp\" value=\"$valeur\" size=\"20\">";
  echo "<a href=\"javascript:cal$nomChamp.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Cliquez ici pour obtenir la date.\"></a>\n";
  echo "<script language=\"JavaScript\">\n";
  echo "var cal$nomChamp = new calendar1(document.forms['$nomForm'].elements['$nomChamp']);\n";
  echo "cal$nomChamp.year_scroll = true;\n";
  echo "cal$nomChamp.time_comp = false;\n";
  echo "</script>\n";
  return 0;
}
	
// on verifie que l'on a bien une date correcte
// et dans le bon format
// sinon on tente de la convertir
// ou bien on met la date courante à la place
// renvoie la date bien formatée
// !!! ne gère pas bien les date d'avant 1970
function verifie_date($date) {
	$timestamp = strtotime($date);
	if ($timestamp && $timestamp != -1) {
		return date('Y-m-d',$timestamp);
	} else {
		return date('Y-m-d');
	}
}
	
login_ou_reconnection();

?>