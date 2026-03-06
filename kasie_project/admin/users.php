<?php
/**
 * Admin - Manage Users
 * Admins can view all users and promote any user to admin
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

requireRole('admin');

$message = '';
$error = '';

// Handle promote to admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promote_user_id'])) {
    $targetId = (int) $_POST['promote_user_id'];
    $currentUserId = (int) $_SESSION['user_id'];

    if ($targetId > 0 && $targetId !== $currentUserId) {
        $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
        $stmt->bind_param("i", $targetId);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $message = 'User promoted to admin successfully.';
        } else {
            $error = 'Failed to promote user.';
        }
        $stmt->close();
    } else {
        $error = 'Invalid request.';
    }
}

// Fetch all users
$result = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at ASC");
$users = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="dashboard">
    <div class="container">
        <header class="dashboard-header">
            <h1>Manage Users</h1>
            <div class="header-actions">
                <a href="../dashboard.php" class="btn btn-outline">← Dashboard</a>
                <a href="../logout.php" class="btn btn-outline">Log Out</a>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <section class="content-section">
            <h2>All Users</h2>
            <p class="subtitle">Promote users to admin. Promoted users must log out and log back in to access admin features.</p>

            <?php if (empty($users)): ?>
                <p>No users found.</p>
            <?php else: ?>
                <div class="users-table-wrapper">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['name']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <span class="badge role-<?= htmlspecialchars($u['role']) ?>">
                                            <?= ucfirst($u['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                                    <td>
                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <form method="post" style="display:inline;" onsubmit="return confirm('Promote this user to admin?');">
                                                <input type="hidden" name="promote_user_id" value="<?= (int) $u['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-primary">Make Admin</button>
                                            </form>
                                        <?php elseif ((int) $u['id'] === (int) $_SESSION['user_id']): ?>
                                            <span class="text-muted">(you)</span>
                                        <?php else: ?>
                                            <span class="text-muted">Admin</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
