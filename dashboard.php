<?php
require_once 'config.php';
require_once 'controllers/DashboardController.php';

$dashboardController = new DashboardController($pdo);
$data = $dashboardController->getDashboardData();

$student_count = $data['student_count'];
$violation_count = $data['violation_count'];
$pending_violations = $data['pending_violations'];
$violations = $data['violations'];

include 'views/dashboard_view.php';
?>
