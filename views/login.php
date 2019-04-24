<div class = "d-flex flex-row-reverse justify-content-around align-items-center flex-wrap">
    <div class = "border rounded form-group col-sm-4 d-flex flex-column justify-content-center h-50" style = "margin-top: 20px;">
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
        <form name = "loginform" action = "" method = "POST">
            <label for = "loginText" class = "no_hightlight">Логін</label>
            <input type = "text" class = "form-control" size = "40" name = "username" id = "loginText" class = "size_text">
            <label for = "passwordText" class = "no_hightlight">Пароль</label>
            <input type = "password" class = "form-control" name = "password" id = "passwordText" size = "40" class = "size_text">
            <button class = "btn btn-light" type = "submit" name = "login">Вхід</button>
        </form>			
    </div>
    <img src = "./img/login.png"  class = "greetingFoto col-sm-6 h-75" style = "object-fit: contain;">
</div>