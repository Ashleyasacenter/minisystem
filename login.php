<?php
require_once 'config.php';
require_once 'controllers/AuthController.php';


if ($authController->isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    // Verify reCAPTCHA response
    if (empty($recaptcha_response)) {
        $error = 'Please complete the reCAPTCHA.';
    } else {
        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $recaptcha_response
        ];

        // Use cURL for POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verify_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20); // Increased timeout to 20 seconds
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            $error = 'Failed to verify reCAPTCHA: ' . $curl_error;
        } else {
            $verification_result = json_decode($response, true);
            if (empty($verification_result['success'])) {
                $error = 'reCAPTCHA verification failed. Please try again.';
                if (!empty($verification_result['error-codes'])) {
                    $error .= ' Error codes: ' . implode(', ', $verification_result['error-codes']);
                }
            } else {
                // Proceed with login if reCAPTCHA is successful
                if (empty($username) || empty($password)) {
                    $error = 'Username and password are required.';
                } else {
                    if ($authController->login($username, $password)) {
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = 'Invalid username or password.';
                    }
                }
            }
        }
    }
}

include 'views/login_view.php';
?>
