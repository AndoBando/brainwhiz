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
        echo "<label for='".$tagname."'>$display </label>";
        echo '<select name="'.$tagname.'" id="'.$tagname.'">';
        echo '<option value=""></option>';
        while($row = mysqli_fetch_array($result)){
            $value = $row['value'];
            echo '<option value="'.$value.'">'.$value.'</option>';
        }
        echo "</select>";
        echo '<span style="color:LightGray;">('.$tagname.')</span>';
    }

    function taginput($tagname,$display){
        echo "<label for='".$tagname."'>$display </label>";
        echo '<input name="'.$tagname.'" id="'.$tagname.'" type="text">';
        echo '<span style="color:LightGray;">('.$tagname.')</span>';
    }
    ?>
</div>  
<div id=content>
    <h1>Upload Image</h1>
    <form action="uploadprocess.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="hidden" name="size" value="1000000">
        <h4> File </h4>
        <div>
            <div>
                Upload Nifti Image : 
                <input type="file" name="nifti">
            </div>
<!--             <div>
                Upload Label File :  
                <input type="file" name="label">
            </div> -->
        </div>
        <br>

        <div>
            <h4> Text Description </h4>
            <div>
                <p> <label>Short Description</label> </p>
                <textarea name="text" cols="40" rows="2" placeholder="Give a short name to the image here. This will function essentially as a title for the image"></textarea>   
            </div>

            <div>
                <p> <label>Long Description and Notes</label> </p>
                <textarea name="long_text" cols="40" rows="4" placeholder='Give a longer description of the image here. This will only be visible in the "detailed view" of the image'></textarea>   
            </div>
            <span style="color:Gray;">(Neither of these sections are currently searchable)</span>
        </div>

        <h4> Uploader </h4>
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
            <h4> Image Attributes and Tags</h4>

            <div>
                <p> Use this section to quickly insert common attributes with values that have already been created.</p>
                <div> <?php taginput("animal","Animal:") ?> </div>
                <div> <?php taginput("date","Date:") ?> </div>
                <div> <?php tagdropdown("genotype","Genotype:") ?> </div>
                <div> <?php tagdropdown("sex","Sex:") ?> </div>
                <div> <?php taginput("dob","DOB:") ?> </div>
                <div> <?php taginput("sambabrunno","SAMBA Brunno:") ?> </div>
                <!-- <div> <?php taginput("t1memrirare","T1MEMRIRARE:") ?> </div> -->
                <!-- <div> <?php taginput("t1map","T1map:") ?> </div> -->
                <!-- <div> <?php taginput("t1map2","T1map2:") ?> </div> -->
                <div> <?php taginput("weight","Weight:") ?> </div>
                <!-- <div> <?php taginput("dwi","DWI:") ?> </div> -->
                <!-- <div> <?php taginput("gre","Gre:") ?> </div> -->
                <div> <?php tagdropdown("study","Study:") ?> </div>
                <div> <?php tagdropdown("imagetype","Image type:") ?> </div>
                <!-- <div> <?php taginput("t2turborare","T2TurboRARE:") ?> </div>
                <div> <?php taginput("perf1_2","Perf1_2:") ?> </div>
                <div> <?php taginput("perf2_1p5","Perf2_1p5:") ?> </div>
                <div> <?php taginput("efficiency","Efficiency:") ?> </div>
                <div> <?php taginput("labeloptim","LabelOptim:") ?> </div>
                <div> <?php taginput("controloptim","controlOptim:") ?> </div> -->
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