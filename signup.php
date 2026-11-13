<?php
require __DIR__ . '/includes/bootstrap.php';
$title = 'Sign up';
$errors = []; $ok = false;
$v = ['fullname' => '', 'username' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { $errors[] = 'Invalid form token. Reload the page and try again.'; }
    foreach (array_keys($v) as $k) { $v[$k] = trim((string)($_POST[$k] ?? '')); }
    $password = (string)($_POST['password'] ?? '');
    $confirm  = (string)($_POST['confirm'] ?? '');

    if ($v['fullname'] === '') { $errors[] = 'Full name is required.'; }
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $v['username'])) { $errors[] = 'Username: 3-30 letters, numbers or underscore.'; }
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid email is required.'; }
    if (strlen($password) < 8) { $errors[] = 'Password must be at least 8 characters.'; }
    if ($password !== $confirm) { $errors[] = 'Passwords do not match.'; }

    if (!$errors) {
        $db = db();
        $chk = $db->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $chk->bind_param('ss', $v['username'], $v['email']);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $errors[] = 'Username or email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $db->prepare('INSERT INTO users (fullname, username, email, phone, password_hash) VALUES (?,?,?,?,?)');
            $ins->bind_param('sssss', $v['fullname'], $v['username'], $v['email'], $v['phone'], $hash);
            $ins->execute();
            $ok = true;
            $v = array_fill_keys(array_keys($v), '');
        }
    }
}
require __DIR__ . '/includes/header.php';
?>
<h1>Sign up</h1>
<?php foreach ($errors as $m): ?><div class="err"><?= e($m) ?></div><?php endforeach; ?>
<?php if ($ok): ?><div class="ok">Account created. You can now <a href="login.php">log in</a>.</div><?php endif; ?>
<form method="post" action="signup.php">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label>Full name <input name="fullname" value="<?= e($v['fullname']) ?>" required></label>
  <label>Username <input name="username" value="<?= e($v['username']) ?>" required></label>
  <label>Email <input type="email" name="email" value="<?= e($v['email']) ?>" required></label>
  <label>Phone <input name="phone" value="<?= e($v['phone']) ?>"></label>
  <label>Password <input type="password" name="password" required></label>
  <label>Confirm password <input type="password" name="confirm" required></label>
  <button type="submit">Create account</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
