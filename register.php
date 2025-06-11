<?php
$usersFile = 'users.txt';
$uploadDir = 'uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $business_name = trim($_POST['business_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($username && $password && $confirm_password && $business_name && $address && $contact && $email && isset($_FILES['logo'])) {
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $users = file_exists($usersFile) ? file($usersFile, FILE_IGNORE_NEW_LINES) : [];
            foreach ($users as $user) {
                list($u, ) = explode('|', $user);
                if ($u === $username) {
                    $error = "Username already exists.";
                    break;
                }
            }

            if (!isset($error)) {
                // Handle logo upload
                $logoName = '';
                if ($_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                    $logoName = uniqid('logo_') . '.' . $ext;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $logoName);
                }

                // Save user details
                $entry = implode('|', [
                    $username,
                    password_hash($password, PASSWORD_DEFAULT),
                    $business_name,
                    str_replace(["\r", "\n"], ' ', $address),
                    $contact,
                    $email,
                    $logoName
                ]);
                file_put_contents($usersFile, $entry . PHP_EOL, FILE_APPEND);
                header('Location: login.php');
                exit;
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            background: #f3f4f6;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .form-box {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 360px;
            overflow-y: auto;
            max-height: 95vh;
        }
        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 0.6rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 0.6rem;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #059669;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
        .form-footer {
            text-align: center;
            margin-top: 1rem;
        }
        .form-footer a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">
            <h2>Register</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="text" name="business_name" placeholder="Business Name" required>
            <textarea name="address" placeholder="Business Address" rows="3" required></textarea>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="file" name="logo" accept="image/*" required>
            <button type="submit">Register</button>
            <div class="form-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>
</body>
</html>
