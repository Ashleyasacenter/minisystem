<?php
require_once 'models/UserModel.php';

class ProfileController {
    public $error = '';
    public $success = '';
    public $user = [];

    private $userModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($pdo);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->loadUser();
    }

    private function loadUser() {
        if (isset($_SESSION['username'])) {
            $this->user = $this->userModel->findByUsername($_SESSION['username']);
        }
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
        }
    }

    private function updateProfile() {
        // Example: update email or other profile fields
        $email = $_POST['email'] ?? '';
        if (empty($email)) {
            $this->error = 'Email cannot be empty.';
            return;
        }

        // Update user email in database
        $stmt = $this->pdo->prepare("UPDATE users SET email = ? WHERE username = ?");
        $success = $stmt->execute([$email, $_SESSION['username']]);

        if ($success) {
            $this->success = 'Profile updated successfully.';
            $this->loadUser();
        } else {
            $this->error = 'Failed to update profile.';
        }
    }
}
?>
