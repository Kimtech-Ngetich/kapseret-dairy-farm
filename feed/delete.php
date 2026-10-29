<?php

require_once "../includes/auth_check.php";
require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| VALIDATE ID
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location:index.php?error=1");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| CHECK RECORD EXISTS
|--------------------------------------------------------------------------
*/

$check = mysqli_query($conn,"
SELECT id
FROM feed_inventory
WHERE id='$id'
");

if(mysqli_num_rows($check) == 0)
{
    header("Location:index.php?error=1");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE RECORD
|--------------------------------------------------------------------------
*/

$delete = mysqli_query($conn,"
DELETE FROM feed_inventory
WHERE id='$id'
");

if($delete)
{
    header("Location:index.php?deleted=1");
}
else
{
    header("Location:index.php?error=1");
}

exit;

?>