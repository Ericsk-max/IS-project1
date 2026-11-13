<?php
require __DIR__ . '/includes/bootstrap.php';
$title = 'Login';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { $errors[] = 'Invalid form token. Reload the page and try again.'; }
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!$errors) {
        $stmt = db()->prepare('SELECT id, username, fullname, password_hash FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row && password_verify($password, $row['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = ['id' => $row['id'], 'username' => $row['username'], 'fullname' => $row['fullname']];
            header('Location: dashboard.php');
            exit;
        }
        $errors[] = 'Invalid username or password.';
    }
}
require __DIR__ . '/includes/header.php';
?>
<h1>Student Login (CNS 3205 CAT 2)</h1>
<?php foreach ($errors as $m): ?><div class="err"><?= e($m) ?></div><?php endforeach; ?>
<form method="post" action="login.php">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label>Username <input name="username" required></label>
  <label>Password <input type="password" name="password" required></label>
  <button type="submit">Login</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
