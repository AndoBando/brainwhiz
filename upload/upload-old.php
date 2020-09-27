<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Uploads</title>
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


    function tagdropdown($tagname,$display){
        $db = mysqli_connect("localhost", "root", "", "brainwiz");
        $sql = "SELECT DISTINCT `value` FROM `tags` WHERE tags.attribute='$tagname'";
        $result = mysqli_query($db, $sql);
        echo "<div>";
        echo "<label for='$tagname'>$display </label>";
        echo '<select name="$tagname" id="$tagname">';
        echo '<option value=""></option>';
        while($row = mysqli_fetch_array($result)){
            $value = $row['value'];
            echo '<option value="$value">'.$value.'</option>';
        }
        echo "</select>";
        echo '<span style="color:LightGray;">('.$tagname.')</span>';
        echo "</div>";
    }
    ?>
</div>  
<div id=content>
    <h1>Upload Image</h1>
    <form action="uploadprocess.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="hidden" name="size" value="1000000">
        <h5> File </h5>
        <div>
           Upload image : 
           <input type="file" name="image">
        </div>
        <br>
        <div>
            <textarea name="text" cols="40" rows="2" placeholder="Give a short description of the image here"></textarea>   
        </div>
        <h5> Uploader </h5>
        <div>
            <?php
            if(isset($_SESSION["logged_in"])){
                echo "<input type='radio' name='uploader' value='usr' checked>";
                echo " Upload image as user : ";
                echo "<font color='red'>";
                echo $_SESSION["usr"];
                echo "</font>";
            }
            ?>
            <br>
            <input type='radio' name='uploader' value=''
            <?php
            if(isset($_SESSION["logged_in"])){}else{
                echo "checked";
            }
            ?>
            >
            Upload image anonymously
        </div>
        <div>
            <h5> Image Attributes and Tags</h5>

            <div>
                <p> Use this section to quickly insert common attributes with values that have already been created.</p>
                <?php
                tagdropdown("animal","Animal:")
                ?>
                <?php
                tagdropdown("animal","Animal:")
                ?>
            </div>
            <div>
                <p> Use this section to insert less common tags, or to give new values to existing attributes</p>
                <textarea name="tags" cols="40" rows="4" placeholder="attribute1:value; tag1; tag2; ... e.g. location:rome; portait; night;"></textarea>
            </div>
        </div>
        <br>
        <div>
            <input type="submit" name="upload" value="Upload Image">
        </div>
    </form>
</div>    
</body>
</html>