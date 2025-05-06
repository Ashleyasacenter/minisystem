<?php
// This partial view contains only the main content section of profile_view.php without sidebar and full HTML structure
// Define $error, $success, and $user if not set to avoid undefined variable warnings
if (!isset($error)) {
    $error = '';
}
if (!isset($success)) {
    $success = '';
}
if (!isset($user)) {
    $user = ['username' => '', 'email' => ''];
}
?>
<div class="head-title">
    <div class="left">
        <h1>My Profile</h1>
        <ul class="breadcrumb">
            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="profile.php">Profile</a>
            </li>
        </ul>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="content-section">
    <form action="profile.php" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required />
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
        </div>
        <div class="form-group">
            <label for="current_password">Current Password (only required if changing password)</label>
            <input type="password" id="current_password" name="current_password" />
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" />
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" />
        </div>
        <button type="submit" name="update_profile" class="btn">Update Profile</button>
    </form>
</div>

<script src="partials/init_scripts.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initProfilePage === 'function') {
            initProfilePage();
        }
    });
</script>
