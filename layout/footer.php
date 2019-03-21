            <div id = "footer" class = "d-flex justify-content-between " style = "padding-top: 10px; border-top: 1px solid silver;">
                <span style = "font-size: 3vmin;"> © B.Hutsul</span>
                <?php 
                    if ( isset($_SESSION['login'])and isset($_SESSION['id']))
                    { 
                ?>
                      <div class = "button_profile">
                          <a href = "index.php?action=logout">
                            <span class = "no_hightlight" style = "font-size: 3vmin;">Вихід</span>
                          </a>
                      </div>
                <?php
                    }
                ?>
            </div>
    </div>		
</body>
</html>