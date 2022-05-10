<?php
    session_start();
    ob_start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Veeam Skunkworks - Dashboard</title>
        <link rel="stylesheet" href="css/dashboard.css" />
    </head>
    <body>
        <form  name="vbrDashboard" action="./logoff.php" autocomplete="on" method="post">
            <div>
                <img src="images/lab.svg" alt="scienceproject" width="150" height="75" class="center-icon" />
                <h2>VBR Dashboard</h2>
            </div>

            <div class="grid-container">
                <div class="grid-item-header">Managed Servers
                        <?php
                            if (isset($_SESSION['authToken'])) {
                                $url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/backupInfrastructure/managedServers';

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
                                                echo '<p><b>'.$value->{'name'}.'</b> : '.$value->{'type'}.' : '.$value->{'description'}.'</p>';
                                                echo '</div>';
                                                }
                                        }
                                    }
                                    curl_close($cHandle);
                                }
                            }
                        ?>
                </div>
                <div class="grid-item-header">Repositories
                    <?php
                        if (isset($_SESSION['authToken'])) {
                            $url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/backupInfrastructure/repositories';

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
                                            echo '<p><b>'.$value->{'name'}.'</b> : '.$value->{'type'}.' : '.$value->{'description'}.'</p>';
                                            echo '</div>';
                                            }
                                    }
                                }
                                curl_close($cHandle);
                            }
                        }
                    ?>
                    <div class="grid-item-header">
                        SOBR's
                        <?php
                        if (isset($_SESSION['authToken'])) {
                            $url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/backupInfrastructure/scaleOutRepositories';

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
                                            echo '<p><b>'.$value->{'name'}.'</b></p>';
                                            $perfTier = $value->{'performanceTier'};
                                            $capTier = $value->{'capacityTier'};
                                            $archTier = $value->{'archiveTier'};
                                            foreach ($perfTier->{'performanceExtents'} as $value) {
                                                echo '<p style = "margin-left: 40px"><b>'.$value->{'name'}.'</b> : Status - '.$value->{'status'}.'</p>';
                                            }
                                            if ($capTier->{'enabled'}) {
                                                echo '<p style = "margin-left: 40px">Object offload enabled</p>'; //.$capTier->{'extentId'}.'</p>';
                                            }
                                            else {
                                                echo '<p style = "margin-left: 40px">Object offload disabled</p>'; //.$capTier->{'extentId'}.'</p>';
                                            }
                                            if ($archTier->{'enabled'}) {
                                                echo '<p style = "margin-left: 40px">Object archive offload enabled</p>'; //.$capTier->{'extentId'}.'</p>';
                                            }
                                            else {
                                                echo '<p style = "margin-left: 40px">Object archive offload disabled</p>'; //.$capTier->{'extentId'}.'</p>';
                                            }
                                            echo '</div>';
                                        }
                                    }
                                }
                                curl_close($cHandle);
                            }
                        }
                        ?>
                        </div>
                    </div>
                <div class="grid-item-header">Backup Jobs
                    <?php
                        if (isset($_SESSION['authToken'])) {
                            $url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/jobs';

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
                                            //echo '<p><b>'.$value->{'name'}.'</b> : '.$value->{'type'}.' : '.$value->{'description'}.'</p>';
                                            echo "<a href=\"jobHistory.php?jobID=" . $value->{'id'}."\"><p><b>".$value->{'name'}.'</b> : '.$value->{'type'}.' : '.$value->{'description'}.'</p></a>';
                                            //echo "<a href=\"jobHistory.php?" . $value->{'id'}."\">xx";
                                                 #echo "<td><a href=\"../Subscriptions/" . $custid . "/" . $row[0] . "/" . $msi . "\">Download</a></td>";
                                            echo '</div>';
                                            }
                                    }
                                }
                                curl_close($cHandle);
                            }
                        }
                    ?>
                </div>
                <div class="grid-item-header">Backups
                    <?php
                        if (isset($_SESSION['authToken'])) {
                            $url = 'https://'.$_SESSION['vbrServer'].':9419/api/v1/backups';

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
                                            echo "<a href=\"backupChains.php?backupID=".$value->{'id'}."\"><p><b>".$value->{'name'}.'</b> : '.$value->{'creationTime'}.'</p></a>';
                                            //echo '<p><b>'.$value->{'name'}.'</b> : '.$value->{'creationTime'}.'</p>';
                                            echo '</div>';
                                            }
                                    }
                                }
                                curl_close($cHandle);
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="grid-login">
                <input name="Logoff" type="submit" value="Logoff"/>
            </div>
    </form>     
 </body>
<?php
    ob_flush();
?>
</html>
