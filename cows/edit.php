<?php

$pageTitle = "Edit Cow";
$activePage = "cows";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM cows WHERE id='$id'");

if (mysqli_num_rows($result) == 0) {
    header("Location: manage.php");
    exit();
}

$cow = mysqli_fetch_assoc($result);

$message = "";
$messageType = "";

if(isset($_POST['update']))
{

    $tag_number = mysqli_real_escape_string($conn, trim($_POST['tag_number']));
    $name       = mysqli_real_escape_string($conn, trim($_POST['name']));
    $breed      = mysqli_real_escape_string($conn, trim($_POST['breed']));
    $gender     = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob        = $_POST['date_of_birth'];
    $status     = mysqli_real_escape_string($conn,$_POST['status']);

    $check = mysqli_query($conn,"
        SELECT id
        FROM cows
        WHERE tag_number='$tag_number'
        AND id != '$id'
    ");

    if(mysqli_num_rows($check)>0){

        $message="Tag number already exists.";

        $messageType="danger";

    }

    else{

        $update=mysqli_query($conn,"
            UPDATE cows SET

            tag_number='$tag_number',

            name='$name',

            breed='$breed',

            gender='$gender',

            date_of_birth='$dob',

            status='$status'

            WHERE id='$id'
        ");

        if($update){

            mysqli_query($conn,"
                INSERT INTO activities(activity)

                VALUES('Updated cow $tag_number')
            ");

            header("Location: view.php?id=$id&updated=1");
            exit();

        }

        else{

            $message="Failed to update cow.";

            $messageType="danger";

        }

    }

}

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>
<i class="bi bi-pencil-square"></i>
Edit Cow
</h2>

<p>
Update the details of this cow.
</p>

</div>

<?php if($message!=""): ?>

<div class="alert alert-<?= $messageType ?>">

<?= $message ?>

</div>

<?php endif; ?>

<div class="glass-card">

<div class="card-header-custom">

<h4>

<i class="bi bi-tags-fill"></i>

Cow Information

</h4>

</div>

<div class="card-body-custom">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Tag Number

</label>

<input
type="text"
name="tag_number"
class="form-control"
required
value="<?= htmlspecialchars($cow['tag_number']) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Cow Name

</label>

<input
type="text"
name="name"
class="form-control"
required
value="<?= htmlspecialchars($cow['name']) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Breed

</label>

<select
name="breed"
class="form-select"
required>

<?php

$breeds=[
"Friesian",
"Ayrshire",
"Jersey",
"Guernsey"
];

foreach($breeds as $breed){

$selected=($cow['breed']==$breed)?"selected":"";

echo "<option $selected>$breed</option>";

}

?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Gender

</label>

<select
name="gender"
class="form-select"
required>

<option <?=($cow['gender']=="Female")?"selected":""?>>
Female
</option>

<option <?=($cow['gender']=="Male")?"selected":""?>>
Male
</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Date of Birth

</label>

<input
type="date"
name="date_of_birth"
class="form-control"
value="<?= $cow['date_of_birth'] ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Status

</label>

<select
name="status"
class="form-select">

<option <?=($cow['status']=="Active")?"selected":""?>>
Active
</option>

<option <?=($cow['status']=="Sold")?"selected":""?>>
Sold
</option>

<option <?=($cow['status']=="Dead")?"selected":""?>>
Dead
</option>

</select>

</div>

</div>

<hr>

<div class="d-flex justify-content-end gap-2">

<a href="view.php?id=<?= $id ?>" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

<button
type="submit"
name="update"
class="btn btn-success">

<i class="bi bi-check-circle"></i>

Update Cow

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>