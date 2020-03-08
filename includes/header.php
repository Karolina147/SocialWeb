<?php

    require 'config/config.php';

    //  jeśli zmienna sesji jest ustanowiona, przypisz ją do zmiennej

    if (isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
        // przypisanie nazwy użytkownika do zmiennej - z bazy
        $user_details_query = mysqli_query($connect, "SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_details_query); // zwróci wszystkie kolumny dla tego użytkownika
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

    <!-- pliki JS / jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>

    <!-- pliki CSS  -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css"> 
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"> 
    <link href='//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

</head>
<body>

    <div class="top_bar">

        <div class="logo">
            <a href="index.php">Wonderland</a>
        </div>

        <nav>
            <!-- link do profilu -->
            <a href="<?php echo $userLoggedIn; ?>">
				<?php echo $user['first_name']; ?>
			</a>

            <a href="index.php">
                <i class="fa fa-envelope fa-lg"></i>
            </a>

            <a href="#">
                <i class="fa fa-home fa-lg"></i>
            </a>
            
            <a href="#">
                <i class="fa fa-bell fa-lg"></i>
            </a> 

            <a href="#">
                <i class="fa fa-people fa-lg"></i>
            </a> 

            <a href="#">
                <i class="fa fa-users fa-lg"></i>
            </a> 

            <a href="#">
                <i class="fa fa-cog fa-lg"></i>
            </a>    

        </nav>      

    </div>

    <!-- div wrapper - do stylowania zdjęcia, zamknięcie diva w index.php -->
    <div class="wrapper">
        

