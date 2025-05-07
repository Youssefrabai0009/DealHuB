<?php
require_once '../config/data_base.php';
require_once '../controllers/Speech_Controllers.php';

$controller = new SpeechController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->addPitch();
} else {
    $controller->showForm();
}
?>
