<?php
include('boite_outils.php');


include('mesfonctions.php');





$connect = connexion();

if(isset($_GET['deleteid'])){
    $idphoto=$_GET['deleteid'];
    $requete = "SELECT id, proprietaire FROM photo WHERE id= $idphoto";
        $re = $connect->prepare($requete);
      $re->execute();
      if($nuplet = $re->fetch(PDO::FETCH_ASSOC)){
        $perso = $nuplet['proprietaire'];
        
        $perso_param = rawurlencode($perso);
        
       
    $sql="DELETE FROM photo where id=$idphoto";
    $resu = $connect->prepare($sql);
      $resu->execute();
     if($resu){
        
       header ("location:photos_personne.php?personne=$perso_param");
     }
    }
    
    else{
         die(mysqli_error($connect));
    }
 }
?>