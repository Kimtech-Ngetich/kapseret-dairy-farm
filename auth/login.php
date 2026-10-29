<?php
session_start();

include "../config/database.php";
require_once "../includes/system_settings.php";

$message = "";

if (isset($_POST['submit'])) {

    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Find user by username or email
    $sql = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $login, $login);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Check account status
            if ($user['status'] !== 'active') {

                $message = "<div class='alert alert-danger'>
                                Your account has been deactivated.
                            </div>";

            } else {

                // Store session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: ../dashboard/index.php");
                exit();
            }

        } else {

            $message = "<div class='alert alert-danger'>
                            Incorrect password.
                        </div>";
        }

    } else {

        $message = "<div class='alert alert-danger'>
                        User not found.
                    </div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login | KDFMS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f7fa;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-card{
    width:450px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 20px rgba(0,0,0,0.1);
}

.logo{
    text-align:center;
    color:#198754;
    font-weight:bold;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="login-card">

<h2 class="logo">KDFMS Login</h2>

<?php echo $message; ?>

<form method="POST">

<div class="mb-3">
    <label>Username or Email</label>
    <input
        type="text"
        name="login"
        class="form-control"
        placeholder="Enter username or email"
        required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password"
name="password"
class="form-control"
required>
</div>

<button
    type="submit"
    name="submit"
    class="btn btn-success w-100">
    Login
</button>

<div class="text-center mt-3">
Don't have an account?
<a href="register.php">Register</a>
</div>

</form>

</div>

</body>
</html>