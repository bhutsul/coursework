
<form name = "searchform" action = "" method="POST"  class = "d-flex flex-column align-items-center justify-content-between">
    <div id = "search_content" class = "d-flex flex-column align-items-center">
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
    <span class = "no_hightlight" style = "font-size: 2.5vmin;">Введіть ім'я користувача</span>
    <input type = "text" size = "40" name = "searchuser"  class = "size_text">
    <input type = "submit" name = "searchform" value = "Пошук" id = "button_register" style = "margin-top: 5px;" class = "gradient">
</form>
