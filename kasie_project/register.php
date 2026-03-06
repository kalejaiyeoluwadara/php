<?php
/**
 * User Registration - INSY 402/SENG 412
 * Requirements: name, email (unique, validated), password (hashed)
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$success = false;
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // Server-side validation
    if ($name === '') {
        $errors['name'] = 'Name is required.';
    } elseif (strlen($name) < 2) {
        $errors['name'] = 'Name must be at least 2 characters.';
    } elseif (strlen($name) > 100) {
        $errors['name'] = 'Name must not exceed 100 characters.';
    } elseif (!preg_match('/^[\p{L}\p{M}\s\'\-\.]+$/u', $name)) {
        $errors['name'] = 'Name can only contain letters, spaces, hyphens, and apostrophes.';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    } elseif (strlen($email) > 254) {
        $errors['email'] = 'Email is too long.';
    } else {
        // Check unique email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['email'] = 'This email is already registered.';
        }
        $stmt->close();
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors['password'] = 'Password must contain uppercase, lowercase, and a number.';
    }

    if ($password !== $passwordConfirm) {
        $errors['password_confirm'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // Hash password securely (bcrypt)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // First user gets admin role, all others get user role
        $countResult = $conn->query("SELECT COUNT(*) FROM users");
        $userCount = $countResult ? (int) $countResult->fetch_row()[0] : 0;
        $role = $userCount === 0 ? 'admin' : 'user';

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            $success = true;
            $name = '';
            $email = '';
        } else {
            $errors['db'] = 'Registration failed. Please try again.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Multi-User Login System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-card">
            <h1>Create Account</h1>
            <p class="subtitle">Sign up to access exclusive content</p>

            <?php if ($success): ?>
                <div class="message success">
                    Account created successfully! <a href="login.php">Log in here</a>.
                </div>
            <?php else: ?>
                <?php if (!empty($errors)): ?>
                    <div class="message error">
                        <strong>Please fix the following:</strong>
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="" novalidate>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required
                           value="<?= htmlspecialchars($name) ?>"
                           placeholder="Your full name"
                           class="<?= isset($errors['name']) ? 'invalid' : '' ?>">

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($email) ?>"
                           placeholder="your@email.com"
                           class="<?= isset($errors['email']) ? 'invalid' : '' ?>">

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Min 8 chars, upper, lower, number"
                           class="<?= isset($errors['password']) ? 'invalid' : '' ?>">

                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required
                           placeholder="Re-enter password"
                           class="<?= isset($errors['password_confirm']) ? 'invalid' : '' ?>">

                    <button type="submit">Sign Up</button>
                </form>
            <?php endif; ?>

            <p class="auth-link">Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>
