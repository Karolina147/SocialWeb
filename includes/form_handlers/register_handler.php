<?php

//Zmienne zabezpieczające przed błędami

$firstName =""; 
$lastName = ""; 
$email = "";
$email2 = "";
$password = "";
$password2 = "";
$date = ""; // data rejestracji
$error_array = array(); // tablica zapisująca informacje o błędach


if (isset($_POST['register_button'])){
    

    // PRZYPISANIE WARTOŚCI Z FORMULARZA DO ZMIENNYCH

    //Imię
    $firstName = strip_tags($_POST['register_firstName']); // //strip_tags - string
    $firstName = str_replace(' ', '', $firstName); // usuwamy spacje- zamieniamy ' ' na ''(nic) w nazwie
    $firstName = ucfirst(strtolower($firstName)); // pierwsza litera wielka - pozostałe małe
    $_SESSION['register_firstName'] = $firstName; // zapisanie imienia - w zmiennej sesji


    //nazwisko
    $lastName = strip_tags($_POST['register_lastName']);
    $lastName = str_replace(' ', '', $lastName);
    $lastName = ucfirst(strtolower($lastName));
    $_SESSION['register_lastName'] = $lastName;// zapisanie nazwiska - w zmiennej sesji

    
    //Email
    $email = strip_tags($_POST['register_email']);
    $email = str_replace(' ', '', $email);
    $email = ucfirst(strtolower($email));
    $_SESSION['register_email'] = $email; //zapisanie adresu email w zmiennej sesji


    //Email2
    $email2 = strip_tags($_POST['register_email2']);
    $email2 = str_replace(' ', '', $email2);
    $email2 = ucfirst(strtolower($email2));
    $_SESSION['register_email2'] = $email; //zapisanie powtórzonego adresu email w zmiennej sesji

    //Hasło
    $password = strip_tags($_POST['register_password']);
    $password2 = strip_tags($_POST['register_password2']);

    //data

    $date = date("Y-m-d"); // data bieżąca

    // SPRAWDZENIE CZY EMAIL JUŻ ISTNIEJE   

    $email_check = mysqli_query($connect, "SELECT email FROM users WHERE  email= '$email'");

    //Zlicz liczbę zwróconych wierszy

    $number_rows = mysqli_num_rows($email_check);

    if($number_rows > 0) {
        array_push($error_array, "Email is already in use<br>");
    }


   //sprawdzenie maila

   if($email==$email2) {
       // sprawdzenie formatu maila
       if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    
       }
       else {
        array_push($error_array, "Invalid email format<br>");
       }
   }
   else {
    array_push($error_array, "Emails do not match<br>");
   }
   //  sprawdzenie pozostałych pól - zgodnie z wymaganiami wprowadzonymi w bazie danych
   if(strlen($firstName) > 25 || strlen($firstName) < 2) {
    array_push($error_array, "Your first name name must be between 2 and 25 characters<br>");
   }

   if(strlen($lastName) > 25 || strlen($lastName) < 2) {
    array_push($error_array, "Your last name name must be between 2 and 25 characters<br>");
    }
    if($password != $password2) {
        array_push($error_array, "Passwords do not match<br>");
    }
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)) { // sprawdzenie czy hasło zawiera tlyko duze/małe litery A-Z i cyfry
            array_push($error_array, "Your password can only contain english letters or numbers<br>");
        }
    }

    if(strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 and 30 characters<br>");
    }

    if(empty($error_array)) {
        $password = md5($password); // zaszyfrowanie hasła przed wysłaniem do bazy danych

        // tworzenie nazwy używkonika (małymi literami) przez konkatenację imienia i nazwiska:
        $username = strtolower($firstName . "_" . $lastName); 
        $check_username_query = mysqli_query($connect, "SELECT username FROM users WHERE username='$username'"); // sprawdzenie nazwy użytkownika w bazie

        $i = 0;
        // jeśli nazwa użytkownika istnieje - dodaj numer
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($connect, "SELECT username FROM users WHERE username='$username'");
        }

        // Przypisanie zdjęcia
        $random = rand(1,6);
        if($random == 1)
        $profile_pic = "assets/images/profile_pics/default/head_wet_asphalt.png";
        else if($random == 2)
        $profile_pic = "assets/images/profile_pics/default/head_turqoise.png";
        else if($random == 3)
        $profile_pic = "assets/images/profile_pics/default/head_sun_flower.png";
        else if($random == 4)
        $profile_pic = "assets/images/profile_pics/default/head_sun_flower.png";
        else if($random == 5)
        $profile_pic = "assets/images/profile_pics/default/head_belize_hole.png";
        else if($random == 6)
        $profile_pic = "assets/images/profile_pics/default/head_alizarin.png";


        //ZAPIS W BAZIE
        $query = mysqli_query($connect, "INSERT INTO users VALUE ('','$firstName', '$lastName', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',' )"); 
        // pierwsze '' - bo id nadane automatycznie, pozniej 0 - liczba postów, 0 - liczba lajków, no - konto nie -zamknięte, ',' - tablica przyjaciół;

        array_push($error_array, "<span style='color:blue'> You are all set. Go ahead and login</span><br>");

        // CZYSZCZENIE DANYCH SESJI
        $_SESSION['register_firstName'] = '';
        $_SESSION['register_lastName'] = '';
        $_SESSION['register_email'] = '';
        $_SESSION['register_email2'] = '';   
    }    
}

?>