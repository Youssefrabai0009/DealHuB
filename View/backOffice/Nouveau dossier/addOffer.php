<?php
include __DIR__.'/../../Controller/offrecontroller.php';
$error='';
$offer=null;
$traveloffer=new offrecontroller();
if(isset($_POST['montant'],$_POST['date_offre'],$_POST['statut']]))
{
    if(!empty($_POST['montant']) && !empty($_POST['date_offre']) && !empty($_POST['statut'])){
        $offer=new offre(null,1,1,1,$_POST['montant'] ?? 1,new date($_POST['date_offre']?? 'now'),$_POST['statut']??'');
    }
}
?>
