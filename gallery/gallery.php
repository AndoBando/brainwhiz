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
if (isset($_SESSION["logged_in"])) {
    echo "Logged in as ";
    echo "<font color = 'red'>";
    echo $_SESSION["usr"];
    echo "</font>";
    echo ".";
    echo "<p>";
    echo "Login in with different account : ";
    echo "<button onclick='location.href=\"../login/login.php\"' type=\"button\"> Login </button>";
    echo "</p>";
}
else {
    $_SESSION["from_page"] = "gallery/gallery.php";
    echo "No account logged in. \t";
    echo "<button onclick='location.href=\"../login/login.php\"' type=\"button\"> Login</button>";
}
?>
</div>
<div>
    <button type="button" onclick="location.href='../upload/upload.php'">Upload new Image</button>
    <button type="button" onclick="location.href='../upload/supertag.php'">Create New Supertag</button>
</div>
<div align="center">
    <form action="gallery.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="submit" name="submit" value="Search">
        <input type="text" id="search" name="search">
    </form>
</div>
<div align="center">
<?php
$sql = "SELECT image_id, uploader, z_path, notes FROM images ORDER BY image_id DESC";

if (isset($_POST['submit']) and $_POST['search']) {
    $s = $_POST['search'];
    $s = str_replace(" and ", " & ", $s);
    $s = str_replace(" or ", " | ", $s);
    $s = str_replace(" not ", " ~ ", $s);
    $s = preg_replace('/\s/', '', $s);
    echo $s;
    $s = str_replace("&", ";&;", $s);
    $s = str_replace("|", ";|;", $s);
    $s = str_replace("~", ";~;", $s);

    $tags = explode(';', $s);
    $sql = "SELECT DISTINCT images.image_id, uploader, z_path, notes FROM images ";
    foreach ($tags as $key => $t) {
        if ($t == "&" || $t == "|" || $t == "~"){continue;}
        if ($t) {
            $tagpair = explode(':', $t);
            if (count($tagpair) <= 2 && $tagpair[0] != '') {
                $sql = $sql . " LEFT JOIN tags AS T$key ON images.image_id=T$key.image_id ";
            }
            elseif (count($tagpair) <= 4) {
                $sql = $sql . " LEFT JOIN tags AS T$key ON images.image_id = T$key.image_id LEFT JOIN supertags AS ST$key ON T$key.attribute=ST$key.superattribute AND T$key.value=ST$key.supervalue ";
            }
        }
    }
    if(count($tags) > 1 || $tags[0] != ''){
        $sql = $sql . " WHERE ";
    }
    foreach ($tags as $key => $t) {
        if ($t) {
            if ($t == "&"){
                $sql = $sql . " AND "; continue;
            }
            elseif ($t == "|"){
                $sql = $sql . " OR "; continue;
            }
            elseif ($t == "~"){
                $sql = $sql . " NOT "; continue;
            }

            $symbs = ["<=",">=",">","<","="];
            if ($t[0] == '#') {
                $t = substr($t, 1);
                $colonpair = explode(':', $t);
                if (count($colonpair) == 1) {
                    foreach ($symbs as $symb) {
                        $tagpair = explode($symb, $colonpair[0]);
                        if (count($tagpair) == 2) {
                            $sql = $sql . " (T$key.attribute='$tagpair[0]' AND  CAST(T0.value AS DECIMAL) $symb $tagpair[1]) ";
                            break;
                        }
                    }
                }
                elseif (count($colonpair) == 3) {
                    if ($colonpair[0] == '') {
                        foreach ($symbs as $symb) {
                            $tagpair = explode($symb, $colonpair[2]);
                            if (count($tagpair) == 2) {
                                $sql = $sql . " (ST$key.subattribute='$tagpair[0]' AND  CAST(ST0.subvalue AS DECIMAL) $symb $tagpair[1]) ";
                                 break;
                            }
                        }
                    }else{
                        foreach ($symbs as $symb) {
                            $tagpair = explode($symb, $colonpair[2]);
                            if (count($tagpair) == 2) {
                                $sql = $sql . " (T$key.attribute='$colonpair[0]' AND ST$key.subattribute='$tagpair[0]' AND  CAST(ST0.subvalue AS DECIMAL) $symb $tagpair[1]) ";
                                 break;
                            }
                        }
                    }
                }
            }else if ($t[0] == '@') {
                $t = substr($t, 1);
                $colonpair = explode(':', $t);
                if (count($colonpair) == 1) {
                    foreach ($symbs as $symb) {
                        $tagpair = explode($symb, $colonpair[0]);
                        if (count($tagpair) == 2) {
                            $sql = $sql . " (T$key.attribute='$tagpair[0]' AND  CAST(T0.value AS DATE) $symb CAST('$tagpair[1]' AS DATE)) ";
                             break;
                        }
                    }
                }
                elseif (count($colonpair) == 3) {
                    if ($colonpair[0] == '') {
                        foreach ($symbs as $symb) {
                            $tagpair = explode($symb, $colonpair[2]);
                            if (count($tagpair) == 2) {
                                $sql = $sql . " (ST$key.subattribute='$tagpair[0]' AND  CAST(ST0.subvalue AS DATE) $symb CAST('$tagpair[1]' AS DATE)) ";
                            }
                        }
                    }else{
                        foreach ($symbs as $symb) {
                            $tagpair = explode($symb, $colonpair[2]);
                            if (count($tagpair) == 2) {
                                $sql = $sql . " (T$key.attribute='$colonpair[0]' AND ST$key.subattribute='$tagpair[0]' AND  CAST(ST0.subvalue AS DATE) $symb CAST('$tagpair[1]' AS DATE)) ";
                                 break;
                            }
                        }
                    }
                }
            }
            else {

                $tagpair = explode(':', $t);

                if (count($tagpair) == 1) {
                    $sql = $sql . " (T$key.attribute='$tagpair[0]') ";
                }
                elseif (count($tagpair) == 2) {
                    if($tagpair[0] != ''){
                        $sql = $sql . " (T$key.attribute='$tagpair[0]' AND T$key.value='$tagpair[1]') ";
                    }else{
                        $sql = $sql . " (T$key.value='$tagpair[1]') ";
                    }
                }

                if (count($tagpair) == 3) {
                    if ($tagpair[0] == '') {
                        $sql = $sql . " (ST$key.subattribute='$tagpair[2]') ";
                    }
                    else {
                        $sql = $sql . " (T$key.attribute='$tagpair[0]' AND ST$key.subattribute='$tagpair[2]') ";
                    }
                }
                elseif (count($tagpair) == 4) {
                    if ($tagpair[0] == '') {
                        $sql = $sql . " (ST$key.subattribute='$tagpair[2]' AND ST$key.subvalue='$tagpair[3]') ";
                    }
                    else {
                        $sql = $sql . " (T$key.attribute='$tagpair[0]' AND ST$key.subattribute='$tagpair[2]' AND ST$key.subvalue='$tagpair[3]') ";
                    }
                }
            }
        }
    }

}
?>
</div>
<div id = "content">
<?php
// echo $sql;
$db = mysqli_connect("localhost", "root", "", "brainwiz");
$result = mysqli_query($db, $sql);
echo $sql;
while ($row = mysqli_fetch_array($result)) {
    echo '<div class="gal_div">';
    echo '<a href=viewimage.php?id=' . $row['image_id'] . "> <img src='../images/" . $row["z_path"] . "'>" . '</a>';
    echo "<div>";
    echo '<a href=viewimage.php?id=' . $row['image_id'] . '> #' . $row['image_id'] . '</a>. ';
    echo $row['notes'] . "<br>";
    if ($row["uploader"]) {
        echo "From : <font color='red'>" . $row["uploader"] . "</font><br>";
    }
    // echo "<i>".$row["uploadTime"]."</i><br>";
    $id = $row['image_id'];
    $sql = "SELECT attribute, value FROM tags WHERE `image_id`=$id";
    $result_tag = mysqli_query($db, $sql);
    while ($tag = mysqli_fetch_array($result_tag)) {
        echo $tag['attribute'];
        if ($tag['value']) {
            echo ":" . $tag['value'];
        }
        echo "; ";

    }
    echo "</div>";
    echo "</div>";
}
?>
</div>   
</body>
</html>
