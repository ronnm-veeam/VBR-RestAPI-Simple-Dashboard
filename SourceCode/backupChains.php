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
        <div class="grid-singleCol">
            <div class="grid-item-header">
                Backup Chain(s)
                <?php
                if (isset($_SESSION['authToken'])) {
                    $url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/objectRestorePoints?backupIdFilter='.$_GET['backupID'];

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
                            $backups = array();

                            if (curl_getinfo($cHandle, CURLINFO_HTTP_CODE) == 200) {
                                foreach ($rval->{'data'} as $value) { //sum up the number of restore points for each workload
                                    if ($backups[$value->{'name'}.';'.$value->{'platformName'}] == null) {
                                        $backups[$value->{'name'}.';'.$value->{'platformName'}] = '1;'.$value->{'creationTime'}; //log first backup point instance and last backup time
                                    }
                                    else {
                                        $backupStats = explode(';', $backups[$value->{'name'}.';'.$value->{'platformName'}]);
                                        $backupPoints = intval($backupStats[0]);
                                        $backups[$value->{'name'}.';'.$value->{'platformName'}] =  strval($backupPoints+1).';'.$backupStats[1];
                                    }
                                }
                                foreach (array_keys($backups) as $value){
                                    $backupChain = explode(';', $value);
                                    $backupStats = explode(';', $backups[$value]);
                                    echo '<div class="grid-item">';
                                    echo '<p><b>'.$backupChain[0].'</b> - <b>Platform : </b> '.$backupChain[1].' <b> Restore points : </b>'.$backupStats[0].' <b> Last run : </b>'.$backupStats[1].'</p>';
                                    echo '</div>';
                                }
                            }
                        }
                        curl_close($cHandle);
                    }
                }
                ?><button type="submit" class="btn btn-primary rounded submit">Back</button>
            </div>
        </div>
    </form>
</body>
</html>
