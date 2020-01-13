<?php
    $umsg = "";
    $pmsg = "";
    if(isset($_POST["btn"])){
        $db = mysqli_connect("localhost", "root", "", "photos");
        $usr = $_POST["usr"];
        $sql = "SELECT * FROM users WHERE `username`='$usr' LIMIT 1";
        $result = mysqli_query($db, $sql);
        if(mysqli_num_rows($result) > 0){
           $row = mysqli_fetch_assoc($result);
           if($row["password"] == $_POST["pass"]){
                session_start();
                $_SESSION["logged_in"] = True;
                $_SESSION["usr"] = $usr;
                echo '<script type="text/javascript">
               window.location = "../';
               if(isset($_SESSION["from_page"])){
                echo $_SESSION["from_page"];
                }
                echo '"</script>';
           } else {
            $pmsg = "This password does not match the one associated with your account in our records. If you have forgotten your password or believe there is a mistake please contact the site administrator.";
           }
        } else {
            $umsg = "No account with that username exists. If you do not have an account, create one here : 
            <button onclick='location.href=\"register.php\"' type=\"button\"> Create an Account</button>" ;
        }
    }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div>
        <h1>Login</h1>
    </div>
    <div id="frm">
        <form action="login.php" method ="POST">
            <p>
                <label>Username:</label>
                <input type="text" id="usr" name="usr"
                <?php
                if(isset($_POST["usr"]))
                echo "value=".$_POST["usr"];
                ?>
                >

                <font color="red"> 
                <?php
                echo $umsg;
                ?>
                </font>
            </p>
            <p>
                <label>Password:</label>
                <input type="password" id="pass" name="pass">
                <font color="red"> 
                <?php
                echo $pmsg;
                ?>
                </font>
            </p>
            <p>
                <input type="submit" id="btn" name="btn" value="Login">
            </p>
    </div>
    <div>
        If you do not yet have an account, please make an account here : 
        <button onclick="location.href='register.php'" type="button"> Create an Account</button>
    </div>
    
</body>
</html> 