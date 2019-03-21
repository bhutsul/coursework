<div class = "d-flex flex-column align-items-center">
    <form name = "loginform" id = "login" action = "" method = "POST" class = "d-flex flex-column align-items-center gradient p-5">
         <?php
             if ( !empty($_POST) )
             {
               $login = trim($_POST['username']);
               $password = $_POST['password'];

               if (($login == "") || ($password == "")) 
               {
                 echo "<span>Заповніть всі поля</span>";
               } 
               else 
               {

                 if ($link)
                 {
                   $query = mysqli_query($link,"SELECT * FROM users where login = '$login'");
                   $row = mysqli_fetch_assoc($query);

                   if ($row == false)
                   {
                     echo "<span>Невірний логін або пароль!</span>";
                   } 
                   else 
                   {

                     if ( password_verify($password,$row['password']) ) 
                     {
                       $_SESSION['login'] = $row['login']; 
                       $_SESSION['id']    = $row['id'];
                       header("Location: index.php?action=profile");
                     } 
                     else 
                     {
                       echo "<span>Невірний логін або пароль!</span>";
                     }
                   }
                 }   
               }
             }
         ?>
        <span class = "no_hightlight">Логін</span>
        <input type = "text" size = "40" name = "username" id = "username" class = "size_text">
        <span class = "no_hightlight">Пароль</span>
        <input type = "password" name = "password" id = "password" size = "40" class = "size_text">
        <input type = "submit" name = "login" value = "Вхід" id = "button_register">
        <span>Немає аккаунта?<a href = "index.php?action=greeting">Зареєструйся тут</a>!</span>
    </form>			
</div>