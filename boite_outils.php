<?php
include("db_connexion.php");
session_start();
function formulaire_login($message="") {
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
    <p><a href="inscription.php">S'inscrire</a></p>
  </body>
</html>
    <?php
    exit;
}
function verifie_login($login,$passwd) {
    $conn = connexion();
	$requete = "SELECT * FROM utilisateur WHERE login='$login' AND password='$passwd'";
	$resultat = $conn->prepare($requete);
    $resultat->execute();
	if ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
		$login_ok = true;
	} else {
		$login_ok = false;
	}
    $conn = null;
	return $login_ok;
}
$login='';
function login_ou_reconnection() {
	global $login;
	if (isset($_SESSION["login"])) {
		$login = $_SESSION["login"];
	} else if (isset($_POST["login"]) && isset($_POST["password"])) {
		$login = $_POST["login"];
		if (verifie_login($login,$_POST["password"])) {
			$_SESSION["login"] = $login;	
		} else {
			formulaire_login("<h3>Erreur d'identification</h3>\n".
							 "<p>Veuillez saisir a nouveau vos identifiants</p>");
		}
	} else {
		formulaire_login();
	}
}
function detruire_session()
{
	$_SESSION = array();
	session_write_close();
}

function deconnexion() {
	detruire_session();	
}

function charger_page($page)
{	
	echo "<script>
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
		die("Il faut specifier le nom du parametre dans ".
		    "lequel est stockee la photo a la fonction sauve_photo !!!");
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
	 	$chemin_destination = 'photos/'.rawurlencode($login);
		@mkdir($chemin_destination);
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