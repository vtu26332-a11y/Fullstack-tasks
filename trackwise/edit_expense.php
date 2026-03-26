<?php
session_start();

if(!isset($_SESSION['user_id']))
{
header("Location: login.php");
exit();
}

include "db.php";

$id = $_GET['id'];
$result = mysqli_query($conn,"SELECT * FROM expenses WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{

$user_id = $_POST['user_id'];
$amount = $_POST['amount'];
$category = $_POST['category'];
$date = $_POST['expense_data'];

$sql = "UPDATE expenses SET 
user_id='$user_id',
amount='$amount',
category='$category',
expense_data='$date'
WHERE id='$id'";

mysqli_query($conn,$sql);

header("Location: view_expenses.php");

}
?>

<!DOCTYPE html>
<html>

<head>
<title>Edit Expense</title>
</head>

<body>

<h2>Edit Expense</h2>

<form method="POST">

User ID:<br>
<input type="number" name="user_id" value="<?php echo $row['user_id']; ?>"><br><br>

Amount:<br>
<input type="number" step="0.01" name="amount" value="<?php echo $row['amount']; ?>"><br><br>

Category:<br>
<input type="text" name="category" value="<?php echo $row['category']; ?>"><br><br>

Date:<br>
<input type="date" name="expense_data" value="<?php echo $row['expense_data']; ?>"><br><br>

<button type="submit" name="update">Update Expense</button>

</form>

</body>
</html>