    <?php
        session_start();
        require("../config/db.php");

        $startFrom = $_POST['newsStartFrom'];
        $endFrom   = $_POST['newsEndFrom'];
        $id        = $_SESSION['id'];

        $getUserFollow = mysqli_query($link,"SELECT id_user_follow FROM followers where id_follower = '$id'");
        $news = [];

        while ( $rowUserFollow = mysqli_fetch_assoc($getUserFollow) )
        {
          $idUserNews = $rowUserFollow['id_user_follow'];

          $getInfFollow = mysqli_query($link, "SELECT * FROM `imggallery` INNER JOIN users ON imggallery.iduser = users.id where iduser = '$idUserNews' ");


          while ( $infFollow = mysqli_fetch_assoc($getInfFollow) )
          {
            $news[] = $infFollow;
          }
        }

        $sortNews = [];

        foreach ($news as $key => $arr) 
        {
          $sortNews[$key] = $arr['date_post'];
        }
        array_multisort($sortNews, SORT_STRING, SORT_DESC, $news);

        $newsStart = [];

        for ($i = $startFrom; $i < $endFrom; $i++)
        {
          $newsStart[] = $news[$i];
        }

        echo '<JSON>'.json_encode($newsStart). '</JSON>';
    ?>