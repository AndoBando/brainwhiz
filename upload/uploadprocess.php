<?php 
    $msg = "No image uploaded";
    //upload button is pressed
    if (isset($_POST['upload'])){
        $target = "../images/".basename($_FILES['image']['name']);

        $db = mysqli_connect("localhost", "root", "", "photos");

        $image = $_FILES['image']['name'];
        $text = $_POST["text"];
        $tags =  $_POST["tags"];
        $tags = $tags . shell_exec("python dicomparse.py " . $target);
        $tags = preg_replace('/\s/', '', $tags);
        $tags = str_replace('"', '', $tags);
        $tags = str_replace("'", '', $tags);
        $tags = explode(';',$tags);


        if($_POST["uploader"]==""){
            $uploader = "";
        }else{
            session_start();
            $uploader = $_SESSION["usr"];
        }
        $sql = "INSERT INTO images (image, notes, uploader) VALUES ('$image', '$text', '$uploader');";
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            $msg = "Image uploaded succesfully!";
            mysqli_query($db, $sql);
            $last_id = mysqli_insert_id($db);
        }else{
            $msg = "There was a problem uploading the image.";
        }

    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Uploaded</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        echo "<h1>".$msg."</h1>";
        $sql = "SELECT * FROM images WHERE `image_id` = $last_id LIMIT 1;";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
    ?>
    <div>
        <h3> Image Preview </h3>
        <?php
            echo "<img src='../images/".$row["image"]."''>";
        ?>
    </div>
    <div>
        <h3> Image Details </h3>
        <?php
        echo "<p>";
        echo "<b> Image Description : </b>";
        echo $row["notes"];
        echo "</p>";
        echo "<p>";
        echo "<b> Upload Time : </b>";
        echo $row["uploadTime"];
        echo "</p>";
        echo "<p>";
        echo "<b> Uploader : </b>";
        echo "<font color='red'>".$row["uploader"]."</font>";
        echo "</p>";
        foreach ($tags as $tag) {
            $l = explode(':', $tag);
            if($l[0] != ''){
                if (count($l) == 1){
                    echo "<p> <b> " . $l[0] . " </b> </p>" ;
                    $sql = "INSERT INTO `tags`(`attribute`, `image_id`) VALUES ('$l[0]', '$last_id');";
                }else{
                    echo "<p> <b> " . $l[0] . " </b> : " . $l[1] . " </p>" ;
                    $sql = "INSERT INTO `tags`(`attribute`, `value`, `image_id`) VALUES ('$l[0]', '$l[1]', '$last_id');";
                }
                mysqli_query($db, $sql);
            }
        }
        ?> 
    </div>
    <div>
    <p>
    <a href="upload.php"> Return to Upload Page </a>
    </p>
    <p>
    <a href="../gallery/gallery.php"> View in Gallery </a>
    </div>
    </p>
</body>
</html>