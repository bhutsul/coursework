<?php
    $idPost    = (int)$_GET['idPost'];
    $loginUser = $_SESSION['login'];
    $idUser    = $_SESSION['id'];
    
    //провірка чи існує пост
    $checkPost = mysqli_query($link, "SELECT image_id FROM imggallery WHERE image_id = '$idPost' AND login_user != '$loginUser'");
    $numRowsId = mysqli_num_rows($checkPost);

    if($numRowsId == 0)
    {
      echo '<div align = "center" style = "margin:15%;"><span style = "color: black ; font-size: 5.0vw;">Ця сторінка не доступна</span></div>';
    }
    else
    {
      //витягуєм ім'я картинки для виводу в профілі
      $getImageNamePost = mysqli_query($link, "SELECT  `image_name` FROM imggallery where image_id = '$idPost'");
      $rowImageNamePost = mysqli_fetch_assoc($getImageNamePost);

      //для провірки чи існує лайк користувача  в сесії
      $queryCheckUserLike = mysqli_query($link,"SELECT  * FROM likepost where id_post = '$idPost' AND login_user = '$loginUser'");
      $rowUserLike        = mysqli_fetch_assoc($queryCheckUserLike);

      if ( isset( $_POST['status'] ) )//добавлееня лайка при кліку
      {
        $status = $_POST['status'];

        if ( empty($rowUserLike['login_user']) || ($rowUserLike['login_user'] != $loginUser) )
        {
          $sql = "INSERT INTO likepost(`login_user`,`id_user`,`id_post`) VALUES ('$loginUser','$idUser','$idPost')";
          $result = mysqli_query($link, $sql) or die("Помилка " . mysqli_error($link));;
        }
        else
        {
          $sql = "DELETE FROM likepost WHERE login_user = '$loginUser' AND id_post = '$idPost'";
          $result = mysqli_query($link, $sql) or die("Помилка " . mysqli_error($link));;
        }
      }
   
      if ( isset( $_POST['addComment'] ) ) //добавлення коментарів
      {
        $errorsComment = [];

        $regComment = '/(([a-zA-Z0-9]{4,255})|([а-яё0-9]{4,255})|([абвгґдеєжзиіїйклмнопрстуфхцчшщьюяы0-9]{4,255}))/';
        $comment    = $_POST['comment'];

        if ( !preg_match($regComment, $comment) )
        {
          $errorsComment[] = "Коментарій повинен містити не менше ніж 4 символа";
        }

        if ( empty($errorsComment) )
        {
          if (isset ($_SESSION['login']) )
          {
            $loginUser = $_SESSION['login'];
            $comment   = $_POST['comment'];
            $date      = date("Y-m-d H:i:s");

            $sql = "INSERT INTO comments(`login_users`,`id_post`,`comment`, `datetime`) VALUES ('$loginUser', '$idPost', '$comment', '$date')";
            $result = mysqli_query($link, $sql);
          }
          else
          {
            echo '<span style = "color: black ; font-size: 0.8vw; margin-left: 30px;">Тільки зареєстровані користувачі можуть залишати коментарі</span>';
          }
        }
        else
        {
          $msg = array_pop($errorsComment);
          echo "<span style = 'color: black ; font-size: 0.9vw; margin-left: 25px;'>$msg</span>";
        }
      }
  echo '<div class = "d-flex flex-wrap justify-content-around">
            <div class = "d-flex flex-column " style = "max-width: 50%">';
         echo  '<img src = "./fotoPost/'.$rowImageNamePost['image_name'].'"  style = "margin-top: 20px; object-fit: contain; height: 450px; max-width: 100%">    
                <div class = "d-flex flex-row">
                    <div onclick = "show(\'none\')" id = "wrap"></div>
                    <div id = "window7" class = "users_like">
                        <img class = "close" onclick = "show(\'none\')" src="./img/close.png" width = "20px" height = "20px">
                        <div class = "d-flex flex-column view_users_like">';
                               //для виводу тих хто лайкнув пост
                               $postLikers = mysqli_query($link,"SELECT `login_user`,`id_user`  FROM likepost where id_post = '$idPost'");

                               while ( $rowPostLikers = mysqli_fetch_assoc($postLikers) ) 
                               {
                                 $listUsers  = $rowPostLikers['login_user'];
                                 $idUserLike = $rowPostLikers['id_user'];

                                 if ( $rowPostLikers['id_user'] == $_SESSION['id'] )
                                 {
                                   echo '<div><a href = "index.php?action=profile" >'.$listUsers.'</a></div>';
                                 }
                                 else
                                 {
                                   echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$idUserLike.'" >'.$listUsers.'</a></div>';
                                 }
                               }

                  echo '</div>
                    </div>';  
                        $numLike =  mysqli_query($link, "SELECT * FROM likepost WHERE id_post = '$idPost'");//для виводу кількості лайків під постом
                        $numRows = mysqli_num_rows($numLike);    

                        if ( empty($rowUserLike) )
                        {
                          echo '<div id = "like" class = "like"></div>';
                        }
                        else
                        {
                          echo '<div id = "dislike" class = "like active"></div>';
                        }      
                        echo '<span style = "margin-top:10px;">Вподобали:</span><div onclick = "show(\'block\',\'7\')"  style = "width: 10px; height: 10px; margin-top: 10px; "><span id = "counter" class = "no_hightlight">'.$numRows.'</span></div>';
          echo' </div>
            </div>
            <div id = "post_inf" class = "d-flex flex-column justify-content-between ">
                <div id = "comments" class = "d-flex flex-column align-items-center">';
                      $getComment = mysqli_query($link,"SELECT * FROM comments where id_post = '$idPost'");

                      while ( $rowCommentInformation = mysqli_fetch_assoc($getComment) )
                      {

                        if ( $_SESSION['login'] == $rowCommentInformation['login_users'] )
                        {
                          echo '<span class = "mr-auto" style = "font-size: 2.5vmin;">'.$rowCommentInformation['login_users'].'</span>';
                          echo '<div style = "white-space: pre-wrap; font-size: 2.5vmin; width: 200px; height:auto;">'.$rowCommentInformation['comment'].'</div>';
                          echo '<span style = "color: black ; font-size: 1.5vmin" class = "ml-auto">'.$rowCommentInformation['datetime'].'</span>';
                          echo '<div class = "deleteComment ml-auto" style = "font-size: 2vmin;" id = "'.$rowCommentInformation['id_comments'].'">Видалити</div>';

                          if ( isset( $_POST['idDeleteComment'] ) )
                          {
                            $deleteComment = $_POST['idDeleteComment'];
                            $queryDeleteComment = "DELETE FROM comments WHERE id_comments = '$deleteComment'";
                            $deleteComment = mysqli_query($link, $queryDeleteComment) or die("Помилка " . mysqli_error($link));
                          }
                        }
                        else
                        {
                          echo '<span>'.$rowCommentInformation['login_users'].'</span>';
                          echo '<div style = "width: 200px; white-space: normal;">'.$rowCommentInformation['comment'].'</div>';
                          echo '<span style = "color: black; ">'.$rowCommentInformation['datetime'].'</span>';
                        }
                      }
          echo '</div>
                <form action = "" class = "d-flex flex-column align-items-center" method = "POST" name = "addComment">
                    <span class = "no_hightlight">Коментарій</span>
                    <textarea  name = "comment" cols = "40" rows = "4" class = "form-control"></textarea>
                    <input id = "button_register"  name = "addComment" type = "submit" value = "Добавити">   
                </form>
            </div>
        </div>';
    }
?>