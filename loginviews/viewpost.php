<?php
    $idPost = (int)$_GET['idPost'];

    $loginUser = $_SESSION['login'];
    $idUser    = $_SESSION['id'];

    //провірка чи існує пост
    $queryCheckPost =  mysqli_query($link, "SELECT image_id FROM imggallery WHERE image_id = '$idPost' AND iduser = '$idUser'");
    $numRowsId = mysqli_num_rows($queryCheckPost);

    //для провірки чи існує лайк користувача  в сесії
    $checkLikeUser = mysqli_query($link,"SELECT  * FROM likepost where id_post = '$idPost' AND id_user = '$idUser'");
    $rowCheckLike = mysqli_fetch_assoc($checkLikeUser); 

    if($numRowsId == 0)
    {
      echo '<div align = "center" style = "margin:15%;"><span style = "color: black ; font-size: 5.0vw;">Ця сторінка не доступна</span></div>';
    }
    else
    {
      $getFotoName = mysqli_query($link,"SELECT  `image_name` FROM imggallery where image_id = '$idPost'");//витягуєм ім'я картинки для виводу 
      $rowFotoName = mysqli_fetch_assoc($getFotoName);

      if( isset($_POST['addComment']) )//добавлення коментарів
      {
        $errorsComment = [];

        $regComment = '/(([a-zA-Z0-9]{4,255})|([а-яё0-9]{4,255})|([абвгґдеєжзиіїйклмнопрстуфхцчшщьюяы0-9]{4,255}))/';//ДОРОБИТИ
        $comment    = $_POST['comment'];

        if ( !preg_match($regComment, $comment) )
        {
          $errorsComment[] = "Коментарій повинен містити не менше ніж 4 символа";
        }

        if ( empty($errorsComment) )
        {

          if ( isset( $_SESSION['login'] ) )
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

      if ( isset( $_POST['status'] ) )
      {

        if ( empty( $rowCheckLike['id_user'] ) || ($rowCheckLike['id_user'] != $idUser) )
        {
          $sql = "INSERT INTO likepost(`id_user`,`id_post`) VALUES ('$idUser','$idPost')";
          $result = mysqli_query($link, $sql) or die("Помилка " . mysqli_error($link));;
        }
        else
        {
          $sql = "DELETE FROM likepost WHERE id_user = '$idUser' AND id_post = '$idPost'";
          $result = mysqli_query($link, $sql);
        }
      }

      if( isset( $_POST['deletePostYes'] ) )//видалення поста, лайків та коментарів які належать до нього
      {
        $getFotoPost = mysqli_query($link, "SELECT image_name FROM imggallery where image_id = '$idPost'");
        $rowFotoPost = mysqli_fetch_assoc($getFotoPost);

        $deleteFile = './fotopost/'.$rowFotoPost['image_name'].'';
        unlink($deleteFile);

        $deleteComments = "DELETE FROM comments WHERE id_post = '$idPost'";
        $resultDeleteComments = mysqli_query($link, $deleteComments) or die("Помилка " . mysqli_error($link));

        $deleteFoto = "DELETE FROM imggallery WHERE image_id = '$idPost'";
        $resultDeleteFoto = mysqli_query($link, $deleteFoto) or die("Помилка " . mysqli_error($link));

        $deletelike = "DELETE FROM likepost WHERE id_post = '$idPost'";
        $resultDeletelike = mysqli_query($link, $deletelike) or die("Помилка " . mysqli_error($link));

        echo "<script type='text/javascript'>  window.location='index.php?action=profile'; </script>";
      } 

  echo '<div class = "d-flex flex-wrap justify-content-around">
            <div class = "d-flex flex-column" style = "max-width: 50%">
                <img src = "./fotopost/'.$rowFotoName['image_name'].'"  style = "margin-top: 20px; object-fit: contain; height: 450px; max-width: 100%" >
                <div  class = "d-flex flex-row justify-content-between">
                    <div onclick = "show(\'none\')" id = "wrap"></div>
                        <div id = "window6" class = "users_like">
                            <img class = "close" onclick = "show(\'none\')" src="./img/close.png" width = "20px" height = "20px">
                            <div class = "d-flex flex-column view_users_like">';

                                //для виводу тих хто лайкнув пост
                                $getListUser = mysqli_query($link,"SELECT * FROM `likepost` INNER JOIN `users` ON likepost.id_user = users.id where id_post = '$idPost'");

                                while ( $rowListUser = mysqli_fetch_assoc($getListUser) ) 
                                {
                                  $listUsers  = $rowListUser['login'];
                                  $idUserLike = $rowListUser['id_user'];

                                  if( $rowListUser['id_user'] == $_SESSION['id'] )
                                  {
                                    echo '<div><a href = "index.php?action=profile" >'.$listUsers.'</a></div>';
                                  }
                                  else
                                  {
                                    echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$idUserLike.'" >'.$listUsers.'</a></div>';
                                  }
                                }
                      echo '</div>
                        </div>
                        <div  class = "d-flex flex-row">'; 
                               
                            //для виводу кількості лайків під постом
                            $getNumLike =  mysqli_query($link, "SELECT * FROM likepost where id_post = '$idPost'");
                            $numRows = mysqli_num_rows($getNumLike);
                            
                            if ( empty($rowCheckLike) )
                            {
                              echo '<button class = "like" id = "like"></button>';
                            }
                            else
                            {
                              echo '<button class = "like active" id = "dislike"></button>';
                            }
                            echo '<span style = "margin-top:10px;">Вподобали: </span><div onclick = "show(\'block\',\'6\')" style = "margin-top:10px;" "><span id = "counter" class = "no_hightlight">'.$numRows.'</span></div>';
              echo '</div>
                    <div class = "deletePost">
                        <img src = "./img/trash.png" width = "40px" height = "40px" >
                    </div>
                </div>
            </div>
      <div id = "post_inf" class = "d-flex flex-column justify-content-between ">
          <div id = "comments" class = "d-flex flex-column align-items-center" >';
              $getComment = mysqli_query($link,"SELECT * FROM comments where id_post = '$idPost'");

              while ( $rowCommentInformation = mysqli_fetch_assoc($getComment) )
              {
                $deleteButtonId = $rowCommentInformation['id_comments'];
                echo '<span class = "mr-auto" style = "font-size: 2.5vmin;">'.$rowCommentInformation['login_users'].'</span>';
                echo '<div style = "white-space: pre-wrap; font-size: 2.5vmin; width: 200px; height:auto;">'.$rowCommentInformation['comment'].'</div>';
                echo '<span style = "color: black ; font-size: 1.5vmin" class = "ml-auto">'.$rowCommentInformation['datetime'].'</span>';
                echo '<div class = "deleteComment ml-auto" style = "font-size: 2vmin;" id = "'.$deleteButtonId.'">Видалити</div>';

                if ( isset( $_POST['idDeleteComment'] ) && ( $_POST['idDeleteComment'] == $deleteButtonId ))
                {
                  $comment = $_POST['idDeleteComment'];

                  $queryDeleteComment = "DELETE FROM comments WHERE id_comments = '$comment'";
                  $deleteComment = mysqli_query($link, $queryDeleteComment) or die("Помилка " . mysqli_error($link));
                }
              }
    echo' </div>
          <form action = "" class = "d-flex flex-column align-items-center" method = "POST" name = "addComment">
              <span class = "no_hightlight">Коментарій</span>
              <textarea  name = "comment" cols = "40" rows = "4" class = "form-control"></textarea>
              <input id = "button_register"  name = "addComment" type = "submit" value = "Добавити">
          </form>
      </div>
    </div>';
    }
?>