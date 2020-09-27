<?php 
    //We assume that there is an error until this is overwritten
    $msg = "No image uploaded";
    //We only run this if we got here by having pressed the upload button on the previous page.
    if (isset($_POST['upload'])){

        // This is where we will store the uploaded image. Right now it is only in one directory
        $target = "../images/".basename($_FILES['image']['name']);

        // Connect to the DB
        $db = mysqli_connect("localhost", "root", "", "brainwiz");

        // the file we uploaded
        $image = $_FILES['image']['name'];

        $text = $_POST["text"];
        $tags =  $_POST["tags"];
        // We run the dicom parser on the tag file and append the results
        // $tags = $tags . shell_exec("python dicomparse.py " . $target);
        // Strip whitespace
        $tags = preg_replace('/\s/', '', $tags);
         // And quote chars
        $tags = str_replace('"', '', $tags);
        $tags = str_replace("'", '', $tags);
        // and then separate into a list
        $tags = explode(';',$tags);


        if($_POST["uploader"]==""){
            $uploader = "";
        }else{
            session_start();
            $uploader = $_SESSION["usr"];
        }

        // We insert the image name into the database (you might want to change this to the full image path), along witht the text and uploader
        $sql = "INSERT INTO images (im_path, notes, uploader) VALUES ('$image', '$text', '$uploader');";
        
        // We try to upload the file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            $msg = "Image uploaded succesfully!";
            $a = mysqli_query($db, $sql);
            // We save the id of the image we just inserted for later
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
        // This displays whether we had a sucess or a failue
        echo "<h1>".$msg."</h1>";
        // Here we use the $last_id to get the most recent image
        $sql = "SELECT * FROM images WHERE `image_id` = $last_id LIMIT 1;";
        $result = mysqli_query($db, $sql);
        // We take the one and only row
        $row = mysqli_fetch_assoc($result);
    ?>
    <div>
        <h3> Image Preview </h3>
        <?php
            echo "<img src='../images/".$row["im_path"]."''>";
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
        echo $row["timestamp"];
        echo "</p>";
        echo "<p>";
        echo "<b> Uploader : </b>";
        echo "<font color='red'>".$row["uploader"]."</font>";
        echo "</p>";

        // Here, we iterate over the tags (semicolon separated)
        foreach ($tags as $tag) {
            // We split if we have a colon
            $l = explode(':', $tag);
            if($l[0] != ''){
                if (count($l) == 1){
                    // If we do not, we add only the attribute, no value
                    echo "<p> <i> " . $l[0] . " </b> </i>" ;
                    $sql = "INSERT INTO `tags`(`attribute`, `value`, `image_id`) VALUES ('$l[0]','', $last_id);";
                    mysqli_query($db, $sql);
                }else{
                    // If we do, we add both the attribute and the value
                    echo "<p> <i> " . $l[0] . " </i> : " . $l[1] . " </p>" ;
                    $sql = "INSERT INTO `tags`(`attribute`, `value`, `image_id`) VALUES ('$l[0]', '$l[1]', $last_id);";
                    mysqli_query($db, $sql);
                }
            }
        }
        ?> 
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