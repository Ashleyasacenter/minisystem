<?php
require_once 'config.php';
require_once 'controllers/StudentController.php';

$studentController = new StudentController($pdo);
$studentController->handleRequest();

$error = $studentController->error;
$success = $studentController->success;
$students = $studentController->getStudents();

include 'views/students_view.php';
