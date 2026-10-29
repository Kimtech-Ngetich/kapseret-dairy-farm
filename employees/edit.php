<?php

$pageTitle = "Edit Employee";
$activePage = "employees";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$error = "";

/*
|--------------------------------------------------------------------------
| GET EMPLOYEE
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM employees
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location:index.php?error=1");
    exit;
}

$employee = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| UPDATE EMPLOYEE
|--------------------------------------------------------------------------
*/

if(isset($_POST['update']))
{
    $employee_number = mysqli_real_escape_string($conn,$_POST['employee_number']);
    $full_name = mysqli_real_escape_string($conn,$_POST['full_name']);
    $gender = mysqli_real_escape_string($conn,$_POST['gender']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $national_id = mysqli_real_escape_string($conn,$_POST['national_id']);
    $department = mysqli_real_escape_string($conn,$_POST['department']);
    $position = mysqli_real_escape_string($conn,$_POST['position']);
    $employment_type = mysqli_real_escape_string($conn,$_POST['employment_type']);
    $salary = (float)$_POST['salary'];
    $hire_date = $_POST['hire_date'];
    $status = mysqli_real_escape_string($conn,$_POST['status']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);

    /*
    |--------------------------------------------------------------------------
    | CHECK DUPLICATE EMPLOYEE NUMBER
    |--------------------------------------------------------------------------
    */

    $exists = mysqli_query($conn,"
    SELECT id
    FROM employees
    WHERE employee_number='$employee_number'
    AND id<>'$id'
    ");

    if(mysqli_num_rows($exists)>0)
    {
        $error = "Employee number already exists.";
    }
    else
    {
        $update = mysqli_query($conn,"
        UPDATE employees
        SET

        employee_number='$employee_number',
        full_name='$full_name',
        gender='$gender',
        phone='$phone',
        email='$email',
        national_id='$national_id',
        department='$department',
        position='$position',
        employment_type='$employment_type',
        salary='$salary',
        hire_date='$hire_date',
        status='$status',
        address='$address'

        WHERE id='$id'
        ");

        if($update)
        {
            header("Location:index.php?updated=1");
            exit;
        }
        else
        {
            $error = "Unable to update employee.";
        }
    }
}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-pencil-square"></i>

Edit Employee

</h2>

<p>

Update employee information.

</p>

</div>

<?php if(!empty($error)): ?>

<div class="alert alert-danger alert-dismissible fade show">

<i class="bi bi-exclamation-triangle-fill"></i>

<?= htmlspecialchars($error); ?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-person-vcard-fill"></i>

Employee Details

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<!-- Employee Number -->

<div class="col-md-4 mb-3">

<label class="form-label">

Employee Number

</label>

<input
type="text"
name="employee_number"
class="form-control"
value="<?= htmlspecialchars($employee['employee_number']); ?>"
required>

</div>

<!-- Full Name -->

<div class="col-md-8 mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
name="full_name"
class="form-control"
value="<?= htmlspecialchars($employee['full_name']); ?>"
required>

</div>

<!-- Gender -->

<div class="col-md-4 mb-3">

<label class="form-label">

Gender

</label>

<select
name="gender"
class="form-select"
required>

<option value="Male" <?= $employee['gender']=="Male" ? "selected" : ""; ?>>Male</option>

<option value="Female" <?= $employee['gender']=="Female" ? "selected" : ""; ?>>Female</option>

</select>

</div>

<!-- Phone -->

<div class="col-md-4 mb-3">

<label class="form-label">

Phone Number

</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($employee['phone']); ?>">

</div>

<!-- Email -->

<div class="col-md-4 mb-3">

<label class="form-label">

Email Address

</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($employee['email']); ?>">

</div>

<!-- National ID -->

<div class="col-md-4 mb-3">

<label class="form-label">

National ID

</label>

<input
type="text"
name="national_id"
class="form-control"
value="<?= htmlspecialchars($employee['national_id']); ?>">

</div>

<!-- Department -->

<div class="col-md-4 mb-3">

<label class="form-label">

Department

</label>

<input
type="text"
name="department"
class="form-control"
value="<?= htmlspecialchars($employee['department']); ?>"
required>

</div>

<!-- Position -->

<div class="col-md-4 mb-3">

<label class="form-label">

Position

</label>

<input
type="text"
name="position"
class="form-control"
value="<?= htmlspecialchars($employee['position']); ?>"
required>

</div>

<!-- Employment Type -->

<div class="col-md-4 mb-3">

<label class="form-label">

Employment Type

</label>

<select
name="employment_type"
class="form-select">

<option value="Permanent" <?= $employee['employment_type']=="Permanent" ? "selected":""; ?>>Permanent</option>

<option value="Contract" <?= $employee['employment_type']=="Contract" ? "selected":""; ?>>Contract</option>

<option value="Casual" <?= $employee['employment_type']=="Casual" ? "selected":""; ?>>Casual</option>

</select>

</div>

<!-- Salary -->

<div class="col-md-4 mb-3">

<label class="form-label">

Monthly Salary (KSh)

</label>

<input
type="number"
step="0.01"
min="0"
name="salary"
class="form-control"
value="<?= $employee['salary']; ?>">

</div>

<!-- Hire Date -->

<div class="col-md-4 mb-3">

<label class="form-label">

Hire Date

</label>

<input
type="date"
name="hire_date"
class="form-control"
value="<?= $employee['hire_date']; ?>">

</div>

<!-- Status -->

<div class="col-md-4 mb-3">

<label class="form-label">

Employment Status

</label>

<select
name="status"
class="form-select">

<option value="Active" <?= $employee['status']=="Active" ? "selected":""; ?>>Active</option>

<option value="On Leave" <?= $employee['status']=="On Leave" ? "selected":""; ?>>On Leave</option>

<option value="Suspended" <?= $employee['status']=="Suspended" ? "selected":""; ?>>Suspended</option>

<option value="Terminated" <?= $employee['status']=="Terminated" ? "selected":""; ?>>Terminated</option>

</select>

</div>

<!-- Address -->

<div class="col-12 mb-4">

<label class="form-label">

Address

</label>

<textarea
name="address"
class="form-control"
rows="4"><?= htmlspecialchars($employee['address']); ?></textarea>

</div>

</div>

<div class="text-end">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

<button
type="submit"
name="update"
class="btn btn-warning">

<i class="bi bi-check-circle"></i>

Update Employee

</button>

</div>

</form>

</div>

</div>
</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    const employeeNumber = document.querySelector("[name='employee_number']");
    const fullName = document.querySelector("[name='full_name']");
    const gender = document.querySelector("[name='gender']");
    const phone = document.querySelector("[name='phone']");
    const email = document.querySelector("[name='email']");
    const department = document.querySelector("[name='department']");
    const position = document.querySelector("[name='position']");
    const salary = document.querySelector("[name='salary']");

    form.addEventListener("submit", function(e){

        if(employeeNumber.value.trim() === "")
        {
            alert("Employee number is required.");
            employeeNumber.focus();
            e.preventDefault();
            return;
        }

        if(fullName.value.trim() === "")
        {
            alert("Please enter the employee's full name.");
            fullName.focus();
            e.preventDefault();
            return;
        }

        if(gender.value === "")
        {
            alert("Please select gender.");
            gender.focus();
            e.preventDefault();
            return;
        }

        if(email.value !== "")
        {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if(!emailPattern.test(email.value))
            {
                alert("Please enter a valid email address.");
                email.focus();
                e.preventDefault();
                return;
            }
        }

        if(phone.value !== "" && phone.value.length < 10)
        {
            alert("Please enter a valid phone number.");
            phone.focus();
            e.preventDefault();
            return;
        }

        if(department.value.trim() === "")
        {
            alert("Department is required.");
            department.focus();
            e.preventDefault();
            return;
        }

        if(position.value.trim() === "")
        {
            alert("Position is required.");
            position.focus();
            e.preventDefault();
            return;
        }

        if(salary.value !== "")
        {
            if(parseFloat(salary.value) < 0)
            {
                alert("Salary cannot be negative.");
                salary.focus();
                e.preventDefault();
                return;
            }
        }

    });

});

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>