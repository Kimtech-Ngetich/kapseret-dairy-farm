<?php

if (!isset($conn)) {
    require_once __DIR__ . "/../config/database.php";
}

$result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");

$systemSettings = mysqli_fetch_assoc($result);