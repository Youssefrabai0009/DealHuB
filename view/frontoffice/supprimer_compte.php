<?php
session_start();
require_once '../../config.php';
require_once '../../controller/UserController.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user']['id'];
$userController = new UserController($pdo);

if ($userController->deleteUser($userId)) {
    session_unset();
    session_destroy();
    header("Location: home.html");
    exit();
} else {
    echo "Erreur lors de la suppression du compte.";
}
?>
