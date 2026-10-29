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
| CHECK RECORD EXISTS
|--------------------------------------------------------------------------
*/

$check = mysqli_query($conn,"
SELECT hr.id,
       hr.disease,
       c.tag_number
FROM health_records hr
INNER JOIN cows c
ON hr.cow_id = c.id
WHERE hr.id='$id'
");

if(mysqli_num_rows($check)==0)
{
    header("Location: index.php?error=notfound");
    exit();
}

$record = mysqli_fetch_assoc($check);

/*
|--------------------------------------------------------------------------
| DELETE RECORD
|--------------------------------------------------------------------------
*/

$delete = mysqli_query($conn,"
DELETE FROM health_records
WHERE id='$id'
");

if($delete)
{

    /*
    |--------------------------------------------------------------------------
    | RECORD ACTIVITY
    |--------------------------------------------------------------------------
    */

    $activity = "Deleted health record for Cow {$record['tag_number']} ({$record['disease']})";

    mysqli_query($conn,"
    INSERT INTO activities(activity)
    VALUES('$activity')
    ");

    header("Location: index.php?deleted=1");
    exit();

}
else
{

    header("Location: index.php?error=1");
    exit();

}

?>