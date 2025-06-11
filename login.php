<?php
session_start();
$usersFile = 'users.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $users = file_exists($usersFile) ? file($usersFile, FILE_IGNORE_NEW_LINES) : [];
        foreach ($users as $user) {
            $fields = explode('|', $user);
            if (count($fields) >= 2) {
                list($u, $hashed_pass) = $fields;
               if ($u === $username && password_verify($password, $hashed_pass)) {
    $_SESSION['user'] = $username;
    $_SESSION['business_name'] = $fields[2] ?? '';
    $_SESSION['address'] = $fields[3] ?? '';
    $_SESSION['phone'] = $fields[4] ?? '';
    $_SESSION['email'] = $fields[5] ?? '';
    $_SESSION['logo'] = $fields[6] ?? '';
    header('Location: index.php');
    exit;
}

            }
        }
        $error = "Invalid username or password.";
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 0.6rem;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4338ca;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
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
        <form method="POST">
            <h2>Login</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <div class="form-footer">
                <p>No account? <a href="register.php">Register here</a></p>
            </div>
        </form>
    </div>
</body>
</html>
