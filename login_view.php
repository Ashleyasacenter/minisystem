<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <!-- Logo Section -->
            <div class="logo">
                <img src="cics.png" alt="CICS Logo" style="max-width: 30%; height: 50;">
                <h2>Student Violation Record Management System</h2>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>
</body>
</html>
