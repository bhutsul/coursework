<div class = "d-flex flex-row flex-wrap justify-content-center">
    <div class = "d-flex flex-column col-sm-4">
        <div class = "border rounded d-flex flex-column align-items-center h-50" style = "margin-top: 20px;">
           <?php
               if (!empty($_POST)) 
               {
                 $search = $_POST['searchuser'];
                 $search = strip_tags($search);
                 $search = mysqli_real_escape_string($link, $search);

                 if ($search == "") 
                 {
                   echo "<span>Заповніть поле пошуку</span>";
                 }
                 else
                 {
                   $getInfUser = mysqli_query($link, "SELECT `id`, `login` FROM users WHERE login LIKE '$search%' ");
                   $rowInfUser = mysqli_fetch_assoc($getInfUser);

                   $idUser = $rowInfUser['id'];

                   if ( $search == $rowInfUser['login'] )
                   {

                     if ( $_SESSION['login'] == $rowInfUser['login'] ) 
                     {
                       echo '<span><a href = "index.php?action=profile"> '.$rowInfUser['login'].' </a></span>';
                     } 
                     else
                     {
                       echo '<span><a href = "index.php?action=viewuserprofile&idUser='.$idUser.'"> '.$rowInfUser['login'].' </a></span>';
                     }
                   }
               else
               {
                 echo "<span>Такого користувача не існує</span>";
               } 
             }
            }
           ?>
         </div>
        <form name = "searchform" action = "" method = "POST" >
            <label class = "no_hightlight" for = "search">Введіть ім'я користувача</label>
            <input type = "text" size = "40" name = "searchuser" id = "search" class = "form-control">
            <button class = "btn border" type = "submit" name = "searchform" id = "button_register" style = "margin-top: 5px;" >Пошук</button>
        </form>
    </div>
    <img src = "./img/search.png"  class = "greetingFoto col-sm-6 h-75" style = "object-fit: contain; margin-top: 20px;">
</div>
