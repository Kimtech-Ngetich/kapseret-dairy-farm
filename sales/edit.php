<?php

$pageTitle = "Edit Sale";
$activePage = "sales";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

if(!isset($_GET['id']))
{
    header("Location: records.php");
    exit;
}

$id = (int)$_GET['id'];

$message = "";

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE COWS
|--------------------------------------------------------------------------
*/

$cows = mysqli_query($conn,"
SELECT id,name,tag_number
FROM cows
WHERE status='Active'
ORDER BY tag_number ASC
");

/*
|--------------------------------------------------------------------------
| LOAD SALE
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT *
FROM sales
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: records.php");
    exit;
}

$sale = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| UPDATE SALE
|--------------------------------------------------------------------------
*/

if(isset($_POST['update']))
{

$customer_name=mysqli_real_escape_string($conn,$_POST['customer_name']);

$cow_id=(int)$_POST['cow_id'];

$sale_date=$_POST['sale_date'];

$quantity=(float)$_POST['quantity'];

$price=(float)$_POST['price_per_litre'];

$total=$quantity*$price;

$payment=mysqli_real_escape_string($conn,$_POST['payment_method']);

$notes=mysqli_real_escape_string($conn,$_POST['notes']);

$update=mysqli_query($conn,"
UPDATE sales SET

customer_name='$customer_name',
cow_id='$cow_id',
sale_date='$sale_date',
quantity='$quantity',
price_per_litre='$price',
total_amount='$total',
payment_method='$payment',
notes='$notes'

WHERE id='$id'
");

if($update){

mysqli_query($conn,"
INSERT INTO activities(activity)
VALUES('Updated Sale #$id')
");

$message='
<div class="alert alert-success">

Sale updated successfully.

</div>';

$sale=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM sales
WHERE id='$id'
"));

}else{

$message='
<div class="alert alert-danger">

Unable to update sale.

</div>';

}

}

?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-pencil-square"></i>

Edit Sale

</h2>

<p>

Update milk sales information.

</p>

</div>

<?= $message ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-cart-check"></i>

Sales Information

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">Customer Name</label>

<input
type="text"
name="customer_name"
class="form-control"
value="<?= htmlspecialchars($sale['customer_name']) ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Sale Date</label>

<input
type="date"
name="sale_date"
class="form-control"
value="<?= $sale['sale_date'] ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Cow</label>

<select
name="cow_id"
class="form-select"
required>

<?php while($cow=mysqli_fetch_assoc($cows)): ?>

<option
value="<?= $cow['id']; ?>"
<?= ($sale['cow_id']==$cow['id'])?'selected':''; ?>>

<?= $cow['tag_number']." - ".$cow['name']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Quantity (Litres)</label>

<input
type="number"
step="0.01"
min="0"
id="quantity"
name="quantity"
class="form-control"
value="<?= $sale['quantity']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Price Per Litre</label>

<input
type="number"
step="0.01"
min="0"
id="price"
name="price_per_litre"
class="form-control"
value="<?= $sale['price_per_litre']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Total Amount</label>

<input
type="text"
id="total"
class="form-control"
value="<?= number_format($sale['total_amount'],2); ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Payment Method</label>

<select
name="payment_method"
class="form-select"
required>

<option <?= $sale['payment_method']=="Cash"?"selected":""; ?>>Cash</option>

<option <?= $sale['payment_method']=="Mpesa"?"selected":""; ?>>Mpesa</option>

<option <?= $sale['payment_method']=="Bank"?"selected":""; ?>>Bank</option>

</select>

</div>

<div class="col-12 mb-3">

<label class="form-label">Notes</label>

<textarea
name="notes"
rows="4"
class="form-control"><?= htmlspecialchars($sale['notes']); ?></textarea>

</div>

</div>

<div class="mt-4">

<button
type="submit"
name="update"
class="btn btn-success">

<i class="bi bi-check-circle"></i>

Update Sale

</button>

<a
href="view.php?id=<?= $id; ?>"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

</div>

<script>

const quantity=document.getElementById("quantity");

const price=document.getElementById("price");

const total=document.getElementById("total");

function calculateTotal(){

let q=parseFloat(quantity.value)||0;

let p=parseFloat(price.value)||0;

total.value=(q*p).toFixed(2);

}

quantity.addEventListener("input",calculateTotal);

price.addEventListener("input",calculateTotal);

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>