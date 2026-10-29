<?php

$pageTitle = "Add Cow";
$activePage = "cows";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

$message = "";
$messageType = "";

if(isset($_POST['save']))
{

    $tag_number = trim($_POST['tag_number']);
    $name = trim($_POST['name']);
    $breed = trim($_POST['breed']);
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $status = $_POST['status'];

    if(
        empty($tag_number) ||
        empty($name) ||
        empty($breed) ||
        empty($gender)
    ){

        $message = "Please fill all required fields.";
        $messageType = "danger";

    }else{

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM cows WHERE tag_number=?"
        );

        mysqli_stmt_bind_param($check,"s",$tag_number);

        mysqli_stmt_execute($check);

        mysqli_stmt_store_result($check);

        if(mysqli_stmt_num_rows($check)>0){

            $message="Cow Tag Number already exists.";

            $messageType="warning";

        }else{

            $insert=mysqli_prepare(
                $conn,
                "INSERT INTO cows
                (
                    tag_number,
                    name,
                    breed,
                    gender,
                    date_of_birth,
                    status
                )
                VALUES(?,?,?,?,?,?)"
            );

            mysqli_stmt_bind_param(
                $insert,
                "ssssss",
                $tag_number,
                $name,
                $breed,
                $gender,
                $date_of_birth,
                $status
            );

            if(mysqli_stmt_execute($insert))
            {

                if(isset($_SESSION['user_id']))
                {

                    $activity="Registered Cow ($tag_number)";

                    $user=$_SESSION['user_id'];

                    mysqli_query($conn,"
                    INSERT INTO activities(user_id,activity,module)
                    VALUES(
                        '$user',
                        '$activity',
                        'Cow Management'
                    )");

                }

                header("Location: manage.php?success=Cow registered successfully");

                exit();

            }else{

                $message="Unable to save cow.";

                $messageType="danger";

            }

        }

    }

}

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Add Cow | KDFMS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:linear-gradient(
        135deg,
        #f8fffc,
        #eefaf3,
        #e6f7ee
    );
    min-height:100vh;
}

.form-card{
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.page-header{
    background:linear-gradient(
        135deg,
        #198754,
        #20c997
    );
    color:white;
    padding:20px;
    border-radius:20px;
    margin-bottom:25px;
}

.btn-success{
    background:#198754;
    border:none;
}

.btn-success:hover{
    background:#157347;
}
.footer{
    background:linear-gradient(
        135deg,
        #198754,
        #20c997
    );

    color:white;
    padding:25px;
    margin-top:30px;

    border-radius:15px 15px 0 0;

    box-shadow:0 -2px 10px rgba(0,0,0,.1);
}

</style>

</head>

<body>

<div class="main-content">

<div class="page-header">

    <h2>

        <i class="bi bi-plus-circle-fill"></i>

        Register New Cow

    </h2>

    <p>

        Add a new dairy cow into the farm management system.

    </p>

</div>


<div class="glass-card">

<div class="card-header-custom">

<h4>

<i class="bi bi-tags-fill"></i>

Cow Information

</h4>

</div>

<div class="card-body-custom">

<?php if(!empty($message)): ?>

<div class="alert alert-<?php echo $messageType; ?>">

<?php echo $message; ?>

</div>

<?php endif; ?>


<form method="POST">

<div class="row">

<div class="col-md-6 mb-4">

<label class="form-label">

Tag Number *

</label>

<input
type="text"
name="tag_number"
class="form-control"
placeholder="Example: KD001"
required>

</div>


<div class="col-md-6 mb-4">

<label class="form-label">

Cow Name *

</label>

<input
type="text"
name="name"
class="form-control"
placeholder="Example: Daisy"
required>

</div>


<div class="col-md-6 mb-4">

<label class="form-label">

Breed *

</label>

<select
name="breed"
class="form-select"
required>

<option value="">Choose Breed</option>

<option>Friesian</option>

<option>Ayrshire</option>

<option>Jersey</option>

<option>Guernsey</option>

<option>Sahiwal</option>

<option>Holstein Friesian</option>

<option>Cross Breed</option>

</select>

</div>


<div class="col-md-6 mb-4">

<label class="form-label">

Gender *

</label>

<select
name="gender"
class="form-select"
required>

<option value="">Choose Gender</option>

<option>Female</option>

<option>Male</option>

</select>

</div>


<div class="col-md-6 mb-4">

<label class="form-label">

Date of Birth

</label>

<input
type="date"
name="date_of_birth"
class="form-control">

</div>


<div class="col-md-6 mb-4">

<label class="form-label">

Status

</label>

<select
name="status"
class="form-select">

<option selected>Active</option>

<option>Pregnant</option>

<option>Dry</option>

<option>Sold</option>

<option>Sick</option>

<option>Dead</option>

</select>

</div>

</div>


<div class="divider"></div>

<div class="d-flex justify-content-end">

<a
href="manage.php"
class="btn btn-secondary me-2">

<i class="bi bi-arrow-left"></i>

Back

</a>


<button
type="submit"
name="save"
class="btn btn-success">

<i class="bi bi-check-circle-fill"></i>

Register Cow

</button>

</div>

</form>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>
   <footer class="footer text-center">

    <h6 class="mb-2">
        Kapketet Dairy Farm Management System
    </h6>

    <p class="mb-1">
        Developed by Carlos
    </p>

    <small>
        © <?php echo date('Y'); ?> All Rights Reserved
    </small>

</footer>
</html>