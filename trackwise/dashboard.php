<?php
include "db.php";

/* Total expense */
$total_expense_query = mysqli_query($conn,"SELECT SUM(amount) as total FROM expenses");
$total_expense = mysqli_fetch_assoc($total_expense_query);

/* Total transactions */
$total_transactions_query = mysqli_query($conn,"SELECT COUNT(*) as count FROM expenses");
$total_transactions = mysqli_fetch_assoc($total_transactions_query);

/* Weekly expense */
$weekly_query = mysqli_query($conn,"
SELECT SUM(amount) as total 
FROM expenses 
WHERE YEARWEEK(expense_data,1)=YEARWEEK(CURDATE(),1)
");
$weekly = mysqli_fetch_assoc($weekly_query);

/* Monthly expense */
$current_month_query = mysqli_query($conn,"
SELECT SUM(amount) as total
FROM expenses
WHERE MONTH(expense_data)=MONTH(CURDATE())
AND YEAR(expense_data)=YEAR(CURDATE())
");
$current_month = mysqli_fetch_assoc($current_month_query);

/* Yearly expense */
$year_query = mysqli_query($conn,"
SELECT SUM(amount) as total
FROM expenses
WHERE YEAR(expense_data)=YEAR(CURDATE())
");
$year_total = mysqli_fetch_assoc($year_query);

/* Year end prediction */
$days_passed = date("z")+1;
$current_year_total = $year_total['total'] ?? 0;

if($days_passed>0)
{
$prediction = ($current_year_total/$days_passed)*365;
}
else
{
$prediction = 0;
}

/* Category chart */
$category_query = mysqli_query($conn,"SELECT category, SUM(amount) as total FROM expenses GROUP BY category");

$categories = [];
$amounts = [];

while($row=mysqli_fetch_assoc($category_query))
{
$categories[] = $row['category'];
$amounts[] = $row['total'];
}

/* Monthly trend chart */
$monthly_query = mysqli_query($conn,"
SELECT DATE_FORMAT(expense_data,'%Y-%m') as month, SUM(amount) as total 
FROM expenses 
GROUP BY month
");

$months = [];
$month_totals = [];

while($row=mysqli_fetch_assoc($monthly_query))
{
$months[] = $row['month'];
$month_totals[] = $row['total'];
}

?>

<?php include "navbar.php"; ?>

<!DOCTYPE html>
<html>

<head>

<title>Expense Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

.chart-container{
width:500px;
height:500px;
margin:auto;
}

.bar-container{
width:800px;
height:400px;
margin:auto;
}

</style>

</head>

<body>

<div class="container mt-5">

<h2 class="text-center">Expense Dashboard</h2>

<div class="row mt-4">

<div class="col-md-3">
<div class="card text-white bg-primary mb-3">
<div class="card-body text-center">
<h6>Total Expenses</h6>
<h4><?php echo number_format($total_expense['total'] ?? 0,2); ?></h4>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-white bg-success mb-3">
<div class="card-body text-center">
<h6>Total Transactions</h6>
<h4><?php echo $total_transactions['count']; ?></h4>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-white bg-warning mb-3">
<div class="card-body text-center">
<h6>This Week</h6>
<h4><?php echo number_format($weekly['total'] ?? 0,2); ?></h4>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-white bg-info mb-3">
<div class="card-body text-center">
<h6>This Month</h6>
<h4><?php echo number_format($current_month['total'] ?? 0,2); ?></h4>
</div>
</div>
</div>

</div>

<div class="row mt-3">

<div class="col-md-6">
<div class="card">
<div class="card-body text-center">
<h6>This Year</h6>
<h3><?php echo number_format($year_total['total'] ?? 0,2); ?></h3>
</div>
</div>
</div>

<div class="col-md-6">
<div class="card">
<div class="card-body text-center">
<h6>Predicted Year End Expense</h6>
<h3><?php echo number_format($prediction,2); ?></h3>
</div>
</div>
</div>

</div>

<h3 class="mt-4 text-center">Expense Analytics by Category</h3>

<div class="chart-container">
<canvas id="expenseChart"></canvas>
</div>

<h3 class="mt-5 text-center">Monthly Expense Trend</h3>

<div class="bar-container">
<canvas id="monthlyChart"></canvas>
</div>

</div>

<script>

const ctx = document.getElementById('expenseChart');

new Chart(ctx,{
type:'pie',
data:{
labels: <?php echo json_encode($categories); ?>,
datasets:[{
data: <?php echo json_encode($amounts); ?>,
backgroundColor:[
'#007bff',
'#28a745',
'#ffc107',
'#dc3545',
'#17a2b8',
'#6f42c1'
]
}]
},
options:{
responsive:true,
maintainAspectRatio:false
}
});

const ctx2 = document.getElementById('monthlyChart');

new Chart(ctx2,{
type:'bar',
data:{
labels: <?php echo json_encode($months); ?>,
datasets:[{
label:'Monthly Expenses',
data: <?php echo json_encode($month_totals); ?>,
backgroundColor:'#2d0d393b'
}]
},
options:{
responsive:true
}
});

</script>

</body>
</html>