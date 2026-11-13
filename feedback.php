<?php
require __DIR__ . '/includes/bootstrap.php';
$title = 'Feedback';
$errors = []; $ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { $errors[] = 'Invalid form token. Reload the page and try again.'; }
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    if ($name === '' || $message === '') { $errors[] = 'Name and message are required.'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid email is required.'; }
    if (!$errors) {
        $ins = db()->prepare('INSERT INTO feedback (name, email, message) VALUES (?,?,?)');
        $ins->bind_param('sss', $name, $email, $message);
        $ins->execute();
        $ok = true;
    }
}
$rows = db()->query('SELECT id, name, email, message, created_at FROM feedback ORDER BY id DESC LIMIT 20');
require __DIR__ . '/includes/header.php';
?>
<h1>Feedback</h1>
<?php foreach ($errors as $m): ?><div class="err"><?= e($m) ?></div><?php endforeach; ?>
<?php if ($ok): ?><div class="ok">Thank you, your feedback was saved.</div><?php endif; ?>
<form method="post" action="feedback.php">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label>Name <input name="name" required></label>
  <label>Email <input type="email" name="email" required></label>
  <label>Message <textarea name="message" rows="4" required></textarea></label>
  <button type="submit">Submit</button>
</form>
<h2>Saved entries</h2>
<table>
  <tr><th>#</th><th>Name</th><th>Email</th><th>Message</th><th>Time</th></tr>
  <?php while ($r = $rows->fetch_assoc()): ?>
  <tr><td><?= (int)$r['id'] ?></td><td><?= e($r['name']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['message']) ?></td><td><?= e($r['created_at']) ?></td></tr>
  <?php endwhile; ?>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
