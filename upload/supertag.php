<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create Supertag</title>
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
<div id=content>
    <h1>Upload Image</h1>
    <form action="supertagprocess.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <p>
                <label>Super Attribute:</label>
                <input type="text" id="superattribute" name="superattribute">
        </p>
        <p>
                <label>Super Value:</label>
                <input type="text" id="supervalue" name="supervalue">
        </p>
        <p>
                <label>Sub Attribute:</label>
                <input type="text" id="subattribute" name="subattribute">
        </p>
        <p>
                <label>Sub Value:</label>
                <input type="text" id="subvalue" name="subvalue">
        </p>
        <p>
            Eg. Animal:Frog:Reptile; Project:Example:Author:Alex
        </p>
        <div>
            <input type="submit" name="supertag" value="Create Supertag Association">
        </div>
    </form>
</div>    
</body>
</html>