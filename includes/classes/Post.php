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
        $data_query = mysqli_query($this->connect, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC"); // wyświetlenie postów w kolejności malejącej (desc)
          
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
				$first_name = $user_row['first_name'];
				$last_name = $user_row['last_name'];
				$profile_pic = $user_row['profile_pic'];
            
            //PRZEDZIAŁY CZASOWE - datowanie postów

            $date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); // czas dodania posta 
					$end_date = new DateTime($date_time_now); // obecny czas
					$interval = $start_date->diff($end_date); // różnica 
					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //1 rok temu
						else 
							$time_message = $interval->y . " years ago"; //1+ lat temu
					}
					else if ($interval-> m >= 1) { // 1 lub więcej miesięcy
						if($interval->d == 0) { // jeśli zero - dni
							$days = " ago";
						}
						else if($interval->d == 1) { // 1 dzien 
							$days = $interval->d . " day ago";
						}
						else {
							$days = $interval->d . " days ago"; // więcej dni temu
						}


						if($interval->m == 1) { // miesiąc
							$time_message = $interval->m . " month". $days;
						}
						else {
							$time_message = $interval->m . " months". $days; // miesiące
						}

					}
					else if($interval->d >= 1) { // dni
						if($interval->d == 1) {
							$time_message = "Yesterday";
						}
						else {
							$time_message = $interval->d . " days ago";
						}
					}
					else if($interval->h >= 1) { // godziny
						if($interval->h == 1) {
							$time_message = $interval->h . " hour ago";
						}
						else {
							$time_message = $interval->h . " hours ago";
						}
					}
					else if($interval->i >= 1) { // minuty
						if($interval->i == 1) {
							$time_message = $interval->i . " minute ago";
						}
						else {
							$time_message = $interval->i . " minutes ago";
						}
					}
					else {
						if($interval->s < 30) { // sekundy
							$time_message = "Just now";
						}
						else {
							$time_message = $interval->s . " seconds ago";
						}
					}
// 	Powinno być??? $str = $str . -  to samo (+=); // link do imienia i nazwiska z linkiem a href, &nbsp; - twarda spacja - mozna ich wstawić kilka

					$str = "<div class='status_post'>
								<div class='post_profile_pic'>
									<img src='$profile_pic' width='50'>
								</div>

								<div class='posted_by' style='color:#ACACAC;'>
									<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
								</div>
								<div id='post_body'>
									$body
									<br>
								</div>

							</div>
							<hr>"; // dodanie linii poziomej pod postem
					
					 echo $str; // z każdym dodaniem nowego posta będzie dodawało nową treść do poprzedniej
        }
    }
		
}

?>

