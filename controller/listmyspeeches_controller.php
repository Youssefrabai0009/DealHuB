<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Speechesmodel.php';

$entrepreneur_id = $_SESSION['user']['id']; // temporaryspeech value // atheya badalneha 

$speechesModel = new SpeechesModel($pdo);
$speeches = $speechesModel->showMySpeeches($entrepreneur_id);

// Pass $speeches to the view
include __DIR__ . '/../view/frontoffice/listmyspeeches.php';
?>
