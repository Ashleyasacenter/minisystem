<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'functions.php';

requireLogin();

// Get stats for dashboard
$student_count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$violation_count = $pdo->query("SELECT COUNT(*) FROM violations")->fetchColumn();
$pending_violations = $pdo->query("SELECT COUNT(*) FROM violations WHERE status = 'pending'")->fetchColumn();

// Get student violations
$student_violations = $pdo->query("SELECT v.*, s.student_id, s.first_name, s.last_name, s.year_level 
                                    FROM violations v 
                                    JOIN students s ON v.student_id = s.student_id 
                                    ORDER BY v.violation_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="style1.css">
	<link rel="stylesheet" href="style_improved.css">
	<title>Violation Management System</title>
</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="cics.png" alt="Logo" class="logo-img"> <!-- Replace with your logo -->
			<span class="text">Violation System</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#" id="nav-dashboard">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="#" id="nav-student">
					<i class='bx bxs-group'></i>
					<span class="text">Student</span>
				</a>
			</li>
			<li>
				<a href="#" id="nav-violation">
					<i class='bx bxs-show-alert'></i>
					<span class="text">Violation</span>
				</a>
			</li>
			<li>
				<a href="#" id="nav-profile">
					<i class='bx bxs-user-account'></i>
					<span class="text">Profile</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="logout.php" class="logout"> <!-- Changed from # to logout.php -->
					<i class='bx bxs-log-out-circle'></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->

		<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main id="main-content">
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>

				<li>
					<i class='bx bxs-user-account'></i>
					<span class="text">
						<p>Total Pending</p>
					</span>
				</li>
			</ul>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	
	<script src="script1.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			function loadContent(url) {
				fetch(url)
					.then(response => response.text())
					.then(html => {
						document.getElementById('main-content').innerHTML = html;
						// Initialize JS for loaded content
						if (url.includes('students_content.php')) {
							if (typeof initStudentPage === 'function') initStudentPage();
						} else if (url.includes('violation_content.php')) {
							if (typeof initViolationPage === 'function') initViolationPage();
						} else if (url.includes('profile_content.php')) {
							if (typeof initProfilePage === 'function') initProfilePage();
						}
					})
					.catch(err => console.error('Error loading content:', err));
			}

			document.getElementById('nav-dashboard').addEventListener('click', function(e) {
				e.preventDefault();
				// Reload dashboard content or show default dashboard content
				location.reload();
			});

			document.getElementById('nav-student').addEventListener('click', function(e) {
				e.preventDefault();
				loadContent('views/partials/students_content.php');
			});

			document.getElementById('nav-violation').addEventListener('click', function(e) {
				e.preventDefault();
				loadContent('get_violation_content.php');
			});

			document.getElementById('nav-profile').addEventListener('click', function(e) {
				e.preventDefault();
				loadContent('views/partials/profile_content_with_init.php');
			});
		});
	</script>
</body>
</html>
