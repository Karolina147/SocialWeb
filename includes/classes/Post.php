<?php
class Post {
	private $user_obj;
	private $connect;

	public function __construct($connect, $user){
		$this->connect = $connect; // do zmiennej connect przypisuje przekazany parametr $connect
		$this->user_obj = new User($connect, $user); // stworzenie obiektu użytkownika w klasie
	}


	public function submitPost($body, $user_to) {
        $body = strip_tags($body); // usuwa tagi html
        $body = mysqli_real_escape_string($this->connect, $body); // zapisze tekst w tabeli nawet jeśli beziez zawierał znaki jak '
        $check_empty = preg_replace('/\s+/', '', $body); // gdy znajdzie pustą przestrzeń, usunie ją

        // umożliwienie wstawiania postów z odstępami pustych linii
        $body = str_replace('\r\n', '\n', $body); // zastąpienie jednego stringa enter = '\r\n - drugim: \n (znak nowej linii), sprawdzamy $body
        $body = nl2br($body); // zastąpienie nowych linii(\n) przez breaki <br>

        if($check_empty != "") { // sprawdzenie czy w poście jest tekst

            // aktualna data i czas
            $date_added = date("Y-m-d H:i:s");
            // dodany przez
            $added_by = $this->user_obj->getUsername();

            // jeśli użytkownijest na swoim profilu odbiorca nie ma odbiorcy

            if($user_to == $added_by) {
				$user_to = "none";
            }

            // dodawanie posta

            // przekazanie danych do bazy - wszytskich wymagany, id - już uzupełnione automatycznie, na końcu: user_closed, deleted, likes - początkowo 0 
            $query = mysqli_query($this->connect, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
            $returned_id = mysqli_insert_id($this->connect); // zwróci id posta  
        }
            // dodawanie powiadomień

            $num_posts = $this->user_obj->getNumPosts(); // zwrócenie liczby postów
			$num_posts++; // zwiększenie o 1
			$update_query = mysqli_query($this->connect, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

    }

    public function loadPostsFriends() {
        $str = ''; // string do zwrócenia //
        $data = mysqli_query($this->connect, "SELECT * FROM posts WHERE deleted='no', ORDER BY id DESC"); // wyświetlenie postów w kolejności malejącej (desc)

        while($row = mysqli_fetch_array($data_query)) {
            // przypisanie danych z bazy do zmiennych
            $id = $row['id'];
            $body = $row['body'];
            $added_by = $row['added_by'];
            $date_time = $row['date_added'];

            //przygotowanie user_to string aby było wyświetlone gdy niezaadresowane do innego użytkownika

            if($row['user_to'] == "none") { // brak uzytkownika - adresowane do piszącego
                $user_to = "";;
            }
            else {
                $user_to_obj = new User($connect, $row['user_to']); // stworzenie nowego obiektu użytkownika
                $user_to_name = $user_to_obj->getFirstAndLastName(); // wypisanie imienia i nazwiska adresata posta
                $user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>"; // stworzenie linka do storny profilowej użytkownika
            }

            // sprawdzenie czy użytkownik piszący ma zamknięte konto

            $added_by_obj = new User($this->connect, $added_by); // obiekt użytkownika tworzacego post
            if($added_by_obj->isClosed()) {
                continue; // czyli przejdź na początek
            }

            $user_details_query = mysqli_query($this->connect, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
			$user_row = mysqli_fetch_array($user_details_query);

        }
    }
		
}

?>

