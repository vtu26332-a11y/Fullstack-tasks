<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="index.php">TrackWise</a>

<div class="collapse navbar-collapse">

<ul class="navbar-nav ms-auto">

<?php
if(isset($_SESSION['user_id']))
{
?>

<li class="nav-item">
<a class="nav-link" href="index.php">Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="add_expense.php">Add Expense</a>
</li>

<li class="nav-item">
<a class="nav-link" href="view_expenses.php">View Expenses</a>
</li>

<li class="nav-item">
<a class="nav-link" href="logout.php">Logout</a>
</li>

<?php
}
else
{
?>

<li class="nav-item">
<a class="nav-link" href="login.php">Login</a>
</li>

<li class="nav-item">
<a class="nav-link" href="register.php">Register</a>
</li>

<?php
}
?>

</ul>

</div>

</div>

</nav>