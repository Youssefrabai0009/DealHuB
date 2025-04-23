<?php
include __DIR__.'/../..Controller/traveloffercontroller.php';
$travelofferC= new traveloffercontroller();
$list=$travelofferC->listoffers();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" . />
    <title>registration form</title>
  </head>
  <body>
  <table border="2px">
      <tr><th> <th>titre</th>
      <th>destination</th>
      <th>departuredate </th>
       <th>returndate</th>
       <th> price</th>
       <th>disponiilty></th>
       <th>category</th>
</tr>
       
       
       <?php
           foreach($list as $offer){
           

?>
  <tr>
      <td><?php  echo $offer['id'];?></td>
      <td><?php  echo  $offer['titre']  ;?></td>
      <td><?php  echo  $offer['destination']  ;?></td>
      <td><?php  echo   $offer['date_depart'] ;?></td>
      <td><?php  echo   $offer['date_retour'] ;?></td>
      <td><?php  echo   $offer['prix'] ;?></td>
      <td><?php  echo   $offer['disponible'] ;?></td>
      <td><?php  echo   $offer['categorie'] ;?></td>

  </tr>        
      <?php
      }
      ?>
  </table>
</body>
</html>