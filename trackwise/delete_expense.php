<?php
session_start();

if(!isset($_SESSION['user_id']))
{
header("Location: login.php");
exit();
}

include "db.php";

$id = $_GET['id'];

$sql = "DELETE FROM expenses WHERE id='$id'";

mysqli_query($conn,$sql);

header("Location: view_expenses.php");
exit();
?>