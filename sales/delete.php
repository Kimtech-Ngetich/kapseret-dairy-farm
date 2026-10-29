<?php

$pageTitle = "Delete Sale";
$activePage = "sales";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

if(!isset($_GET['id']))
{
    header("Location: records.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| CHECK IF SALE EXISTS
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
| DELETE SALE
|--------------------------------------------------------------------------
*/

$delete = mysqli_query($conn,"
DELETE FROM sales
WHERE id='$id'
");

if($delete)
{

    mysqli_query($conn,"
    INSERT INTO activities(activity)
    VALUES('Deleted Sale #$id')
    ");

    header("Location: records.php?deleted=1");
    exit;

}
else
{

    header("Location: records.php?error=1");
    exit;

}

?>