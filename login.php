<?php 
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data pengguna dari database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if ($password === $user['password']) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Pengguna tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #17a2b8;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 520px;  /* Container diperlebar */
            padding: 35px 60px; /* Padding kanan kiri diperbesar */
            border: none;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        .login-container h2 {
            color: black;
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 600;
        }
        .logo {
            width: 110px;
            height: auto;
            display: block;
            margin: 0 auto 20px;
        }
        .form-group {
            margin-bottom: 20px;
            padding: 0 15px; /* Padding kanan kiri form group */
        }
        .form-group label {
            color: #495057;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 6px;
        }
        .form-control {
            height: 42px;
            border: 1.5px solid #ced4da;
            border-radius: 8px;
            padding: 8px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            width: 100%; /* Memastikan input field mengisi lebar container */
        }
        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
        }
        .btn-primary {
            background-color: #17a2b8;
            border: none;
            border-radius: 8px;
            height: 40px;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 10px;
            transition: all 0.3s ease;
            width: 140px; /* Button diperlebar */
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-primary:hover {
            background-color: #138496;
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        .form-group input::placeholder {
            color: #adb5bd;
            font-size: 13px;
        }
        @media (max-width: 576px) {
            .login-container {
                max-width: 90%;
                padding: 25px 20px;
                margin: 15px;
            }
            .form-group {
                padding: 0;
            }
            .logo {
                width: 90px;
            }
            .btn-primary {
                width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="logo-unissula.png" alt="Logo Unissula" class="logo">
        <h2 class="text-center">Login</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>