<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php'; // register będzie miał już wszytskie informacje z config
require 'includes/form_handlers/login_handler.php'; // login musi byc po register, bo korzysta z danych w register_handler

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- popdięcie pliku css -->
    <link rel="stylesheet" type="text/css" href="assets/css/register_style.css"> 


</head>
<body>
    <div class="wrapper"> 
        <!-- LOGOWANIE -->
        <form action="register.php" method ="POST"> 
        <!-- wysyłanie danych do logowania metodą POST -->
            <input type="email" name = "log_email" placeholder="Email address" value="<?php 
            if(isset($_SESSION['log_email'])) {
                echo $_SESSION['log_email'];
            } ?>" required> 
            <!-- required - pole wymagane - bez niego nie uda się wysłać formularza -->
            <br>
            <input type="password" name = "log_password" placeholder="Password">
            <br>
            <input type="submit" name = "login_button" placeholder="Login">
            <br>
            <!-- wyświetlenie wiadomości o błędzie -->
            <?php
            if(in_array("Email or password was incorrect<br>", $error_array)) echo "Email or password was incorrect<br>"
            ?>

        </form>
        <!-- REJESTRACJA -->
        <form action="register.php" method="POST">
            <!-- uzupełnione dane zostaną wysłane na podany adres register.php -->
            <input type="text" name="register_firstName" placeholder="First name" value="<?php 
            if(isset($_SESSION['register_firstName'])) {
                echo $_SESSION['register_firstName'];
            } ?>" required> 
            <!-- dzięki wstawce php - do pola zostanie przekazana wartość wprowadzona podczas sesji trwającej - imię -->
            <br>

            <?php 
                if(in_array("Your first name name must be between 2 and 25 characters<br>",$error_array)) echo "Your first name name must be between 2 and 25 characters<br>"; // wyświetlanie błedu z tablicy błędów
            ?>

            <input type="text" name="register_lastName" placeholder="Last name" value="<?php 
            if(isset($_SESSION['register_lastName'])) {
                echo $_SESSION['register_lastName'];
            } ?>" required>
            <!-- dzięki wstawce php - do pola zostanie przekazana wartość wprowadzona podczas sesji trwającej - nazwisko -->
            <br>    

            <?php 
                if(in_array("Your last name name must be between 2 and 25 characters<br>",$error_array)) echo "Your last name name must be between 2 and 25 characters<br>"; // wyświetlanie błedu z tablicy błędów
            ?> 

            <input type="email" name="register_email" placeholder="Email" value="<?php 
            if(isset($_SESSION['register_email'])) {
                echo $_SESSION['register_email'];
            } ?>" required>
            <!-- dzięki wstawce php - do pola zostanie przekazana wartość wprowadzona podczas sesji trwającej - email -->
            <br>       
            <input type="email" name="register_email2" placeholder="Confirm Email" value="<?php 
            if(isset($_SESSION['register_email2'])) {
                echo $_SESSION['register_email2'];
            } ?>" required>
            <br>

            <?php 
                if(in_array("Invalid email format<br>",$error_array)) echo "Invalid email format<br>"; 
                else if(in_array("Email is already in use<br>",$error_array)) echo "Email is already in use<br>"; // wyświetlanie błedu z tablicy błędów
                else if(in_array("Emails do not match<br>",$error_array)) echo "Emails do not match<br>"; // wyświetlanie błedu z tablicy błędów
            ?>

            <input type="password" name="register_password" placeholder="Password" required>
            <br>
            <input type="password" name="register_password2" placeholder="Confirm Password" required>
            <br>

            <?php 
                if(in_array("Passwords do not match<br",$error_array)) echo "Passwords do not match<br"; 
                else if(in_array("Your password can only contain english letters or numbers<br>",$error_array)) echo "Your password can only contain english letters or numbers<br>"; // wyświetlanie błedu z tablicy błędów
                else if(in_array("Your password must be between 6 and 30 characters<br>",$error_array)) echo "Your password must be between 6 and 30 characters<br>"; // wyświetlanie błedu z tablicy błędów
            ?>

            <input type="submit" name="register_button" value="Register" required>
            <br>

            <?php 
                if(in_array("<span style='color:blue'> You are all set. Go ahead and login</span><br>",$error_array)) echo "<span style='color:blue'> You are all set. Go ahead and login</span><br>"; // wyświetlanie napisu o poprawnym zalogowaniu
            ?>

        </form>
    </div>
</body>
</html>