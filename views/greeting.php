<div  class = "d-flex flex-wrap-reverse flex-row-reverse justify-content-around">
    <img src = "./img/greeting.jpg" style = "height: auto; min-width: 40%; margin-top: 20px; border-radius: 0px 0px 20px 0px;" class = "greetingFoto">
    <div id = "register_user" class = "d-flex flex-column justify-content-around p-5 gradient register" class = "register">  
        <?php
            if ( isset( $_POST['register'] ) )
            {
              $checkLoginAndEmail = mysqli_query($link, "SELECT login, email FROM users");
            
              $errors = [];

              $regExpLogin = '/^\w{4,15}$/';
              $regExpPass  = '/^[a-zA-Z0-9]{7,}$/';
              $regExpEmail = '/^[a-zA-Z0-9-_\-.]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/';
              $regInf      = '/([a-zA-Z0-9]{7,})?/';
              $login       = $_POST['username'];
              $email       = $_POST['email'];
              $password    = $_POST['password'];
              $rpt_pass    = $_POST['repeat_password'];
              $information = $_POST['information'];

              while ( $rowLoginAndEmail = mysqli_fetch_assoc($checkLoginAndEmail) )
              {

                if ($rowLoginAndEmail['login'] == $login)
                {
                  $errors[] = "Користувач з таким ім'ям вже існує";
                }

                if ($rowLoginAndEmail['email'] == $email)
                {
                  $errors[] = "Користувач з такою поштою вже існує";
                }
              }
			
              if ( !preg_match($regInf, $information) )
              {
                $errors[] = "Введіть правильну інформацію";
              }
              else
              {
                $information = strip_tags($information);
              }

              if ( !preg_match($regExpLogin, $login) )
              {
                $errors[] = "Логін повинен містити символи і довжину не менше 4 символи та не більше 15 символів";
              }

              if ( !preg_match($regExpEmail, $email) )
              {
                $errors[] = "Пошта введена не вірно";
              }

              if ( !preg_match($regExpPass, $password) )
              {
                $errors[] = "Пароль повинен містити символи і принаймні довжину 7";
              }

              if ($password != $rpt_pass)
              {
                $errors[] = "Пароль не відповідає повторюваному паролю!";
              }

              if ( empty($errors) )
              {
                $password = password_hash ($password,PASSWORD_BCRYPT);

                $addUser = "INSERT INTO users(`login`,`password`,`email`,`information`) VALUES ('$login','$password','$email','$information')";
                $result  = mysqli_query($link, $addUser);
                mysqli_close($link);

                if($result)
                {
                  header("Location: index.php?action=intropage");
                }
                else
                {
                  echo "<span style = 'color: black ; font-size: 0.9vw;'>Помилка бази даних</span>";
                }
              }
              else
              {
                $msg = array_pop($errors);
                echo "<span style = 'color: black ; font-size: 0.9vw;'>$msg</span>";
              }
	        }	
	    ?>
	  <form class = "d-flex flex-column align-items-center" method = "POST" name = "registerform">
           <span class = "no_hightlight">Електронна адреса</span>
           <input class = "size_text" name = "email" size = "32"  type = "email" value = " <?= !empty($_POST)?$email:"" ?>">
           <span class = "no_hightlight">Логін</span>
           <input class = "size_text" name = "username" size = "32" type = "text" value = "<?= !empty($_POST)?$login:"" ?>">
           <span class = "no_hightlight">Про себе</span>
           <input class = "size_text_inf" name = "information" size = "32" type = "text" size = "20" maxlength = "50" value = "<?= !empty($_POST)?$information:"" ?>">
           <span class = "no_hightlight">Пароль</span>
           <input class = "size_text" name = "password" size = "32" type = "password" value = "">
           <span class = "no_hightlight">Підтвердіть пароль</span>
           <input class = "size_text" name = "repeat_password" size = "32" type = "password" value = "">
           <input id = "button_register" id = "register" name = "register" type = "submit" value = "Зареєструватись">
      </form>
    </div>
</div>			