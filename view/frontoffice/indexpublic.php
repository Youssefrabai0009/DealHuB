<?php
require_once '../../config.php';
require_once '../../controller/Speech_Controllers.php';

$controller = new SpeechController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->addPitch();
} else {
    $controller->showForm();
}
?>
