 <?php 
     $link = mysqli_connect('localhost', 'root', '', 'gallery');
     if (mysqli_connect_errno())
     {
       printf("не вдалось підключитись: %s\n", mysqli_connect_error());
       exit();
     }
     mysqli_set_charset($link, "utf8");
 ?>