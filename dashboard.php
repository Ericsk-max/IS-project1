<?php
require __DIR__ . '/includes/bootstrap.php';
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$title = 'Dashboard';
$stmt = db()->prepare('SELECT fullname, username, email, phone, created_at FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();
require __DIR__ . '/includes/header.php';
?>
<h1>Welcome, <?= e($u['fullname']) ?></h1>
<div class="ok">Login successful.</div>
<table>
  <tr><th>Username</th><td><?= e($u['username']) ?></td></tr>
  <tr><th>Email</th><td><?= e($u['email']) ?></td></tr>
  <tr><th>Phone</th><td><?= e((string)$u['phone']) ?></td></tr>
  <tr><th>Registered</th><td><?= e($u['created_at']) ?></td></tr>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
