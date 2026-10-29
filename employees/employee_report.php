<?php

$pageTitle = "Employee Report";
$activePage = "reports";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$query = mysqli_query($conn,"
SELECT *
FROM employees
ORDER BY full_name ASC
");

$totalEmployees = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM employees
"))['total'];

$totalSalary = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(salary),0) total
FROM employees
WHERE status='Active'
"))['total'];

$activeEmployees = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM employees
WHERE status='Active'
"))['total'];

$departments = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(DISTINCT department) total
FROM employees
"))['total'];

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-file-earmark-person-fill"></i>

Employee Report

</h2>

<p>

View, analyze and print employee records and payroll information.

</p>

</div>

<!-- Statistics Cards -->

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-success">

<h5>Total Employees</h5>

<h2><?= $totalEmployees ?></h2>

<i class="bi bi-people-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-primary">

<h5>Active Employees</h5>

<h2><?= $activeEmployees ?></h2>

<i class="bi bi-person-check-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-warning">

<h5>Departments</h5>

<h2><?= $departments ?></h2>

<i class="bi bi-building"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-danger">

<h5>Monthly Payroll</h5>

<h2>KSh <?= number_format($totalSalary,2) ?></h2>

<i class="bi bi-cash-stack"></i>

</div>

</div>

</div>

<!-- Report Panel -->

<div class="dashboard-panel">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-table"></i>

Employee Report

</h5>

<div>

<button
class="btn btn-primary"
onclick="window.print();">

<i class="bi bi-printer-fill"></i>

Print Report

</button>

</div>

</div>

<div class="dashboard-panel-body">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-success">

<tr>

<th>#</th>

<th>Employee No.</th>

<th>Full Name</th>

<th>Department</th>

<th>Position</th>

<th>Employment</th>

<th>Salary</th>

<th>Status</th>

<th>Hire Date</th>

</tr>

</thead>

<tbody>
<?php

if(mysqli_num_rows($query) > 0):

    $no = 1;

    while($row = mysqli_fetch_assoc($query)):

        switch($row['status'])
        {
            case "Active":
                $badge = "success";
                break;

            case "On Leave":
                $badge = "warning";
                break;

            case "Suspended":
                $badge = "danger";
                break;

            default:
                $badge = "secondary";
        }

?>

<tr>

<td><?= $no++; ?></td>

<td>

<strong>

<?= htmlspecialchars($row['employee_number']); ?>

</strong>

</td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= htmlspecialchars($row['department']); ?></td>

<td><?= htmlspecialchars($row['position']); ?></td>

<td><?= htmlspecialchars($row['employment_type']); ?></td>

<td>

KSh <?= number_format($row['salary'],2); ?>

</td>

<td>

<span class="badge bg-<?= $badge; ?>">

<?= htmlspecialchars($row['status']); ?>

</span>

</td>

<td>

<?= !empty($row['hire_date'])
    ? date("d M Y", strtotime($row['hire_date']))
    : "-"; ?>

</td>

</tr>

<?php

    endwhile;

else:

?>

<tr>

<td colspan="9" class="text-center py-5">

<i class="bi bi-people-fill fs-1 text-success"></i>

<h5 class="mt-3">

No Employee Records Available

</h5>

<p class="text-muted">

There are currently no employees registered in the system.

</p>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<style>

@media print{

.sidebar,
.topbar,
.btn,
.page-header p{

display:none !important;

}

.main-content{

margin:0 !important;

padding:0 !important;

}

.dashboard-panel{

border:none !important;

box-shadow:none !important;

}

}

</style>

<?php include "../includes/footer.php"; ?>

</body>

</html>