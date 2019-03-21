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

	  if( isset( $_POST['follow'] ) )//підписка
	  {
	    $followUser = mysqli_query($link,"SELECT  * FROM followers where login_follower = '$loginFollower' AND id_user_follow = '$idUser'");
	    $rowfollowUser = mysqli_fetch_assoc($followUser);

	   	if($rowfollowUser['id_user_follow'] != $idUser)
	   	{
	      $sql = "INSERT INTO followers(`id_user_follow`, `login_user_follow`, `login_follower`,`id_follower`) VALUES ('$idUser', '$loginFollow' ,'$loginFollower','$idFollower')";
	      $result = mysqli_query($link, $sql);
	   	}
	    else 
	    {
	      $sql = "DELETE FROM followers WHERE login_follower = '$loginFollower' AND id_user_follow = '$idUser'";
	      $result = mysqli_query($link, $sql);
	    }
	  }

	  echo '<div  class = "d-flex flex-row justify-content-center" style = "padding-bottom: 20px; border-bottom: 1px solid silver;">
		        <div id = "avatar" style = "margin-right: 5%;">';
		            $getAvatarName = mysqli_query($link,"SELECT * FROM avatars where user_id = '$idUser'");//запит для виводу аватарки
	                $rowAvatarName = mysqli_fetch_assoc($getAvatarName);

				    if (!$rowAvatarName)
				    {
				      echo  '<img src = "./avatars/noavatar.png " style = "height: 20vw; max-height:200px; max-width:200px; width: 20vw; margin-top: 20px; object-fit: cover;" class = "rounded-circle">';
				    }
				    else
				    {
				      echo  '<img src = "./avatars/'.$rowAvatarName['image_name'].' " style = "height: 20vw; max-height:200px; max-width:200px; width: 20vw; margin-top: 20px; object-fit: cover;" class = "rounded-circle" >';
				    }
	      echo '</div>
		        <div class = "d-flex flex-column justify-content-center align-items-start">
		            <span class = "no_hightlight" style = "font-size: 3vmax; opacity: 0.6;">';
		                $getLoginUser = mysqli_query($link,"SELECT  `id`, `login` FROM users where id = '$idUser'");//для виводу логіна 
                        $rowLoginUser = mysqli_fetch_assoc($getLoginUser);

			        	echo $rowLoginUser['login'];
		 	  echo '</span>
			        <form method = "POST" name = "follow" >      
			       		<input type = "submit" id = "button_subscribe" name = "follow" class = "gradient" value = "Підписатись">
				    </form>
			        <div class = "d-flex flex-row">
					    <div id = "subscriptions" class = "d-flex flex-row">
						     <span class = "no_hightlight" style = "font-size: 2.8vmin; opacity: 0.6;">Підписки: </span>';
								 $loginUser = $rowLoginUser['login'];        

								 $queryNumFollow = mysqli_query($link,"SELECT  * FROM followers where login_follower = '$loginUser'");
								 $numFollow = mysqli_num_rows($queryNumFollow);

							     echo '<div onclick = "show(\'block\',\'8\')" style = "font-size: 2.8vmin; margin-left: 4px; opacity: 0.6;">' .$numFollow.'</div>';			
				  echo '</div>
				        <div id = "followers" class = "d-flex flex-row">
					         <span class = "no_hightlight" style = "margin-left: 10px; font-size: 2.8vmin; opacity: 0.6;">Підписники: </span>';
							      $queryNumFollowers = mysqli_query($link,"SELECT  * FROM followers where id_user_follow = '$idUser'");
					              $numFollowers = mysqli_num_rows($queryNumFollowers);

							      echo '<div onclick = "show(\'block\',\'7\')" style = "font-size: 2.8vmin; margin-left: 4px; opacity: 0.6;">' .$numFollowers.'</div>';	
				  echo '</div>
			        </div>
		        </div>
	        </div>
            <div id = "window7" class = "users_like" >
			    <img class = "close" onclick = "show(\'none\')" src="./img/close.png" width = "20px" height = "20px">
			    <div class = "d-flex flex-column view_users_like">';
		            while ( $rowFollowers = mysqli_fetch_assoc($queryNumFollowers) ) 
		            {

                      if ($rowFollowers['id_follower'] == $_SESSION['id'])
                      {
                        echo '<div><a href = "index.php?action=profile" >'.$rowFollowers['login_follower'].'</a></div>';
                      }
                      else
                      {
		                echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$rowFollowers['id_follower'].'" >'.$rowFollowers['login_follower'].'</a></div>';
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
                        echo '<div><a href = "index.php?action=profile" >'.$rowFollow['login_user_follow'].'</a></div>';
                      }
                      else
                      {
		                echo '<div><a href = "index.php?action=viewuserprofile&idUser='.$rowFollow['id_user_follow'].'" >'.$rowFollow['login_user_follow'].'</a></div>';
                      }
		            }
		  echo '</div>
			</div>

	        <div class = "d-flex flex-row justify-content-around flex-wrap">';   
	            $getFotoPost = mysqli_query($link,"SELECT * FROM imggallery where iduser = '$idUser'");//для виводу фото юзера
	            
				while ( $rowFotoInf = mysqli_fetch_assoc($getFotoPost) )
				{
				  $idFoto = $rowFotoInf['image_id'];

				  echo  '<a href = "index.php?action=viewuserpost&idPost='.$idFoto.'" >
				           <img src = "./fotoPost/'.$rowFotoInf['image_name'].' " width = "300px" height = "300px" style = "margin-top: 30px; object-fit: cover;">
				        </a>';
			  	}
	  echo '</div>';
    }
?>