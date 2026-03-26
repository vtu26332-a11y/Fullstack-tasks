<?php
include "db.php";

$message="";

if(isset($_POST['register']))
{

$username=$_POST['username'];
$email=$_POST['email'];
$password=password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql="INSERT INTO users(username,email,password)
VALUES('$username','$email','$password')";

if(mysqli_query($conn,$sql))
{
header("Location: login.php");
exit();
}
else
{
$message="Error: ".mysqli_error($conn);
}

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Create Account</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#eef1f6;
height:100vh;
display:flex;
justify-content:center;
align-items:center;
font-family:Arial;
margin:0;
}

.container-box{
width:900px;
height:520px;
display:flex;
background:white;
border-radius:20px;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
overflow:hidden;
}

.left-panel{
width:50%;
background:linear-gradient(135deg,#5f6fd8,#6c48c5);
color:white;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
text-align:center;
padding:40px;
}

.left-panel h1{
font-weight:bold;
margin-bottom:15px;
}

.left-panel a{
margin-top:20px;
border:1px solid white;
padding:10px 25px;
border-radius:25px;
color:white;
text-decoration:none;
}

.right-panel{
width:50%;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
}

.form-box{
width:80%;
}

.form-box input{
width:100%;
padding:10px;
margin:10px 0;
border-radius:6px;
border:1px solid #ccc;
}

.form-box button{
width:100%;
padding:12px;
background:#6c48c5;
border:none;
color:white;
border-radius:25px;
margin-top:10px;
}

</style>

</head>

<body>

<div class="container-box">

<div class="left-panel">

<h1>Welcome!</h1>

<p>Enter your personal details to use all site features</p>

<a href="login.php">SIGN IN</a>

</div>

<div class="right-panel">

<div class="form-box">

<h2 class="text-center mb-4">Create Account</h2>

<?php
if($message!="")
{
echo "<div class='alert alert-danger'>$message</div>";
}
?>

<form method="POST">

<input type="text" name="username" placeholder="Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="register">SIGN UP</button>

</form>

</div>

</div>

</div>

</body>

</html>