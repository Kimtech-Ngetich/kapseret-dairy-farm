<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kapkatet Dairy Farm Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f7fa;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card-box{
    width:500px;
    padding:40px;
    border-radius:15px;
    box-shadow:0 0 25px rgba(0,0,0,0.1);
    background:white;
    text-align:center;
}

.logo{
    font-size:28px;
    font-weight:bold;
    color:#198754;
}

.subtitle{
    color:gray;
    margin-bottom:25px;
}

</style>

</head>
<body>

<div class="card-box">

<h1 class="logo">KDFMS</h1>

<p class="subtitle">
Kapseret Dairy Farm Management System
</p>

<a href="auth/login.php" class="btn btn-success w-100 mb-3">
Login
</a>

<a href="auth/register.php" class="btn btn-outline-success w-100">
Register
</a>

</div>

</body>
</html>