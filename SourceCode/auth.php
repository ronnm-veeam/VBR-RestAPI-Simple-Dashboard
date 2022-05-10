<?php
    session_start();
    ob_start();

    try {
        $url = 'https://'.$_POST['txtVBRServer'].':9419/api/oauth2/token';

        $cHandle = curl_init($url);

        if ($cHandle != false) {
            curl_setopt($cHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cHandle, CURLOPT_POST, true);
            $data = "grant_type=password&username=".$_POST['txtAdminUser']."&password=".$_POST['txtPasswd'];
            curl_setopt($cHandle, CURLOPT_POSTFIELDS,  $data);
            curl_setopt($cHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cHandle, CURLOPT_SSL_VERIFYSTATUS, false);
            curl_setopt($cHandle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($cHandle, CURLOPT_HTTPHEADER, [
              'x-api-version: 1.0-rev2',
              'Accept: application/json',
              'Content-Type: application/x-www-form-urlencoded'
            ]);

            $response = curl_exec($cHandle);
            if (curl_errno($cHandle) != 0) {
                $_SESSION['authError'] = "Connection failure - curl error!";
                ob_flush();
                header("Location: ./index.php");
                exit(1);
            }
            else {
                $rval = json_decode($response);
                //check err response
                if (curl_getinfo($cHandle, CURLINFO_HTTP_CODE) == 200) {
                    $_SESSION['authToken'] = $rval->{"access_token"};
                    $_SESSION['authError'] = "";
                    $_SESSION['vbrServer'] = $_POST['txtVBRServer'];
                    ob_flush();
                    header("Location: ./vbrDashboard.php");
                }
                else {
                    $_SESSION['authError'] = $rval->{"errorCode"}.' : '.$rval->{"message"};
                    echo $rval->{"errorCode"}.' : '.$rval->{"message"};
                    ob_flush();
                    header("Location: ./index.php");
                    exit(1);
                }
            }
            curl_close($cHandle);
            exit(0);
        }
        else {
            $_SESSION['authError'] = "Connection failure - curl init error!";
            ob_flush();
            header("Location: ./index.php");
            exit(1);
        }
    }
    catch (Exception $err) {
        $_SESSION['authError'] = "Connection failure - connection/authentication exception!";
        ob_flush();
        header("Location: ./index.php");
        exit(1);
    }
?>