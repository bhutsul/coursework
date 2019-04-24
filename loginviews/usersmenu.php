    <div class = "button_main">
        <a href = "index.php?action=search">
          <span class = "no_hightlight clickProfileBtn" style = "font-size: 3vmin;">Пошук</span>
        </a>
    </div>
    <div class = "button_main ">
        <a href = "index.php?action=news">
          <span class = "no_hightlight clickProfileBtn" style = "font-size: 4vmin;">Instalite</span>
        </a>
    </div>
    <div>
        <a href = "index.php?action=profile">
          <span class = "no_hightlight clickProfileBtn" style = "font-size: 3vmin;">
               <?php  
                   echo $_SESSION['login'];
                ?>
          </span>
        </a>
    </div>
</div>