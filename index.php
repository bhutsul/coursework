<?php 
  session_start();    
  require("./config/db.php");
  require("./layout/header.php");

  if(isset($_GET['action']))
  {
    $action = $_GET['action'];
    if(isset($_GET['idUser']))
    {  
      $idUser = $_GET['idUser'];
    }

    if(isset($_GET['idPost']))
    {
      $idPost = $_GET['idPost'];
    }
  }
  else
  {
    $action = "greeting";
  }

  if ( isset($_SESSION['login'])and isset($_SESSION['id']))
  {
    require_once "loginviews/usersmenu.php";
    if(file_exists("./loginviews/$action.php"))
    {
      include "./loginviews/$action.php";
    }
  }
  else
  {
    require_once "views/menu.php";
    if(file_exists("./views/$action.php"))
    {
      include "./views/$action.php";
    }
  }  
  require("./layout/footer.php");
?>