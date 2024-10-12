<?php
include('boite_outils.php');

$personne = $_GET['personne'];

$connect = connexion();

?>
<html>
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  
  
  <?php
  print "<title>Photos de $personne</title>"; 
  ?>
</head>
<body>
<h1>A faire: ajouter les dates dans le r�sum� des photos et trier les photos par dates</h1>
<input type ="text" name = "from_date"  id = "from_date">
<input type ="text" name = "to_date"  id = "to_date">
<input type ="button" name = "filter"  id = "filter" value = "Filtrer">
<div id="order_list">
<?php 
print "<h2>Photos de $personne</h2>\n"; 

print "<ol>\n";
$requete = "SELECT * FROM photo WHERE proprietaire = '$personne'";
$resultat = $connect->prepare($requete);
$resultat->execute();

while ($nuplet = $resultat->fetch(PDO::FETCH_ASSOC)) {
	$id_photo = $nuplet['id'];
	$date = $nuplet['date_photo'];
	
		$personne_param = rawurlencode($personne);
	$description_courte = substr(stripslashes($nuplet['description']),0,30);
	if (strlen($nuplet['description']) > 30) {
		$description_courte = $description_courte.'...';
	}
	$resume = $description_courte.$date;
	print "<li><a href='photo.php?id=$id_photo'>$resume</a></li>";
}

print "</ol>\n";
?>
</div>
</body>
</html>
<script>
    $(document).ready(function(){
		$.datepicker.setDefaults({
   dateFormat:'yy-mm-dd'
})
        $(function(){
            $("#from_date").datepicker();
            $("#to_date").datepicker();
        });
	});
		$('#filter').click(function(){
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    if(from_date != '' && to_date != '')
  {
	//alert("hello");     
        $.ajax({
            url:"filter.php",
            method: "POST",
            data:{from_date:from_date, to_date:to_date}
			
            success:function(data)
            {
				$('#order_list').html = data;
            }
			
		});
	}
		else {
			alert("Please Select date");
		}
    });
</script>

<?php
$connect = null;
?>
