<?php
    $idUser        = (int)$_GET['idUser'];
    $loginFollower = $_SESSION['login'];
    $idFollower    = $_SESSION['id'];

    $checkUser =  mysqli_query($link, "SELECT id, login FROM users WHERE id = '$idUser' AND login != '$loginFollower'");//провірка чи існує юзер
    $numRowsId = mysqli_num_rows($checkUser);

    $rowUserLogin = mysqli_fetch_assoc($checkUser);
    $loginFollow  = $rowUserLogin['login'];

    if($numRowsId == 0)
    {
      echo '<div align = "center" style = "margin:15%;"><span style = "color: black ; font-size: 5.0vw;">Ця сторінка не доступна</span></div>';
    }
    else
    {
      
      $followUser = mysqli_query($link,"SELECT  * FROM followers where id_follower = '$idFollower' AND id_user_follow = '$idUser'");
      $rowfollowUser = mysqli_fetch_assoc($followUser);

      if( isset( $_POST['follow'] ) )//підписка
      {

        if($rowfollowUser['id_user_follow'] != $idUser)
        {
          $sql = "INSERT INTO followers(`id_user_follow`,`id_follower`) VALUES ('$idUser','$idFollower')";
          $result = mysqli_query($link, $sql);
        }
        else 
        {
          $sql = "DELETE FROM followers WHERE id_follower = '$idFollower' AND id_user_follow = '$idUser'";
          $result = mysqli_query($link, $sql);
        }
      }     

      echo '<div  class = "d-flex flex-row justify-content-center" style = "padding-bottom: 20px; border-bottom: 1px solid silver;">
                <div id = "avatar" style = "margin-right: 5%;">';
                    //запит для виводу аватарки
                    $getAvatar = mysqli_query($link, "SELECT image_name FROM avatars where user_id = '$idUser'");
                    $rowAvatar = mysqli_fetch_assoc($getAvatar);  

                    if (!$rowAvatar['image_name'])
                    {
                      echo  '<img src = "./avatars/noavatar.png " style = "height: 20vw; max-height:200px; max-width:200px; width: 20vw; margin-top: 20px; object-fit: cover;" class = "rounded-circle">';
                    }
                    else
                    {
                      echo  '<img src = "./avatars/'.$rowAvatar['image_name'].' " style = "height: 20vw; max-height:200px; max-width:200px; width: 20vw; margin-top: 20px; object-fit: cover;" class = "rounded-circle" >';
                    }
          echo '</div>
                <div class = "d-flex flex-column justify-content-center align-items-start">
                    <span class = "no_hightlight" style = "font-size: 3vmax; opacity: 0.6;">';
                         $getLogin = mysqli_query($link, "SELECT login FROM users where id = '$idUser'");
                         $rowLogin = mysqli_fetch_assoc($getLogin); 

                         echo $rowLogin['login'];
              echo '</span>';

                    if ( empty($rowfollowUser) )
                    {
                      echo '<button class = "btn btn-light follow" id = "follow">Підписатись</button>';
                    }
                    else 
                    {
                      echo '<button class = "btn btn-light follow active" id = "unfollow">Відписатись</button>';
                    }  

              echo '<div class = "d-flex flex-row">
                        <div onclick = "show(\'block\',\'8\')" class = "d-flex flex-row clickProfileBtn">
                            <span class = "no_hightlight" style = "font-size: 2.8vmin;">Підписки: </span>';       
                                 $queryNumFollow = mysqli_query($link,"SELECT  * FROM followers INNER JOIN users ON followers.id_user_follow = users.id where id_follower = '$idUser'");
                                 $numFollow = mysqli_num_rows($queryNumFollow);

                                 echo '<div style = "font-size: 2.8vmin; margin-left: 4px; opacity: 0.6;">' .$numFollow.'</div>';			
                  echo '</div>
                        <div id onclick = "show(\'block\',\'7\')" class = "d-flex flex-row clickProfileBtn">
                            <span class = "no_hightlight" style = "margin-left: 10px; font-size: 2.8vmin;">Підписники: </span>';
                                 $queryNumFollowers = mysqli_query($link,"SELECT  * FROM followers INNER JOIN users ON followers.id_follower = users.id where id_user_follow = '$idUser'");
                                 $numFollowers = mysqli_num_rows($queryNumFollowers);

                           echo '<div style = "font-size: 2.8vmin; margin-left: 4px; opacity: 0.6;" id = "numFollowers">' .$numFollowers.'</div>';	
                  echo '</div>
                    </div>
                </div>
            </div>
            <div onclick = "show(\'none\')" id = "wrap"></div>
            <div id = "window7" class = "users_like" >
                <img class = "close" onclick = "show(\'none\')" src="./img/close.png" width = "20px" height = "20px">
                <div class = "d-flex flex-column view_users_like">';
                    while ( $rowFollowers = mysqli_fetch_assoc($queryNumFollowers) ) 
                    {

                      if ($rowFollowers['id_follower'] == $_SESSION['id'])
                      {
                        echo '<div><a href = "index.php?action=profile" >'.$rowFollowers['login'].'</a></div>';
                      }
                      else
                      {
                        echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$rowFollowers['id_follower'].'" >'.$rowFollowers['login'].'</a></div>';
                      }
                    }
          echo '</div>
            </div>

            <div id = "window8" class = "users_like" >
                <img class = "close" onclick = "show(\'none\')" src="./img/close.png" width = "20px" height = "20px">
                <div class = "d-flex flex-column view_users_like">';
                    while ( $rowFollow = mysqli_fetch_assoc($queryNumFollow) ) 
                    {

                      if ($rowFollow['id_user_follow'] == $_SESSION['id'])
                      {
                        echo '<div><a href = "index.php?action=profile" >'.$rowFollow['login'].'</a></div>';
                      }
                      else
                      {
                        echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$rowFollow['id_user_follow'].'" >'.$rowFollow['login'].'</a></div>';
                      }
                    }
          echo '</div>
            </div>

            <div class = "d-flex flex-row justify-content-start flex-wrap p-4" id = "fotoUser">';  
                //для виводу фото юзера 
                $getFotoPost = mysqli_query($link,"SELECT image_name, image_id FROM imggallery where iduser = '$idUser' LIMIT 6");
	              $numFoto = mysqli_num_rows($getFotoPost);

                if ($numFoto == 0)
                {
                  echo '<div style = "height: 40%"><span style = "font-size: 4em;">Немає фото</span></div>';
                }
                else
                {
                  $fotos = [];
                  
                  while ( $rowFotoInf = mysqli_fetch_assoc($getFotoPost) )
                  {
                    $fotos[] = $rowFotoInf;
                  }

                  foreach ($fotos as $foto):  
             echo  '
                    <div style = "margin: 2%;">
                        <a href = "index.php?action=viewuserpost&idPost='.$foto['image_id'].'" >
                          <img src = "./fotopost/'.$foto['image_name'].' " width = "300px" height = "300px" style = "object-fit: cover;">
                        </a>
                    </div>
                   ';
                  endforeach;
                }
                $_SESSION['idUser'] = $idUser;
      echo '</div>';
    }
?>