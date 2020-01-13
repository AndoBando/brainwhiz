<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gallery</title>
    <link rel="stylesheet" href="../style.css" type="text/css">
</head>
<body>
<div align="right">
    <?php
    session_start();
    if(isset($_SESSION["logged_in"])){
        echo "Logged in as ";
        echo "<font color = 'red'>";
        echo $_SESSION["usr"];
        echo "</font>";
        echo ".";
        echo "<p>";
        echo "Login in with different account : ";
        echo "<button onclick='location.href=\"../login/login.php\"' type=\"button\"> Login </button>";
        echo "</p>";
    }else{
        $_SESSION["from_page"] = "gallery/gallery.php";
        echo "No account logged in. \t";
        echo "<button onclick='location.href=\"../login/login.php\"' type=\"button\"> Login</button>";
    }
    ?>
</div>
<div align="right">
    <form action="gallery.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="submit" name="submit" value="Search">
        <input type="text" id="search" name="search">
    </form>
</div>
<div>
    <button type="button" onclick="location.href='../upload/upload.php'">Upload new Image</button>
</div>
<div align="center">
<?php
$sql = "SELECT * FROM images ORDER BY image_id DESC";
if(isset($_POST['submit'])){
    $s = $_POST['search'];
    echo $s;
    $l = explode(':', $s);
    if($l[0] == ''){

    }
    elseif (count($l) == 1){
    $sql = "SELECT * FROM images LEFT JOIN tags ON images.image_id=tags.image_id WHERE attribute='$l[0]' ORDER BY images.image_id DESC";
    }
    elseif (count($l) == 2){
    $sql = "SELECT * FROM images LEFT JOIN tags ON images.image_id=tags.image_id WHERE attribute='$l[0]' AND value='$l[1]' ORDER BY images.image_id DESC";
    }
}
?>
</div>
<div id = "content">
<?php
    $db = mysqli_connect("localhost", "root", "", "photos");
    $result = mysqli_query($db, $sql);
    while($row = mysqli_fetch_array($result)){
        echo "<div class=\"gal_div\">";
            echo "<img src='../images/".$row["image"]."'>";
            echo "<div>";
            echo '('.$row['image_id'].') '.$row['notes']."<br>";
            echo "From : <font color='red'>".$row["uploader"]."</font><br>";
            //echo "<i>".$row["uploadTime"]."</i><br>";
            $id  = $row['image_id'];
            $sql = "SELECT * FROM tags WHERE `image_id`=$id";
            $result_tag = mysqli_query($db, $sql);
            // while($tag = mysqli_fetch_array($result_tag)){
            //     echo $tag['attribute'];
            //     if(is_null($tag['value'])){}else{
            //         echo ":" . $tag['value'];
            //     }
            //     echo "; ";
                
            // }
            echo "</div>";
        echo "</div>";
    }
?>
</div>   
</body>
</html>