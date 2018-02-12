<?php
session_start();
require 'vendor/autoload.php';
use Aws\Rds\RdsClient;

$rdsClient = RdsClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-west-2'
    ));

$dbInstances = $rdsClient->describeDBInstances(array(
        'DBInstanceIdentifier' => 'itmo-544-kkumanan-dbreplica',
   ));

foreach ($dbInstances['DBInstances'] as $temp)
   {
     foreach($temp['Endpoint'] as $url)
       {
         $finalUrl=$url;
          break;
       }
   }

$dbConnection = mysqli_connect($finalUrl, "controller","controllerpass","itmo544mp","3306") or die("Error" . mysqli_error($dbConnection));

$sqlSelect = "SELECT id, status from records";

$resultOfSqlSelect = $dbConnection->query($sqlSelect);

    if($resultOfSqlSelect -> num_rows > 0)
    {
        while($rowData = $resultOfSqlSelect-> fetch_assoc())
        {
            $data[] = $rowData;
        }
    }
    $dbConnection->close();
    print json_encode($data);
 ?>
