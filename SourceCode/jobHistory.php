<?php
    session_start();
    ob_start();
/* note that for correct Linux operation MUST run a dos2unix convert to prevent issues w/CRLF hosing PHP...ridiculous BTW*/
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Veeam Skunkworks - Job History</title>
    <link rel="stylesheet" href="css/dashboard.css" />

</head>
<body>
    <form name="jobHistoryForm" action="./vbrDashboard.php" autocomplete="on" method="post">

        <div>
            <img src="images/lab.svg" alt="scienceproject" width="150" height="75" class="center-icon" />
            <h2>Veeam Skunkworks</h2>
        </div>
        <div class="grid-login">
            <div class="grid-item-header">
                Job Status
                <?php
                //echo $_GET['jobID'];
                            if (isset($_SESSION['authToken'])) {
                                $url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/jobs/states?idFilter='.$_GET['jobID'];
                                //$url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/jobs/states';

                                $cHandle = curl_init($url);

                                if ($cHandle != false) {
                                    curl_setopt($cHandle, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($cHandle, CURLOPT_HTTPGET, true);

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
                                        if (curl_getinfo($cHandle, CURLINFO_HTTP_CODE) == 200) {
                                            foreach ($rval->{'data'} as $value) {
                                                echo '<div class="grid-item">';
                                                //echo $value->{'name'}.' : '.$value->{'type'}.' : '.$value->{'description'};
                                                echo '<p><b>'.$value->{'name'}.'</b> : <b>Last run : </b> '.$value->{'lastRun'}.'<b> Last result : </b>'.$value->{'lastResult'}.'</p>';
                                                echo '</div>';
                                                }
                                        }
                                    }
                                    curl_close($cHandle);
                                }
                            }
                ?>                 <button type="submit" class="btn btn-primary rounded submit">Back</button>
            </div>
        </div>
    </form>
</body>
</html>
