<?php
session_start();
include "../config/database.php";

$message = "";

if (isset($_POST['register']))
{
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate required fields
    if (
        empty($fullname) ||
        empty($username) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    )
    {
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    }
    // Validate password confirmation
    elseif ($password != $confirm_password)
    {
        $message = "<div class='alert alert-danger'>Passwords do not match.</div>";
    }
    else
    {
        // Check username
        $checkUsername = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");

        if (mysqli_num_rows($checkUsername) > 0)
        {
            $message = "<div class='alert alert-warning'>Username already exists.</div>";
        }
        else
        {
            // Check email
            $checkEmail = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

            if (mysqli_num_rows($checkEmail) > 0)
            {
                $message = "<div class='alert alert-warning'>Email already exists.</div>";
            }
            else
            {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert = mysqli_query(
                    $conn,
                    "INSERT INTO users(fullname, username, email, password, role)
                     VALUES('$fullname', '$username', '$email', '$hashed_password', 'employee')"
                );

                if ($insert)
                {
                    $message = "<div class='alert alert-success'>
                                    Registration successful. You can now login.
                                </div>";
                }
                else
                {
                    $message = "<div class='alert alert-danger'>
                                    Registration failed.<br>
                                    Error: " . mysqli_error($conn) . "
                                </div>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register | KDFMS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f7fa;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.register-card{
    width:500px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0px 0px 20px rgba(0,0,0,0.1);
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

<div class="register-card">

<h2 class="logo">KDFMS Registration</h2>

<?php echo $message; ?>

<form method="POST">

<div class="mb-3">
<label>Full Name</label>
<input type="text"
name="fullname"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Username</label>
<input
type="text"
name="username"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Email Address</label>
<input type="email"
name="email"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password"
name="password"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Confirm Password</label>
<input type="password"
name="confirm_password"
class="form-control"
required>
</div>

<button type="submit"
name="register"
class="btn btn-success w-100">
Register
</button>

<div class="text-center mt-3">
Already have an account?
<a href="login.php">Login</a>
</div>

</form>

</div>

</body>
</html>