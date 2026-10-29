<?php

$pageTitle = "View Employee";
$activePage = "employees";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| VALIDATE EMPLOYEE ID
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| FETCH EMPLOYEE
|--------------------------------------------------------------------------
*/

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

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-person-badge-fill"></i>

Employee Details

</h2>

<p>

View complete employee information.

</p>

</div>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-person-vcard-fill"></i>

Employee Profile

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-6 mb-3">

<strong>Employee Number</strong>

<p><?= htmlspecialchars($employee['employee_number']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Full Name</strong>

<p><?= htmlspecialchars($employee['full_name']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Gender</strong>

<p><?= htmlspecialchars($employee['gender']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Phone Number</strong>

<p><?= htmlspecialchars($employee['phone']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Email Address</strong>

<p><?= htmlspecialchars($employee['email']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>National ID</strong>

<p><?= htmlspecialchars($employee['national_id']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Department</strong>

<p><?= htmlspecialchars($employee['department']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Position</strong>

<p><?= htmlspecialchars($employee['position']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Employment Type</strong>

<p><?= htmlspecialchars($employee['employment_type']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Monthly Salary</strong>

<p>KSh <?= number_format($employee['salary'],2); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Hire Date</strong>

<p>

<?= date("d M Y", strtotime($employee['hire_date'])); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<strong>Status</strong>

<?php

switch($employee['status'])
{
    case 'Active':
        $badge="success";
        break;

    case 'On Leave':
        $badge="warning";
        break;

    case 'Suspended':
        $badge="danger";
        break;

    default:
        $badge="secondary";
}

?>

<p>

<span class="badge bg-<?= $badge; ?>">

<?= htmlspecialchars($employee['status']); ?>

</span>

</p>

</div>

<div class="col-12 mb-3">

<strong>Address</strong>

<div class="border rounded p-3 bg-light">

<?= !empty($employee['address'])
    ? nl2br(htmlspecialchars($employee['address']))
    : '<span class="text-muted">No address provided.</span>'; ?>

</div>

</div>

</div>

<hr>

<div class="text-end">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<a href="edit.php?id=<?= $employee['id']; ?>" class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit

</a>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>