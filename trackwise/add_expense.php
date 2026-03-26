<?php
session_start();
if(!isset($_SESSION['user_id']))
{
header("Location: login.php");
exit();
}

include "db.php";

$message="";

if(isset($_POST['submit']))
{

$user_id=$_SESSION['user_id'];
$amount=$_POST['amount'];
$category=$_POST['category'];
$expense_data=$_POST['expense_data'];

$sql="INSERT INTO expenses(user_id,amount,category,expense_data)
VALUES('$user_id','$amount','$category','$expense_data')";

if(mysqli_query($conn,$sql))
{
$message="Expense Added Successfully!";
}
else
{
$message="Error: ".mysqli_error($conn);
}

}
?>

<?php include "navbar.php"; ?>

<!DOCTYPE html>
<html>

<head>

<title>Add Expense</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container mt-5">

<div class="card shadow-lg p-4">

<h2 class="mb-4">Add Expense</h2>

<?php
if($message!="")
{
echo "<div class='alert alert-success'>$message</div>";
}
?>

<form method="POST">

<div class="mb-3">

<label class="form-label">Amount</label>

<input type="number" step="0.01" name="amount" class="form-control" required>

</div>

<div class="mb-3">

<label class="form-label">Category</label>

<select name="category" class="form-control" required>

<option value="">Select Category</option>
<option value="Food">Food</option>
<option value="Travel">Travel</option>
<option value="Shopping">Shopping</option>
<option value="Clothes">Clothes</option>
<option value="Bills">Bills</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">Date</label>

<input type="date" name="expense_data" class="form-control" required>

</div>

<button type="submit" name="submit" class="btn btn-primary">

Add Expense

</button>

</form>

</div>

</div>

</body>

</html>