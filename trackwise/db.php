<?php

$conn = mysqli_connect("localhost","root","","trackwise");

if(!$conn)
{
die("Connection Failed: ".mysqli_connect_error());
}

?>