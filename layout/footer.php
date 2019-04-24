            <div id = "footer" class = "d-flex justify-content-between ">
                <span style = "font-size: 3vmin;" class = "clickProfileBtn"> © B.Hutsul</span>
                <?php 
                    if ( isset($_SESSION['login'])and isset($_SESSION['id']))
                    { 
                ?>
                      <div class = "button_profile">
                          <a href = "index.php?action=logout">
                            <span class = "no_hightlight clickProfileBtn" style = "font-size: 3vmin;">Вихід</span>
                          </a>
                      </div>
                <?php
                    }
                ?>
            </div>
    </div>		
</body>
</html>