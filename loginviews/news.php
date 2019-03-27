<div class = "d-flex flex-column flex-wrap align-items-center">
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

          while ( $rowUserFollow = mysqli_fetch_assoc($getUserFollow) )
          {
            $idUserNews = $rowUserFollow['id_user_follow'];

            $getInfFollow = mysqli_query($link, "SELECT * FROM `imggallery` INNER JOIN users ON imggallery.iduser = users.id where iduser = '$idUserNews'  ORDER BY UNIX_TIMESTAMP(date_post) DESC");

            while ( $infFollow = mysqli_fetch_assoc($getInfFollow) )
            {
              $idFoto = $infFollow['image_id'];
              $idUser = $infFollow['iduser'];

         echo '
              <div class = "d-flex flex-column">
                  <a href = "index.php?action=viewuserprofile&idUser='.$idUser.'" >
                    <span style = "font-size: 4vmin;">'.$infFollow['login'].'</span>
                  </a>
                  <span style = "font-size: 2vmin;">'.$infFollow['date_post'].'</span>
              </div>
              <a href = "index.php?action=viewuserpost&idPost='.$idFoto.'" >
              <img src = "./fotopost/'.$infFollow['image_name'].' "style = "margin-top: 20px; width:100%; max-width: 500px; height: 500px; object-fit: cover;">
              </a>
              ';
            }
          }
        }
    ?>
</div>