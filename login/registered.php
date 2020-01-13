<?php
    session_start();
    if($_SESSION["reged"]){

    } else {
        echo '<script type="text/javascript">
           window.location = "fail.php"
            </script>';
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration sucess!</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <h1> Account created! </h1>
    <p>
    New account created with user name : 
    <?php
    echo "<font color='red'>".$_SESSION["regusr"]."</font>";
    ?>
    </p>
    <p>
    Go to login in with your new account : 
    <form action="login.php">
        <input type="submit" value="Go to Login Page" />
    </form>
    </p>
</body>
</html>