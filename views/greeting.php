<div class = "d-flex flex-row-reverse justify-content-between flex-wrap">
    <div class = "border rounded form-group col-sm-6 h-75" style = "margin-top: 20px;">
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
	  <form  method = "POST" name = "registerform">
           <label class = "no_hightlight" for = "emailText">Електронна адреса</label>
           <input class = "form-control" id = "emailText" name = "email" size = "32"  type = "email" value = " <?= !empty($_POST)?$email:"" ?>">
           <label class = "no_hightlight" for = "loginText">Логін</label>
           <input class = "form-control" id = "loginText" name = "username" size = "32" type = "text" value = "<?= !empty($_POST)?$login:"" ?>">
           <label class = "no_hightlight" for = "userInf">Про себе</label>
           <textarea name = "information" id = "userInf" class = "md-textarea form-control" rows = "5" value = "<?= !empty($_POST)?$information:"" ?>"></textarea>
           <label class = "no_hightlight" for = "passwordText">Пароль</label>
           <input class = "form-control" id = "passwordText" name = "password" size = "32" type = "password" value = "">
           <label class = "no_hightlight" for = "repeatPasswordText">Підтвердіть пароль</label>
           <input class = "form-control" id = "repeatPasswordText" name = "repeat_password" size = "32" type = "password" value = "">
           <button class = "btn btn-default" name = "register" type = "submit">Зареєструватись</button>
      </form>
    </div>
    <img src = "./img/greeting.png"  class = "greetingFoto col-sm-6 h-75" style = "object-fit: contain; margin-top: 20px;">
</div>
	
