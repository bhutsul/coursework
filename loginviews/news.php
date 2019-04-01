<div class = "d-flex flex-column flex-wrap align-items-center" id = "news">
    <?php
        $id = $_SESSION['id']; 

        $getUserFollow = mysqli_query($link,"SELECT id_user_follow FROM followers where id_follower = '$id'");
        $numNews = mysqli_num_rows($getUserFollow);

        if ($numNews == 0)
        {
          echo '<div style = "margin: 15%;"><span style = "color: black ; font-size: 5.0vw;">Новин немає;(</span></div>';
        }
        else
        {
          $news = [];
          while ( $rowUserFollow = mysqli_fetch_assoc($getUserFollow) )
          {
            $idUserNews = $rowUserFollow['id_user_follow'];

            $getInfFollow = mysqli_query($link, "SELECT date_post, image_name, iduser, image_id, login FROM `imggallery` INNER JOIN users ON imggallery.iduser = users.id where iduser = '$idUserNews' ");

            while ( $infFollow = mysqli_fetch_assoc($getInfFollow) )
            {
              $news[] = $infFollow;
            }
          }
          
          $newsStart = [];
          for ($i = 0; $i < 3; $i++)
          {
            $newsStart[] = $news[$i];
          }

          foreach ($newsStart as $newsInf):
       echo '
            <div class = "d-flex flex-column">
                <a href = "index.php?action=viewuserprofile&idUser='.$newsInf['iduser'].'" >
                  <span style = "font-size: 4vmin;">'.$newsInf['login'].'</span>
                </a>
                <span style = "font-size: 2vmin;">'.$newsInf['date_post'].'</span>
            </div>
            <a href = "index.php?action=viewuserpost&idPost='.$newsInf['image_id'].'" >
            <img src = "./fotopost/'.$newsInf['image_name'].' "style = "margin-top: 20px; width:100%; max-width: 500px; height: 500px; object-fit: cover;">
            </a>
            ';
          endforeach; 
        }
    ?>
</div>