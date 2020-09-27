<?php 
    //We assume that there is an error until this is overwritten
    $msg = "No Nifti image uploaded";
    // $msg2 = "No Label file uploaded";
    // Connect to the DB
    $db = mysqli_connect("localhost", "root", "", "brainwiz");

    //We only run this if we got here by having pressed the upload button on the previous page.
    if (isset($_POST['upload'])){

        // This is where we will store the uploaded image. Right now it is only in one directory
        $nifti_path = "../images/".basename($_FILES['nifti']['name']);
        // $label_path = "../images/".basename($_FILES['label']['name']);

        $name = "../images/".preg_replace("/\.nii(\.gz)?/", "", basename($_FILES['nifti']['name']));
        $x_path = $name . "_x.jpg";
        $y_path = $name . "_y.jpg";
        $z_path = $name . "_z.jpg";

        

        // We try to upload the nifti file
        if (move_uploaded_file($_FILES['nifti']['tmp_name'], $nifti_path)){
            $msg = "Nifti Image uploaded succesfully!";
        }else{
            $msg = "There was a problem uploading the Nifti Image.";
        }

        // if($_FILES['label']['name']){
        //     if (move_uploaded_file($_FILES['label']['tmp_name'], $label_path)){
        //         $msg2 = "Labels uploaded succesfully!";
        //     }else{
        //         $msg2 = "There was a problem uploading the labels.";
        //     }
        // }

        shell_exec("C:\Users\andy\AppData\Local\Programs\Python\Python37-32\python.exe centerslice.py $nifti_path $x_path $y_path $z_path");

        $notes = $_POST["text"];
        $notes = str_replace('"', '', $notes);
        $notes = str_replace("'", '', $notes);

        $long_notes = $_POST["long_text"];
        $long_notes = str_replace('"', '', $long_notes);
        $long_notes = str_replace("'", '', $long_notes);

        $tags =  $_POST["tags"];

        $special_tags = ["animal","genotype","sex","date","dob","sambabrunno","weight","study","imagety"];

        foreach($special_tags as $st){
            if(isset($_POST[$st])){
                if($_POST[$st] == ""){
                    // echo "<p>".$st." no input!!</p>";
                }else{
                    $tags = $tags . ";" . $st . ":" . $_POST[$st];
                }
            }
        }

        // Strip whitespace from tags.
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

        $sql = "INSERT INTO images (uploader,x_path,y_path,z_path,nifti_path,notes,long_notes) VALUES ('$uploader','$x_path','$y_path','$z_path','$nifti_path','$notes','$long_notes');";

        echo mysqli_query($db, $sql);
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
        // echo "<h1>".$msg2."</h1>";
        // Here we use the $last_id to get the most recent image
        if(!isset($last_id)){
            $sql = "SELECT image_id FROM `images` ORDER BY image_id DESC LIMIT 1";
            $last_id = mysqli_fetch_assoc(mysqli_query($db, $sql))["image_id"];
        }

        $sql = "SELECT * FROM images WHERE `image_id` = $last_id LIMIT 1;";
        $result = mysqli_query($db, $sql);
        // We take the one and only row
        $row = mysqli_fetch_assoc($result);
    ?>
    <div>
        <h3> Image Previews </h3>
        <?php
            echo "<img src='../images/".$row["x_path"]."''>";
            echo "<img src='../images/".$row["y_path"]."''>";
            echo "<img src='../images/".$row["z_path"]."''>";
        ?>
    </div>
    <div>
        Download links : 
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
        if(isset($tags)){
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
        }else{
        // Here, we iterate over the tags
        $sql = "SELECT * FROM tags WHERE `image_id`=$last_id";
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