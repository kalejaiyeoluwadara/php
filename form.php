<?php
require_once __DIR__ . '/db.php';

// Handle POST (form submission)
$submittedName = '';
$submittedEmail = '';
$postMessage = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawName = trim($_POST['name'] ?? '');
    $rawEmail = trim($_POST['email'] ?? '');

    // Empty field checks
    if ($rawName === '') {
        $errors['name'] = 'Name is required.';
    } else {
        if (strlen($rawName) < 2) {
            $errors['name'] = 'Name must be at least 2 characters.';
        } elseif (strlen($rawName) > 100) {
            $errors['name'] = 'Name must not exceed 100 characters.';
        } elseif (!preg_match('/^[\p{L}\p{M}\s\'\-\.]+$/u', $rawName)) {
            $errors['name'] = 'Name can only contain letters, spaces, hyphens, and apostrophes.';
        }
    }

    if ($rawEmail === '') {
        $errors['email'] = 'Email is required.';
    } else {
        if (!filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif (strlen($rawEmail) > 254) {
            $errors['email'] = 'Email is too long.';
        }
    }

    if (empty($errors)) {
        $submittedName = htmlspecialchars($rawName);
        $submittedEmail = htmlspecialchars($rawEmail);

        $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $rawName, $rawEmail);
        if ($stmt->execute()) {
            $postMessage = "Form submitted via POST: Hello {$submittedName}, we received your email: {$submittedEmail}";
        } else {
            $errors['db'] = 'Could not save your submission. Please try again.';
        }
        $stmt->close();
    } else {
        $submittedName = htmlspecialchars($rawName);
        $submittedEmail = htmlspecialchars($rawEmail);
    }
}

// Handle GET (e.g. ?name=John&email=john@example.com for pre-filling)
$getName = htmlspecialchars($_GET['name'] ?? '');
$getEmail = htmlspecialchars($_GET['email'] ?? '');
$getMessage = '';
if (!empty($getName) || !empty($getEmail)) {
    $getMessage = "Data received via GET: Name={$getName}, Email={$getEmail}";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Name & Email Form</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 40px auto; padding: 20px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 16px; padding: 10px 24px; background: #333; color: white; border: none; cursor: pointer; }
        button:hover { background: #555; }
        .message { margin-top: 20px; padding: 12px; background: #e8f5e9; border-left: 4px solid #4caf50; }
        .error { margin-top: 20px; padding: 12px; background: #ffebee; border-left: 4px solid #c62828; margin-bottom: 16px; }
        .field-error { color: #c62828; font-size: 0.9em; margin-top: 4px; font-weight: normal; }
        input.invalid { border: 1px solid #c62828; }
    </style>
</head>
<body>
    <h1>Contact Form</h1>

    <?php if (!empty($errors)): ?>
        <div class="error" role="alert">
            <strong>Please fix the following:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($postMessage): ?>
        <div class="message"><?= $postMessage ?></div>
    <?php endif; ?>

    <?php if ($getMessage): ?>
        <div class="message"><?= $getMessage ?></div>
    <?php endif; ?>

    <form method="post" action="" novalidate>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required
               value="<?= $submittedName ?: $getName ?>"
               placeholder="Enter your name"
               class="<?= isset($errors['name']) ? 'invalid' : '' ?>"
               aria-invalid="<?= isset($errors['name']) ? 'true' : 'false' ?>"
               aria-describedby="<?= isset($errors['name']) ? 'name-error' : '' ?>">
        <?php if (isset($errors['name'])): ?>
            <p id="name-error" class="field-error"><?= htmlspecialchars($errors['name']) ?></p>
        <?php endif; ?>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required
               value="<?= $submittedEmail ?: $getEmail ?>"
               placeholder="Enter your email"
               class="<?= isset($errors['email']) ? 'invalid' : '' ?>"
               aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>"
               aria-describedby="<?= isset($errors['email']) ? 'email-error' : '' ?>">
        <?php if (isset($errors['email'])): ?>
            <p id="email-error" class="field-error"><?= htmlspecialchars($errors['email']) ?></p>
        <?php endif; ?>

        <button type="submit">Submit (POST)</button>
    </form>

   
</body>
</html>
