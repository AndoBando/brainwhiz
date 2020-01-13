<?php
$usr = "";
$pmsg = "";
$umsg = "";
if (isset($_POST["btn"])){
    $usr = $_POST["usr"];
    $pass = $_POST["pass"];
    $db = mysqli_connect("localhost", "root", "", "photos");
    $sql = "SELECT * FROM users WHERE `username`='$usr'";
    $result = mysqli_query($db, $sql);
    if(mysqli_num_rows($result) > 0){
        $umsg = "\tA user with that name already exists. If you already have an account, login : 
        <button onclick='location.href=\"login.php\"' type=\"button\"> Go to Login Page</button>";
    } else {
        if ($pass == $_POST["cpass"]){
            $sql = "INSERT INTO users (username, password) VALUES ('$usr', '$pass');";
            mysqli_query($db, $sql);
            session_start();
            $_SESSION["regusr"] = $usr;
            $_SESSION["reged"] = True;
            echo '<script type="text/javascript">
               window.location = "registered.php"
                </script>';
        } else {
            $pmsg = "\tPassword do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register for an Account</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div id="frm">
        <form action="register.php" method ="POST">
            <h2>Register for an account</h2>
            <p>
                <label>Username:</label>
                <?php
                    echo "<input type='text' id='usr' name='usr' value=".$usr.">";
                    echo "<font color='red'>".$umsg."</font>";
                ?> 
            </p>
            <p>
            <b>NOTE</b> : Other users will be able to see your username. Usernames must be unique.<br>
            </p>
            <p>
                <label>Password:</label>
                <input type="password" id="pass" name="pass">
                <?php
                 echo "<font color='red'>".$pmsg."</font>";
                 ?> 
            </p>
            <p>
                <label>Confirm Password:</label>
                <input type="password" id="cpass" name="cpass">
            </p>
            <p>
            <b>NOTE</b> : Passwords are <b>NOT</b> secure! While it will not be visible to other users, the site administrator can see all passwords. Passwords are stored in plain text. You should <b>NOT</b> reuse a password from another site. If you leave the password fields blank, you will have the empty string "" as your password. If you do this, you must also leave the password field blank when you login.
            </p>
            <p>
                <input type="submit" id="btn" value="Create Account" name="btn">
            </p>
    </div>
    <div>
        If you already have an account, go to the login page here : 
        <button onclick="location.href='login.php'" type="button"> Go to Login Page</button>
    </div>
    
</body>
</html> 