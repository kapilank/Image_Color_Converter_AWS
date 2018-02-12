<?php
session_start();
require 'vendor/autoload.php';
$cloudWatch = new Aws\CloudWatch\CloudWatchClient([
'version' => 'latest',
'region'  => 'us-west-2'
]);
$sqsMetricStatistics = $cloudWatch->getMetricStatistics([
    'Namespace' => 'AWS/SQS',
    'MetricName' => 'NumberOfMessagesSent',
    'Dimensions' => [
        [
            'Name' => 'QueueName',
            'Value' => 'kkumanan-sqs-queue',
        ],
    ],
    'StartTime' => '2017-12-01T21:00:00',
    'EndTime' => '2017-12-15T21:00:00',
    'Period' => 3600,
    'Statistics' => ['Sum'],
]);
$dataArray = array();
foreach($sqsMetricStatistics['Datapoints'] as $data){
    $dataArray['timestamp'][] = $data['Timestamp'];
    $dataArray['sum'][] = $data['Sum'];
}
echo json_encode($dataArray);
 ?>
