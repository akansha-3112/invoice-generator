<?php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Load session values
$username = $_SESSION['user'];
$business_name = $_SESSION['business_name'] ?? 'N/A';
$logo_path = $_SESSION['logo'] ?? '';
$address = $_SESSION['address'] ?? 'N/A';
$phone = $_SESSION['phone'] ?? 'N/A';
$email = $_SESSION['email'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Account</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .account-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      width: 100%;
      padding: 30px;
    }
    .header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 25px;
    }
    .header img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #d1d5db;
    }
    .header h2 {
      margin: 0;
      font-size: 1.5rem;
      color: #111827;
    }
    .details {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 15px;
      color: #374151;
      font-size: 0.95rem;
    }
    .details label {
      font-weight: bold;
      text-align: right;
    }
    .logout {
      display: inline-block;
      margin-top: 30px;
      background: #ef4444;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      text-align: center;
    }
    .logout:hover {
      background: #dc2626;
    }
     .index {
      display: inline-block;
      margin-top: 30px;
      background: #ef4444;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      text-align: center;
    }
    .index:hover {
      background: #dc2626;
    }
    
  </style>
</head>
<body>
  <div class="account-container">
    <div class="header">
      <?php if (!empty($logo_path) && file_exists($logo_path)): ?>
        <img src="<?= htmlspecialchars($logo_path) ?>" alt="Business Logo">
      <?php else: ?>
        <img src="default_logo.png" alt="No Logo">
      <?php endif; ?>
      <h2><?= htmlspecialchars($business_name) ?></h2>
    </div>

    <div class="details">
      <label>Username:</label>
      <div><?= htmlspecialchars($username) ?></div>

      <label>Email:</label>
      <div><?= htmlspecialchars($email) ?></div>

      <label>Phone:</label>
      <div><?= htmlspecialchars($phone) ?></div>

      <label>Address:</label>
      <div><?= htmlspecialchars($address) ?></div>
    </div>

    <a href="logout.php" class="logout">Logout</a>
    <a href="index.php" class="index">Home Page</a>
  </div>
</body>
</html>
