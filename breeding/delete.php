<?php

require_once "../includes/auth_check.php";
require_once "../config/database.php";

if(!isset($_GET['id']))
{
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| CHECK IF RECORD EXISTS
|--------------------------------------------------------------------------
*/

$check = mysqli_query($conn,"
SELECT id
FROM breeding_records
WHERE id='$id'
");

if(mysqli_num_rows($check)==0)
{
    header("Location:index.php?error=notfound");
    exit();
}

/*
|--------------------------------------------------------------------------
| DELETE RECORD
|--------------------------------------------------------------------------
*/

$delete = mysqli_query($conn,"
DELETE FROM breeding_records
WHERE id='$id'
");

if($delete)
{

    mysqli_query($conn,"
    INSERT INTO activities(activity)
    VALUES('Deleted a breeding record')
    ");

    header("Location:index.php?deleted=1");
    exit();

}
else
{

    header("Location:index.php?error=1");
    exit();

}

?>