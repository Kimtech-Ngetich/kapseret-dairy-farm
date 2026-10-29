<?php

$pageTitle = "Employees";
$activePage = "employees";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

$search = "";

$sql = "

SELECT *

FROM employees

";

if(isset($_GET['search']) && !empty(trim($_GET['search'])))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $sql .= "

    WHERE

    employee_number LIKE '%$search%'

    OR

    full_name LIKE '%$search%'

    OR

    department LIKE '%$search%'

    OR

    position LIKE '%$search%'

    ";
}

$sql .= " ORDER BY full_name ASC";

$query = mysqli_query($conn,$sql);

/*
|--------------------------------------------------------------------------
| STATISTICS
|--------------------------------------------------------------------------
*/

$totalEmployees = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM employees
"))['total'];

$activeEmployees = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM employees
WHERE status='Active'
"))['total'];

$monthlySalary = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(salary),0) total
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

<i class="bi bi-people-fill"></i>

Employee Management

</h2>

<p>

Manage farm employees, positions, salaries and employment records.

</p>

</div>

<?php if(isset($_GET['added'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Employee added successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Employee updated successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Employee deleted successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show">

<i class="bi bi-exclamation-triangle-fill"></i>

Unable to complete the requested operation.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<!-- Statistics -->

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-green">

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

<h2>KSh <?= number_format($monthlySalary,2) ?></h2>

<i class="bi bi-cash-stack"></i>

</div>

</div>

</div>

<!-- Search & Actions -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-body">

<div class="row align-items-center">

<div class="col-md-8">

<form method="GET">

<div class="input-group">

<span class="input-group-text bg-success text-white">

<i class="bi bi-search"></i>

</span>

<input
type="text"
name="search"
class="form-control"
placeholder="Search by Employee Number, Name, Department or Position..."
value="<?= htmlspecialchars($search) ?>">

<button class="btn btn-success">

Search

</button>

</div>

</form>

</div>

<div class="col-md-4 text-end">

<a href="add.php" class="btn btn-success">

<i class="bi bi-person-plus-fill"></i>

Add Employee

</a>

</div>

</div>

</div>

</div>
<!-- Employees Table -->

<div class="dashboard-panel">

    <div class="dashboard-panel-header d-flex justify-content-between align-items-center">

        <h5>

            <i class="bi bi-people-fill"></i>

            Employees

        </h5>

        <span class="badge bg-success">

            <?= $totalEmployees ?> Employees

        </span>

    </div>

    <div class="dashboard-panel-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

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

                        <th width="170">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                if(mysqli_num_rows($query) > 0):

                    $no = 1;

                    while($row = mysqli_fetch_assoc($query)):

                ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>

                        <strong>

                            <?= htmlspecialchars($row['employee_number']); ?>

                        </strong>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['full_name']); ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['department']); ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['position']); ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['employment_type']); ?>

                    </td>

                    <td>

                        KSh <?= number_format($row['salary'],2); ?>

                    </td>

                    <td>

                        <?php

                        switch($row['status'])
                        {
                            case 'Active':
                                $badge = "success";
                                break;

                            case 'On Leave':
                                $badge = "warning";
                                break;

                            case 'Suspended':
                                $badge = "danger";
                                break;

                            default:
                                $badge = "secondary";
                        }

                        ?>

                        <span class="badge bg-<?= $badge; ?>">

                            <?= htmlspecialchars($row['status']); ?>

                        </span>

                    </td>

                    <td>

                        <a
                        href="view.php?id=<?= $row['id']; ?>"
                        class="btn btn-sm btn-info">

                            <i class="bi bi-eye"></i>

                        </a>

                        <a
                        href="edit.php?id=<?= $row['id']; ?>"
                        class="btn btn-sm btn-warning">

                            <i class="bi bi-pencil-square"></i>

                        </a>

                        <a
                        href="delete.php?id=<?= $row['id']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this employee?')">

                            <i class="bi bi-trash"></i>

                        </a>

                    </td>

                </tr>

                <?php

                    endwhile;

                else:

                ?>

                <tr>

                    <td colspan="9" class="text-center py-5">

                        <i class="bi bi-people fs-1 text-success"></i>

                        <h5 class="mt-3">

                            No Employees Found

                        </h5>

                        <p class="text-muted">

                            Click <strong>Add Employee</strong> to register your first employee.

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

<?php include "../includes/footer.php"; ?>

</body>

</html>