<?php
// Handle POST (form submission)
$submittedName = '';
$submittedEmail = '';
$postMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedName = htmlspecialchars($_POST['name'] ?? '');
    $submittedEmail = htmlspecialchars($_POST['email'] ?? '');
    $postMessage = "Form submitted via POST: Hello {$submittedName}, we received your email: {$submittedEmail}";
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
    </style>
</head>
<body>
    <h1>Contact Form</h1>

    <?php if ($postMessage): ?>
        <div class="message"><?= $postMessage ?></div>
    <?php endif; ?>

    <?php if ($getMessage): ?>
        <div class="message"><?= $getMessage ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required
               value="<?= $submittedName ?: $getName ?>"
               placeholder="Enter your name">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required
               value="<?= $submittedEmail ?: $getEmail ?>"
               placeholder="Enter your email">

        <button type="submit">Submit (POST)</button>
    </form>

   
</body>
</html>
