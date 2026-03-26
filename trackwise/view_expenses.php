<?php
session_start();

if(!isset($_SESSION['user_id']))
{
header("Location: login.php");
exit();
}

include "db.php";

$user_id = $_SESSION['user_id'];

$where="WHERE user_id='$user_id'";

if(isset($_GET['category']) && $_GET['category']!="")
{
$category=$_GET['category'];
$where.=" AND category='$category'";
}

if(isset($_GET['start_date']) && $_GET['start_date']!="")
{
$start=$_GET['start_date'];
$where.=" AND expense_data >= '$start'";
}

if(isset($_GET['end_date']) && $_GET['end_date']!="")
{
$end=$_GET['end_date'];
$where.=" AND expense_data <= '$end'";
}

$sql="SELECT * FROM expenses $where ORDER BY expense_data DESC";

$result=mysqli_query($conn,$sql);
?>

<?php include "navbar.php"; ?>

<!DOCTYPE html>
<html>

<head>

<title>View Expenses</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container mt-5">

<div class="card p-4 shadow-lg">

<h2 class="mb-4">My Expenses</h2>

<form method="GET" class="row g-3 mb-4">

<div class="col-md-3">
<label class="form-label">Category</label>
<input type="text" name="category" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label">Start Date</label>
<input type="date" name="start_date" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label">End Date</label>
<input type="date" name="end_date" class="form-control">
</div>

<div class="col-md-3 d-flex align-items-end">
<button class="btn btn-primary w-100">Filter</button>
</div>

</form>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>Amount</th>
<th>Category</th>
<th>Date</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php
while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['amount']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['expense_data']; ?></td>

<td>
<a href="edit_expense.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete_expense.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</body>

</html>