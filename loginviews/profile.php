<?php
    $id    = $_SESSION['id'];
    $login = $_SESSION['login'];     

    if ( isset( $_POST['addFoto'] ) )//добавлення фото в профілі 
    {
      $errors = [];

      $uploaddir    = './fotopost/';
      $uploadfile   = $uploaddir.basename($_FILES['uploadfile']['name'] = uniqid());
      $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
      $detectedType = exif_imagetype($_FILES['uploadfile']['tmp_name']);
      $image        = $_FILES['uploadfile']['tmp_name'];
      $image_name   = $_FILES['uploadfile']['name'];

      if ($_FILES['uploadfile']['size'] >= 2000000)
      {
        $errors[] = "Розмір фото повинен бути не більше 2-х мегабайт";
      }

      if ( !( in_array($detectedType, $allowedTypes) ) )
      {
        $errors[] = "Некоректний файл";
      }

      if ( empty($errors) )//немає помилок,заносим всі дані про фото в бд та на сервер
      {
        $date = date("Y-m-d H:i:s");

        move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile);

        $sql= "INSERT INTO imggallery(`iduser`, `image`, `image_name`, `date_post`) VALUES ('$id', '$image', '$image_name', '$date')";
        $result = mysqli_query($link, $sql);
      } 
      else
      {
        $msg = array_pop($errors);
        echo '<div align = "center"><span style = "color: black ; font-size: 2.0vw;">'.$msg.'</span></div>';
      }
    }

    if( isset($_POST['addAvatar']) )//добавлення аватара
    {
      $uploaddir    = './avatars/';
      $uploadfile   = $uploaddir.basename($_FILES['uploadfile']['name'] = uniqid());
      $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
      $detectedType = exif_imagetype($_FILES['uploadfile']['tmp_name']);
      $image        = $_FILES['uploadfile']['tmp_name'];
      $image_name   = $_FILES['uploadfile']['name'];

      if ($_FILES['uploadfile']['size'] >= 2000000)
      {
        $errors[] = "Розмір фото повинен бути не більше 2-х мегабайт";
      }

      if ( !(in_array($detectedType, $allowedTypes)) )
      {
        $errors[] = "Некоректний файл";
      }

      if ( empty($errors) )//немає помилок,заносим данні про фото в бд та на сервер
      {
        move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile);

        $checkAvatar = mysqli_query($link, "SELECT * FROM avatars where user_id = '$id'");

        if ( !$rowCheckAvatar = mysqli_fetch_assoc($checkAvatar) )
        {
          $sql = "INSERT INTO avatars(`user_id`, `image`, `image_name`) VALUES ('$id', '$image', '$image_name')";
          $result = mysqli_query($link, $sql);
        }
        else
        {
          $deleteFile = './avatars/'.$rowCheckAvatar['image_name'].'';
          unlink($deleteFile);

          $sql = "UPDATE avatars SET `user_id` = '$id',`image` = '$image', `image_name` = '$image_name' where user_id = '$id'";
          $result = mysqli_query($link, $sql);
        }
      } 
      else
      {
        $msg = array_pop($errors);
        echo '<div align = "center"><span style = "color: black ; font-size: 2.0vw;">'.$msg.'</span></div>';
      }
    }
?>

<div  class = "d-flex flex-row justify-content-center" style = "padding-bottom: 20px; border-bottom: 1px solid silver;">
    <div style = "margin-right: 5%;">
        <?php
            $getAvatar = mysqli_query($link, "SELECT image_name FROM avatars where user_id = '$id'");//запит для виводу аватарки
            $rowAvatar = mysqli_fetch_assoc($getAvatar);  

            if (!$rowAvatar)
            {
              echo  '<img src = "./avatars/noavatar.png " style = "height: 20vw; max-height:200px; max-width:200px; width: 20vw; margin-top: 20px; object-fit: cover;" class = "rounded-circle" >';
            }
            else
            {
              echo  '<img src = "./avatars/'.$rowAvatar['image_name'].'" style = "height: 20vw; max-height:200px; max-width:200px; width: 20vw; margin-top: 20px; object-fit: cover;" class = "rounded-circle">';
            }
        ?>
    </div>
    <div id = "subscribers" class = "d-flex flex-column justify-content-center align-items-start">
        <span class = "no_hightlight" style = "font-size: 3vmax; opacity: 0.6;">
             <?php  
                 echo $_SESSION['login'];
              ?>
        </span>
        <div class = "d-flex flex-row"> 
            <div class = "d-flex flex-row clickProfileBtn"  onclick = "show('block','6')" >
                <span class = "no_hightlight" style = "font-size: 2.8vmin;">Підписки: </span>
                     <?php
                         $getFollow = mysqli_query($link, "SELECT  * FROM followers INNER JOIN users ON followers.id_user_follow = users.id where id_follower = '$id'");
                         $numFollow = mysqli_num_rows($getFollow);

                         echo '<div style = "font-size: 2.8vmin; margin-left: 4px;">' .$numFollow.'</div>';
                     ?>
            </div>
            <div class = "d-flex flex-row clickProfileBtn" onclick = "show('block','5')" >
                <span class = "no_hightlight" style = "margin-left: 10px; font-size: 2.8vmin;">Підписники: </span>
                     <?php
                         $getFollowers = mysqli_query($link, "SELECT  * FROM followers INNER JOIN users ON followers.id_follower = users.id where id_user_follow = '$id'");
                         $numFollowers = mysqli_num_rows($getFollowers);

                         echo '<div style = "font-size: 2.8vmin; margin-left: 4px;">' .$numFollowers.'</div>';
                     ?>
            </div>
        </div>

        <div class = "d-flex flex-column">
            <div class = "button_main mr-auto">
                <a href = "index.php?action=edit">
                  <span class = "no_hightlight clickProfileBtn" style = "font-size: 2.8vmin;">Редагувати профіль</span>
                </a>
            </div>
            <div onclick = "show('block','3')" class = "followers  mr-auto" style = "font-size: 2.8vmin;">
                <span class = "no_hightlight counterClick clickProfileBtn" style = "font-size: 2.8vmin;">Добавити пост</span>
            </div>
            <div  onclick = "show('block','4')" class = "followers  mr-auto">
                <span class = "no_hightlight counterClick clickProfileBtn" style = "font-size: 2.8vmin;" >Змінити аватар</span>
            </div>
        </div>
     </div>
</div>

<div onclick = "show('none')" id = "wrap"></div>
<div id = "window3" class = "boxstyleProfile">
    <img class = "close" onclick = "show('none')" src="./img/close.png" width = "20px" height = "20px">
      <form enctype = "multipart/form-data" action = "" method = "POST" name = "submit">
          <div class = "input-group">
              <div class = "custom-file">
                  <input type = "file" name = "uploadfile" class = "custom-file-input" id = "inputGroupFile01" aria-describedby = "inputGroupFileAddon01">
                  <label class = "custom-file-label" for = "inputGroupFile01">Вибрати</label>
              </div>
          </div>
          <input type = "submit" name = "addFoto" value = "Добавити фото" class = "btn btn-light ml-auto" style = "width: 100%; margin-top: 2%;"/>
      </form>
</div>

<div onclick = "show('none')" id = "wrap"></div>
<div id = "window4" class = "boxstyleProfile">
    <img class = "close" onclick = "show('none')" src="./img/close.png" width = "20px" height = "20px">
      <form enctype = "multipart/form-data" action = "" method = "POST" name = "add_avatar">
          <div class = "input-group">
              <div class = "custom-file">
                  <input type = "file" name = "uploadfile" class = "custom-file-input" id = "inputGroupFile01" aria-describedby = "inputGroupFileAddon01">
                  <label class = "custom-file-label" for = "inputGroupFile01">Вибрати</label>
              </div>
          </div>
          <input type = "submit" name = "addAvatar" value = "Змінити" class = "btn btn-light ml-auto" style = "width: 100%; margin-top: 2%;"/>
      </form>
</div>

<div id = "window5" class = "users_like" >
    <img class = "close" onclick = "show('none')" src="./img/close.png" width = "20px" height = "20px">
    <div class = "d-flex flex-column view_users_like">
        <?php
            while ( $rowFollowers = mysqli_fetch_assoc($getFollowers) ) 
            {
              echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$rowFollowers['id_follower'].'" >'.$rowFollowers['login'].'</a></div>';
            }
        ?>
    </div>
</div>

<div id = "window6" class = "users_like" >
    <img class = "close" onclick = "show('none')" src="./img/close.png" width = "20px" height = "20px">
    <div class = "d-flex flex-column view_users_like">
        <?php
            while ( $rowFollow = mysqli_fetch_assoc($getFollow) ) 
            {
              echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$rowFollow['id_user_follow'].'" >'.$rowFollow['login'].'</a></div>';
            }
        ?>
    </div>
</div> 
<div class = "d-flex flex-row flex-wrap justify-content-start p-4" id = "foto">
    <?php 
        $getFoto = mysqli_query($link, "SELECT image_name, image_id FROM imggallery where iduser = '$id' LIMIT 6");
        $numFoto = mysqli_num_rows($getFoto);

        if ($numFoto == 0)
        {
          echo '<div style = "height: 40%; margin-left: 32%;"><span style = "font-size: 4em;">Немає фото</span></div>';
        }
        else
        {
          
          $fotos = [];

          while ( $rowFoto = mysqli_fetch_assoc($getFoto) )
          {
            $fotos[] = $rowFoto;
          }
          foreach ($fotos as $foto): 
            echo '<div style = " margin: 2%;">
                      <a href = "index.php?action=viewpost&idPost='.$foto['image_id'].'" >
                        <img src = "./fotopost/'.$foto['image_name'].' " width = "300px" height = "300px" style = "object-fit: cover;">
                      </a> 
                  </div>';
          endforeach; 
        }
    ?>
</div>
