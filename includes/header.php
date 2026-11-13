<?php /* expects $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'CAT 2') ?> | CAT 2 PHP App</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav>
  <a href="index.php">Home</a>
  <a href="feedback.php">Feedback</a>
  <?php if (!empty($_SESSION['user'])): ?>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Logout (<?= e($_SESSION['user']['username']) ?>)</a>
  <?php else: ?>
    <a href="signup.php">Sign up</a>
    <a href="login.php">Login</a>
  <?php endif; ?>
</nav>
<main>
