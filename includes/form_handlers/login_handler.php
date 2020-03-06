<?php

if(isset($_POST['login_button'])) { // po kliknięciu guzika login
    $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); // sprawdzenie czy email występuje i czy jest w odpowiednim formacie
    $_SESSION['log_email'] = $email; // zapisanie adresu email w zmiennej sesji
    $password = md5($_POST['log_password']); // przypisanie hasła po zakodowaniu

    $check_database_query =  mysqli_query($connect, "SELECT * FROM users WHERE email='$email' AND password='$password'");  // sprawdzenie maila i hasła z bazą danych

    $check_login_query = mysqli_num_rows($check_database_query); 

    if ($check_login_query == 1) { // jeśli znajdzie login (istnieje)
        $row = mysqli_fetch_array($check_database_query); // użyciedanych zwróconych z zapytania
        $username = $row['username'];

        // zamknięte konto - i ponowne jego otwarcie
        $user_closed_query = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
        if(mysqli_num_rows($user_closed_query) == 1 ){ // jeśli konto jest zamknięte
            $reopen_account = mysqli_query($connect, "UPDATE users SET user_closed='no' WHERE email='$email'"); // aktywacja konta zamkniętego
        }

        $_SESSION['username'] = $username; // utworzenie nowej zmiennej sesji i przypisanie do wartości username
        header("Location: index.php"); // po zalogowaniu przekierowanie do strony index
        exit();
    }
    else {
        array_push($error_array, "Email or password was incorrect<br>"); // zapisanie błędu o nieprawidłowym logowaniu w tablicy

    }
}


?>