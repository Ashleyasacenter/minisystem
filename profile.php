<?php
require_once 'config.php';
require_once 'controllers/ProfileController.php';

$profileController = new ProfileController($pdo);
$profileController->handleRequest();

$error = $profileController->error;
$success = $profileController->success;
$user = $profileController->user;

include 'views/profile_view.php';
