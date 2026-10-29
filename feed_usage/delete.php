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
| FETCH USAGE RECORD
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT *
FROM feed_usage
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location:index.php?error=1");
    exit;
}

$usage = mysqli_fetch_assoc($query);

mysqli_begin_transaction($conn);

try
{
    /*
    |--------------------------------------------------------------------------
    | GET FEED RECORD
    |--------------------------------------------------------------------------
    */

    $feedQuery = mysqli_query($conn,"
    SELECT *
    FROM feed_inventory
    WHERE id='{$usage['feed_id']}'
    ");

    if(mysqli_num_rows($feedQuery)==0)
    {
        throw new Exception("Feed record not found.");
    }

    $feed = mysqli_fetch_assoc($feedQuery);

    /*
    |--------------------------------------------------------------------------
    | RESTORE STOCK
    |--------------------------------------------------------------------------
    */

    $newQuantity = $feed['quantity'] + $usage['quantity'];

    if($newQuantity <= 0)
    {
        $status = "Out of Stock";
    }
    elseif($newQuantity <= $feed['reorder_level'])
    {
        $status = "Low Stock";
    }
    else
    {
        $status = "Available";
    }

    mysqli_query($conn,"
    UPDATE feed_inventory
    SET
        quantity='$newQuantity',
        status='$status'
    WHERE id='{$feed['id']}'
    ");

    /*
    |--------------------------------------------------------------------------
    | DELETE USAGE RECORD
    |--------------------------------------------------------------------------
    */

    mysqli_query($conn,"
    DELETE FROM feed_usage
    WHERE id='$id'
    ");

    mysqli_commit($conn);

    header("Location:index.php?deleted=1");
    exit;
}
catch(Exception $e)
{
    mysqli_rollback($conn);

    header("Location:index.php?error=1");
    exit;
}
?>