<?php
    session_start();
    /* note that for correct Linux operation MUST run a dos2unix convert to prevent issues w/CRLF hosing PHP...ridiculous BTW*/
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Veeam Skunkworks - VBR Logon</title>
    <link rel="stylesheet" href="css/dashboard.css" />

</head>
<body>
    <form name="loginForm" action="./auth.php" autocomplete="on" method="post">

        <div>
            <img src="images/lab.svg" alt="scienceproject" width="150" height="75" class="center-icon" />
            <h2>Veeam Skunkworks</h2>
        </div>
        <div class="grid-login">
            <div class="grid-item-header">
                VBR Login
                <?php
                    if (isset($_SESSION['authError'])) {
                        if ($_SESSION['authError'] <> "") {
                        echo "<h3 style=\"color:red\">".$_SESSION['authError']."</h3>";
                        }
                    }
                ?>
                <div class="icon d-flex align-items-center justify-content-center">
                    <span class="fa fa-user"></span>
                </div>
                    <input type="text" name="txtVBRServer" placeholder="VBR Server" required /><br />
                <input type="text" name="txtAdminUser" placeholder="Username" required /> <br />
                <input type="password" name="txtPasswd" placeholder="Password" required /><br />
                    <button type="submit" class="btn btn-primary rounded submit">Login</button>
            </div>
        </div>
    </form>
</body>
</html>
