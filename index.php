<?php
$connect = mysqli_connect("localhost", "root", "", "social");
// połaczenie z bazą danych - localhost, nazwa: root i "" - domyślne hasło

if(mysqli_connect_errno())
// funkcja zwróci błedy jeśli wystapia podczas łączenia z bazą danych
{
    echo 'Failed to connect: ' . mysqli_connect_errno();
}

$query = mysqli_query($connect, "INSERT INTO test VALUES('', 'Zenon')" );
//dodajemy wartości do naszego testu w bazie danych - pierwsza wartość: ''  ID uzupełniony automatycznie w bazie, dodajemy tylko imię 'Zenon'


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media</title>
</head>
<body>
    Hello
</body>
</html>