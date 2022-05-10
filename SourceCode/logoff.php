<?php
    session_start();

    if (isset($_SESSION["authToken"])) {
        $url = 'https://'.$_SESSION['vbrServer'].':9419/api/oauth2/logout';

        $cHandle = curl_init($url);

        if ($cHandle != false) {
            curl_setopt($cHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cHandle, CURLOPT_POST, true);

            curl_setopt($cHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cHandle, CURLOPT_SSL_VERIFYSTATUS, false);
            curl_setopt($cHandle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($cHandle, CURLOPT_HTTPHEADER, [
              'x-api-version: 1.0-rev2',
              'Accept: application/json',
              'Content-Type: application/x-www-form-urlencoded',
              'Authorization: Bearer '.$_SESSION['authToken']
            ]);

            $response = curl_exec($cHandle);

            if (curl_errno($cHandle) == 0) {
                $rval = json_decode($response);
                //check err response
                if (curl_getinfo($cHandle, CURLINFO_HTTP_CODE) == 200) {
                    $_SESSION['authToken'] = null;
                    $_SESSION['authError'] = null;
                    $_SESSION['vbrServer'] = null;
                    header("Location: ./index.php");
                }
            }
            curl_close($cHandle);
            session_destroy();
            exit(0);
        }
    }
?>