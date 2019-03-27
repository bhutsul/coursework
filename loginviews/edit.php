<div class = "d-flex flex-row-reverse justify-content-between flex-wrap">
    <div class = "border rounded form-group col-sm-6 h-100" style = "margin-top: 20px;">
        <?php 
            $id = $_SESSION['id'];

            if( isset( $_POST['deleteAvatarYes'] ) )
            {
              $getAvatar = mysqli_query($link, "SELECT image_name FROM avatars where user_id = '$id'");
              $rowAvatar = mysqli_fetch_assoc($getAvatar);

              $deleteFile = './avatars/'.$rowAvatar['image_name'].'';
              unlink($deleteFile);

              $queryDeleteAvatar = "DELETE FROM avatars WHERE user_id = '$id'";
              $deleteAvatar = mysqli_query($link, $queryDeleteAvatar) or die("Помилка " . mysqli_error($link));

              echo "<script type='text/javascript'>  window.location='index.php?action=profile'; </script>";
            } 

            if ( isset( $_POST['edit'] ) )
            {
              $checkLoginAndEmail = mysqli_query($link, "SELECT login, email FROM users");

              $errors = [];

              $regExpLogin  = '/^\w{4,15}$/';
              $regExpPass   = '/^[a-zA-Z0-9]{7,}$/';
              $regExpEmail  = '/^[a-zA-Z0-9-_]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/';
              $regInf       = '/([a-zA-Z0-9]{7,})?/';
              $login        = $_POST['username'];
              $email        = $_POST['email'];
              $new_password = $_POST['new_password'];
              $rpt_pass     = $_POST['repeat_new_password'];
              $information  = $_POST['information'];
              $password     = $_POST['password'];
 
              $getPasswordUser = mysqli_query($link,"SELECT password FROM users where id = '$id'");
              $rowPasswordUser = mysqli_fetch_assoc($getPasswordUser);

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
                $errors[] = "Логін повинен містити символи";
              }

              if ( !preg_match($regExpEmail, $email) )
              {
                $errors[] = "Bad email!";
              }

              if ( !preg_match($regExpPass, $new_password) )
              {
                $errors[] = "Пароль повинен містити символи і принаймні довжину 7";
              }

              if ( $new_password != $rpt_pass )
              {
                $errors[] = "Пароль не відповідає повторюваному паролю!";
              }

              if ( !password_verify($password, $rowPasswordUser['password'])) 
              {
                $errors[] = "Невірний старий пароль";
              }
				
              if ( empty($errors) )
              {
                $new_password = password_hash ($new_password,PASSWORD_BCRYPT);

                $updateUserInformation = "UPDATE users SET `login` = '$login',`password` = '$new_password', `email` = '$email', `information` = '$information'where id = '$id'";
                $resultUpdate = mysqli_query($link, $updateUserInformation)or die("Помилка " . mysqli_error($link));;
                mysqli_close($link);	

                if ( $resultUpdate )
                {
                  echo "<script type='text/javascript'>  window.location='index.php?action=profile'; </script>";
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
	  
      <form  method = "POST" name = "edit">
      	  <label class = "no_hightlight" for = "emailText">Електронна адреса</label>
          <input class = "form-control" id = "emailText" name = "email" size = "32" type = "email" value = " <?= !empty($_POST)?$email:"" ?>">
          <label class = "no_hightlight" for = "loginText">Логін</label>
          <input class = "form-control" id = "loginText" name = "username" size = "32" type = "text" value = "<?= !empty($_POST)?$login:"" ?>">
          <label class = "no_hightlight" for = "userInf">Про себе</label>
          <textarea name = "information" id = "userInf" class = "md-textarea form-control" rows = "5" value = "<?= !empty($_POST)?$information:"" ?>"></textarea>
          <label class = "no_hightlight" for = "oldPassword">Старий пароль</label>
          <input class = "form-control" id = "oldPassword" name = "password" size = "32" type = "password" value = "">
          <label class = "no_hightlight" for = "newPassword">Новий пароль</label>
          <input class = "form-control"  id = "newPassword" name = "new_password" size = "32" type = "password" value = "">
          <label class = "no_hightlight" for = "repeatNewPassword">Підтвердіть пароль</label>
          <input class = "form-control"  id = "repeatNewPassword" name = "repeat_new_password" size = "32" type = "password" value = "">
          <button class = "btn border" style = "margin-top: 1%;" name = "edit" type = "submit">Редагувати</button>
      </form>
      <div class = "d-flex flex-column align-items-center justify-content-center">
          <span>Видалити аватар </span>
          <img src = "./img/trash.png" width = "40px" height = "40px" style = "cursor: pointer;" class = "deleteAvatar ">
      </div>
    </div>
    <img src = "./img/edit.png"  class = "greetingFoto col-sm-6 h-100" style = "object-fit: contain;">	
</div>