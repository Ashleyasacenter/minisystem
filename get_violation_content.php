<?php
require_once 'config.php';
require_once 'controllers/ViolationController.php';

$violationController = new ViolationController($pdo);
$violationController->handleRequest();

$error = $violationController->error;
$success = $violationController->success;
$violations = $violationController->getViolations();
$students = $violationController->students;
$reflection = new ReflectionClass($violationController);
$violationModelProperty = $reflection->getProperty('violationModel');
$violationModelProperty->setAccessible(true);
$violationModel = $violationModelProperty->getValue($violationController);

$violationTypes = $violationModel->getViolationTypes();

include 'views/violation_content.php';
