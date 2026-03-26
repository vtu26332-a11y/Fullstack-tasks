<?php
session_start();
include "db.php";

$message="";

if(isset($_POST['login']))
{

$email=$_POST['email'];
$password=$_POST['password'];

$sql="SELECT * FROM users WHERE email='$email'";
$result=mysqli_query($conn,$sql);

$user=mysqli_fetch_assoc($result);

if($user && password_verify($password,$user['password']))
{

$_SESSION['user_id']=$user['id'];
$_SESSION['username']=$user['username'];

header("Location: index.php");
exit();

}
else
{
$message="Invalid Login";
}

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Sign In</title>

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

.right-panel{
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

.right-panel h1{
font-weight:bold;
margin-bottom:15px;
}

.right-panel a{
margin-top:20px;
border:1px solid white;
padding:10px 25px;
border-radius:25px;
color:white;
text-decoration:none;
}

.left-panel{
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

.extra{
display:flex;
justify-content:space-between;
align-items:center;
margin-top:5px;
font-size:14px;
}

/* FIX ADDED */
.extra label{
display:flex;
align-items:center;
gap:5px;
}

.extra input{
width:auto;
margin:0;
}
/* END FIX */

.extra a{
text-decoration:none;
color:#5f6fd8;
}

</style>

</head>

<body>

<div class="container-box">

<div class="left-panel">

<div class="form-box">

<h2 class="text-center mb-4">Sign In</h2>

<?php
if($message!="")
{
echo "<div class='alert alert-danger'>$message</div>";
}
?>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<div class="extra">
<label>
<input type="checkbox"> Remember me
</label>

<a href="#">Forgot Password?</a>
</div>

<button type="submit" name="login">SIGN IN</button>

</form>

</div>

</div>

<div class="right-panel">

<h1>Hello, Friend!</h1>

<p>Welcome back and register with your personal details to use all site features</p>

<a href="register.php">SIGN UP</a>

</div>

</div>

</body>

</html>