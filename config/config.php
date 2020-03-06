<?php

ob_start(); // buforowanie 
session_start(); // potrzebne aby zapisac dane użyte w ciągu jednej sesji

$timezone = date_default_timezone_set("Europe/Warsaw"); // strefa czasowa
$connect = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno())
// funkcja zwróci błedy jeśli wystapia podczas łączenia z bazą danych

{
    echo 'Failed to connect: ' . mysqli_connect_errno();
}

?>