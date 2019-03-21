<div class = "d-flex flex-column align-items-center ">
	<div id = "redact_user" class = "d-flex flex-column justify-content-around align-items-center gradient p-3">
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
	  
	  <form  class = "d-flex flex-column align-items-center " method = "POST" name = "edit">
	      <span class = "no_hightlight">Електронна адреса</span>
		  <input class = "size_text" id = "email" name = "email" size = "32" type = "email" value = " <?= !empty($_POST)?$email:"" ?>">
		  <span class = "no_hightlight">Логін</span>
		  <input class = "size_text" id = "username" name = "username" size = "32" type = "text" value = "<?= !empty($_POST)?$login:"" ?>">
		  <span class = "no_hightlight">Про себе</span>
		  <input class = "size_text_inf" id = "information" name = "information" size = "32" type = "text" size = "20" maxlength = "50" value = "<?= !empty($_POST)?$information:"" ?>">
		  <span class = "no_hightlight">Старий пароль</span>
		  <input class = "size_text"  name = "password" size = "32" type = "password" value = "">
		  <span class = "no_hightlight">Новий пароль</span>
		  <input class = "size_text"  name = "new_password" size = "32" type = "password" value = "">
		  <span class = "no_hightlight">Підтвердіть пароль</span>
		  <input class = "size_text"  name = "repeat_new_password" size = "32" type = "password" value = "">
		  <input id = "button_register"  name = "edit" type = "submit" value = "Редагувати">
	  </form>
	  <span>Видалити аватар </span>
      <img src = "./img/trash.png" width = "40px" height = "40px" style = "cursor: pointer;" class = "deleteAvatar">
	</div>	
</div>