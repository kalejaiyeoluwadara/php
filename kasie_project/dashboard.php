<?php
/**
 * Dashboard - Multi-User Access
 * Role-restricted content: articles, images, videos
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

$userName = $_SESSION['user_name'] ?? 'User';
$userRole = getCurrentRole();

// Fetch content user has permission to see
$roleOrder = ['user' => 1, 'editor' => 2, 'admin' => 3];
$userLevel = $roleOrder[$userRole] ?? 0;

$stmt = $conn->prepare("
    SELECT id, title, body, content_type, min_role
    FROM content
    WHERE CASE min_role
        WHEN 'user' THEN 1
        WHEN 'editor' THEN 2
        WHEN 'admin' THEN 3
    END <= ?
    ORDER BY min_role DESC, id ASC
");
$stmt->bind_param("i", $userLevel);
$stmt->execute();
$contentResult = $stmt->get_result();
$contentItems = $contentResult->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$errorMsg = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Multi-User Login System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard">
    <div class="container">
        <header class="dashboard-header">
            <h1>Welcome, <?= htmlspecialchars($userName) ?></h1>
            <div class="header-actions">
                <span class="badge role-<?= htmlspecialchars($userRole) ?>"><?= ucfirst($userRole) ?></span>
                <a href="logout.php" class="btn btn-outline">Log Out</a>
            </div>
        </header>

        <?php if ($errorMsg === 'insufficient_permissions'): ?>
            <div class="message error">You do not have permission to access that page.</div>
        <?php endif; ?>

        <section class="content-section">
            <h2>Your Content</h2>
            <p class="subtitle">Content available to your role (<?= ucfirst($userRole) ?>)</p>

            <?php if (empty($contentItems)): ?>
                <p>No content available for your role.</p>
            <?php else: ?>
                <div class="content-grid">
                    <?php foreach ($contentItems as $item): ?>
                        <article class="content-card content-<?= htmlspecialchars($item['content_type']) ?>">
                            <span class="content-type-badge"><?= ucfirst($item['content_type']) ?></span>
                            <span class="content-role-badge"><?= ucfirst($item['min_role']) ?>+</span>
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($item['body'])) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <?php if (hasRole('editor')): ?>
        <section class="content-section">
            <h2>Editor Tools</h2>
            <p>As an editor, you can manage content. (Demo placeholder)</p>
        </section>
        <?php endif; ?>

        <?php if (hasRole('admin')): ?>
        <section class="content-section admin-section">
            <h2>Admin Panel</h2>
            <p>Administrators have full access to all system features.</p>
            <a href="admin/users.php" class="btn btn-primary">Manage Users</a>
        </section>
        <?php endif; ?>
    </div>
</body>
</html>
