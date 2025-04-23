<?php
include __DIR__.'/../../Controller/traveloffercontroller.php';
$travelofferC=new traveloffercontroller();
$travelofferC->deleteoffer($_GET['id']);


?>