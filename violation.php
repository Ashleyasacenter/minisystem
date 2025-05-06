<?php
require_once 'config.php';
require_once 'controllers/ViolationController.php';

$violationController = new ViolationController($pdo);
$violationController->handleRequest();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_violation'])) {
    error_log("POST data for add_violation: " . print_r($_POST, true));
}

$error = $violationController->error;
$success = $violationController->success;
$violations = $violationController->getViolations();
$students = $violationController->students;
$reflection = new ReflectionClass($violationController);
$violationModelProperty = $reflection->getProperty('violationModel');
$violationModelProperty->setAccessible(true);
$violationModel = $violationModelProperty->getValue($violationController);

$violationTypes = $violationModel->getViolationTypes();
$absences = $violationController->absences ?? [];

include 'views/violation_view.php';
