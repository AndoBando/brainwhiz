<?php 
    //We only run this if we got here by having pressed the upload button on the previous page.
    if (isset($_POST['supertag'])){

        // Connect to the DB
        $db = mysqli_connect("localhost", "root", "", "brainwiz");

        $spa = $_POST["superattribute"];
        $spv = $_POST["supervalue"];
        $sba = $_POST["subattribute"];
        $sbv = $_POST["subvalue"];

        $sql = "INSERT INTO supertags (superattribute, supervalue, subattribute, subvalue) VALUES ('$spa', '$spv', '$sba', '$sbv');";
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
     <?php if(mysqli_query($db, $sql)){
            echo  "<h2> Supertag Created </h2>";
        }else{
            echo  "<h2> Supertag Failed to Create </h2>";
        }
    ?>
        <p>
            Super Attribute: <?php echo $spa;?>
        </p>
        <p>
            Super Value: <?php echo $spv;?>
        </p>
        <p>
            Sub Attribute: <?php echo $sba;?>
        </p>
        <p>
            Sub Value: <?php echo $sbv;?>
        </p>
    <!-- Some links to other pages -->
    <p>
    <a href="supertag.php"> Return to Supertag Creation Page </a>
    </p>
    <p>
    <a href="../gallery/gallery.php"> Back to Gallery </a>
    </div>
    </p>
</body>
</html>