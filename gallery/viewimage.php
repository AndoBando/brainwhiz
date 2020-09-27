
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Image</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php
        $db = mysqli_connect("localhost", "root", "", "brainwiz");
        // Here we use the $last_id to get the most recent image
        $id = $_GET['id'];
        $sql = "SELECT * FROM images WHERE `image_id` = $id LIMIT 1;";
        $result = mysqli_query($db, $sql);
        // We take the one and only row
        $row = mysqli_fetch_assoc($result);
    ?>
    <div>
        <?php
        echo "<h1>".$row['notes']."</h1>";
        ?>
    </div>
    <div>
        <h3> Image Previews </h3>
        <?php
            echo "<img src='../images/".$row["x_path"]."''>";
            echo "<img src='../images/".$row["y_path"]."''>";
            echo "<img src='../images/".$row["z_path"]."''>";
        ?>
    </div>
    <div>
        <h4> Download links : </h4>
        <?php
            echo "<div> <a href=".$row['nifti_path']."> Nifti Download </a> </div>";
            // if(isset($row['label_path'])){
            //     echo "<div> <a href=".$row['label_path']."> Label Download </a> </div> ";
            // }
        ?>
    </div>
    <div>
        <h3> Image Details </h3>
        <?php
        if(isset($row["long_notes"])){
            echo "<p>";
            echo "<b> Image Description : </b>";
            echo $row["long_notes"];
        } 
        echo "</p>";
        echo "<p>";
        echo "<b> Upload Time : </b>";
        echo $row["timestamp"];
        echo "</p>";
        echo "<p>";
        echo "<b> Uploader : </b>";
        echo "<font color='red'>".$row["uploader"]."</font>";
        echo "</p>";
        ?>

        <div>
            <h3> Image tags and attributes </h3>   
            <?php
            // Here, we iterate over the tags
            $sql = "SELECT * FROM tags WHERE `image_id`=$id";
            $result_tag = mysqli_query($db, $sql);
            while($tag = mysqli_fetch_array($result_tag)){
                    echo "<p> <b> ";
                    echo $tag['attribute'];
                    if($tag['value']){
                        echo " :</b> <i>" . $tag['value'] . "</i>";
                    }else{
                        echo "</b>";
                    }
                    echo "</p>";
                    
                }
            ?> 
        </div>
    </div>
    <div>
    <!-- Some links to other pages -->
    <p>
    <a href="upload.php"> Return to Upload Page </a>
    </p>
    <p>
    <a href="../gallery/gallery.php"> View in Gallery </a>
    </div>
    </p>
</body>
</html>