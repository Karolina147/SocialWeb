<?php
    include("includes/header.php");
    // session_destroy(); // koniec sesji aby przetestować przekierowanie dla niezalogowanego użytkownika

?>
    <div class="user_details column">
        <!-- dodanie zdjęcia za pomocą linka do bazy danych - zdjęcie -->
        <a href="#"> <img src="<?php echo $user['profile_pic']; ?>"></a> 
        
            <!-- div ze zdjęciem i liczbą postow like'ów -->
            <div class="user_details_left_right">

                <!-- wstawienie imienia i nazwiska do pola ze zdjęciem -->
                <a href="#">
                    <?php 
                        echo $user['first_name'] . " " . $user['last_name'];
                    ?>
                </a> 
                <br>
                <!-- Liczba postów i like'ów -->
                <?php echo "Posts: " . $user['num_posts'] . "<br>"?>
                <?php echo "Likes: " . $user['num_likes']?>

            </div>

    </div>
    <!-- dwie klasy - klasa column- - ta sama co div z obrazkiem -->
    <div class="main_column column"> 

        <!-- formularz do wpisywania postów -->
        <form class="post_form" action="index.php" method="POST">
            <textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
            <input type="submit" name="post" id="post_button" value="Post">
            
            <hr>


        </form>
        
    </div>



    </div>
    <!-- zamknięcie diva wrapper - z header.php -->
</body>
</html>