    <?php
        session_start();
        require("../config/db.php");

        $startFrom = $_POST['startFrom'];
        $id        = $_SESSION['id'];

        $getFoto = mysqli_query($link, "SELECT image_name, image_id FROM imggallery where iduser = '$id' LIMIT {$startFrom}, 6");

        $fotos = array();
        while ( $rowFoto = mysqli_fetch_assoc($getFoto) )
        {
           $fotos[] = $rowFoto;
        }
        echo '<JSON>'.json_encode($fotos). '</JSON>';
    ?>