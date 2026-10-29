<?php

$pageTitle = "Add Employee";
$activePage = "employees";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$error = "";

/*
|--------------------------------------------------------------------------
| GENERATE EMPLOYEE NUMBER
|--------------------------------------------------------------------------
*/

$nextID = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(MAX(id),0)+1 AS next_id
FROM employees
"));

$employeeNumber = "EMP" . str_pad($nextID['next_id'], 4, "0", STR_PAD_LEFT);

/*
|--------------------------------------------------------------------------
| SAVE EMPLOYEE
|--------------------------------------------------------------------------
*/

if(isset($_POST['save']))
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
    ");

    if(mysqli_num_rows($exists)>0)
    {
        $error = "Employee number already exists.";
    }
    else
    {
        $insert = mysqli_query($conn,"
        INSERT INTO employees
        (
            employee_number,
            full_name,
            gender,
            phone,
            email,
            national_id,
            department,
            position,
            employment_type,
            salary,
            hire_date,
            status,
            address
        )
        VALUES
        (
            '$employee_number',
            '$full_name',
            '$gender',
            '$phone',
            '$email',
            '$national_id',
            '$department',
            '$position',
            '$employment_type',
            '$salary',
            '$hire_date',
            '$status',
            '$address'
        )
        ");

        if($insert)
        {
            header("Location:index.php?added=1");
            exit;
        }
        else
        {
            $error = "Unable to save employee.";
        }
    }
}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-person-plus-fill"></i>

Add Employee

</h2>

<p>

Register a new employee into the Kapseret Dairy Farm Management System.

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

Employee Information

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
value="<?= $employeeNumber; ?>"
readonly>

</div>

<!-- Full Name -->

<div class="col-md-8 mb-3">

<label class="form-label">

Full Name <span class="text-danger">*</span>

</label>

<input
type="text"
name="full_name"
class="form-control"
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

<option value="">Select Gender</option>

<option value="Male">Male</option>

<option value="Female">Female</option>

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
class="form-control">

</div>

<!-- Email -->

<div class="col-md-4 mb-3">

<label class="form-label">

Email Address

</label>

<input
type="email"
name="email"
class="form-control">

</div>

<!-- National ID -->

<div class="col-md-4 mb-3">

<label class="form-label">

National ID

</label>

<input
type="text"
name="national_id"
class="form-control">

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
placeholder="e.g. Production">

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
placeholder="e.g. Farm Manager">

</div>

<!-- Employment Type -->

<div class="col-md-4 mb-3">

<label class="form-label">

Employment Type

</label>

<select
name="employment_type"
class="form-select">

<option value="Permanent">Permanent</option>

<option value="Contract">Contract</option>

<option value="Casual">Casual</option>

</select>

</div>

<!-- Salary -->

<div class="col-md-4 mb-3">

<label class="form-label">

Monthly Salary (KSh)

</label>

<input
type="number"
name="salary"
class="form-control"
step="0.01"
min="0">

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
value="<?= date('Y-m-d'); ?>">

</div>

<!-- Status -->

<div class="col-md-4 mb-3">

<label class="form-label">

Employment Status

</label>

<select
name="status"
class="form-select">

<option value="Active">Active</option>

<option value="On Leave">On Leave</option>

<option value="Suspended">Suspended</option>

<option value="Terminated">Terminated</option>

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
rows="4"
placeholder="Enter employee address..."></textarea>

</div>

</div>

<div class="text-end">

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

<button
type="submit"
name="save"
class="btn btn-success">

<i class="bi bi-save"></i>

Save Employee

</button>

</div>

</form>

</div>

</div>
</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

    const form = document.querySelector("form");

    const fullName = document.querySelector("[name='full_name']");
    const gender = document.querySelector("[name='gender']");
    const phone = document.querySelector("[name='phone']");
    const email = document.querySelector("[name='email']");
    const department = document.querySelector("[name='department']");
    const position = document.querySelector("[name='position']");
    const salary = document.querySelector("[name='salary']");

    form.addEventListener("submit", function(e){

        // Full Name

        if(fullName.value.trim() === "")
        {
            alert("Please enter the employee's full name.");
            fullName.focus();
            e.preventDefault();
            return;
        }

        // Gender

        if(gender.value === "")
        {
            alert("Please select the employee's gender.");
            gender.focus();
            e.preventDefault();
            return;
        }

        // Email Validation

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

        // Salary Validation

        if(salary.value !== "" && parseFloat(salary.value) < 0)
        {
            alert("Salary cannot be negative.");
            salary.focus();
            e.preventDefault();
            return;
        }

        // Phone Validation (optional)

        if(phone.value !== "" && phone.value.length < 10)
        {
            alert("Please enter a valid phone number.");
            phone.focus();
            e.preventDefault();
            return;
        }

        // Department

        if(department.value.trim() === "")
        {
            alert("Please enter the employee's department.");
            department.focus();
            e.preventDefault();
            return;
        }

        // Position

        if(position.value.trim() === "")
        {
            alert("Please enter the employee's position.");
            position.focus();
            e.preventDefault();
            return;
        }

    });

});

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>