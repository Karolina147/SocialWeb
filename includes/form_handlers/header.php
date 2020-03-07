<?php

    require 'config/config.php';

    //  jeśli zmienna sesji jest ustanowiona, przypisz ją do zmiennej

    if(isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
    }
    else {
        header("Location: register.php"); // w przeciwnym wypadku przekieruje do strony rejestracji
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wonderland</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
