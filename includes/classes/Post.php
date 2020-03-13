<?php
class Post {
	private $user_obj;
	private $connect;

	public function __construct($connect, $user){
		$this->connect = $connect;
		$this->user_obj = new User($connect, $user);
	}

	public function submitPost($body, $user_to) {
		$body = strip_tags($body); //usuwanie tagó html
		$body = mysqli_real_escape_string($this->connect, $body);
		$check_empty = preg_replace('/\s+/', '', $body); // usuwanie wolnych przestrzeni 
      
		if($check_empty != "") {


			//Obecna data i czas
			$date_added = date("Y-m-d H:i:s");
			//Uzyskaj nazwę użytkonika
			$added_by = $this->user_obj->getUsername();

			//Użytkownik na swoim profilu - brak user_to
			if($user_to == $added_by) {
				$user_to = "none";
			}

			//insert post 
			$query = mysqli_query($this->connect, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
			$returned_id = mysqli_insert_id($this->connect);


			// aktualizowanie liczby postów użytkownika
			$num_posts = $this->user_obj->getNumPosts();
			$num_posts++;
			$update_query = mysqli_query($this->connect, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

		}
	}

	public function loadPostsFriends($data, $limit) {

		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;


		$str = ""; // string do zwrócenia
		$data_query = mysqli_query($this->connect, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {


			$num_iterations = 0; // liczba sprawdzonych wyników (niekocznie zapostowanych)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];

				//przygotwanie suer_to do stringa zebeby moglo być zesrte nawet jeśki nie zaadresowane do żuytkownika
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($connect, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//sprawdz użytkownik piszący ma zamknięte konto
				$added_by_obj = new User($this->connect, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				

					if($num_iterations++ < $start)
						continue; 


					//kiedy załaduje 10 postów - break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					$user_details_query = mysqli_query($this->connect, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];


					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); // Czas posta
					$end_date = new DateTime($date_time_now); //Obecny czas
					$interval = $start_date->diff($end_date); //róznica między czasem dodania posta a obecnym czasem 
					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //rok temu
						else 
							$time_message = $interval->y . " years ago"; // 1+ lat temu
					}
					else if ($interval-> m >= 1) {
						if($interval->d == 0) {
							$days = " ago";
						}
						else if($interval->d == 1) { // dni
							$days = $interval->d . " day ago"; 
						}
						else {
							$days = $interval->d . " days ago";
						}


						if($interval->m == 1) { //miesiące
							$time_message = $interval->m . " month". $days;
						}
						else {
							$time_message = $interval->m . " months". $days;
						}

					}
					else if($interval->d >= 1) { // wczoraj
						if($interval->d == 1) {
							$time_message = "Yesterday";
						}
						else {
							$time_message = $interval->d . " days ago";
						}
					}
					else if($interval->h >= 1) {
						if($interval->h == 1) {
							$time_message = $interval->h . " hour ago";
						}
						else {
							$time_message = $interval->h . " hours ago";
						}
					}
					else if($interval->i >= 1) {
						if($interval->i == 1) {
							$time_message = $interval->i . " minute ago";
						}
						else {
							$time_message = $interval->i . " minutes ago";
						}
					}
					else {
						if($interval->s < 30) {
							$time_message = "Just now";
						}
						else {
							$time_message = $interval->s . " seconds ago";
						}
					}

					$str .= "<div class='status_post'>
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
							<hr>";
				

			} //koniec pętli
			
			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
		}

		echo $str;


	}




}

?>