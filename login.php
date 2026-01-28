<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "toko_online");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek kecocokan di database
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['status_login'] = true;
        $_SESSION['user_admin'] = $username;
        header("Location: index.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 350px; }
        input { width: 100%; padding: 12px; margin: 10px 0 20px; border: 1.5px solid #e2e8f0; border-radius: 12px; outline: none; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #6366f1; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: bold; }
        .error { color: #ef4444; font-size: 13px; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 style="text-align: center; color: #6366f1;">Admin Login</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Masuk ke Dashboard</button>
        </form>
    </div>
</body>
</html>