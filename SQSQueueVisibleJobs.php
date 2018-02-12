<?php
session_start();
require 'vendor/autoload.php';
$cloudWatch = new Aws\CloudWatch\CloudWatchClient([
'version' => 'latest',
'region'  => 'us-west-2'
]);
$sqsVisibleMessagesMetricStatistics = $cloudWatch->getMetricStatistics([
    'Namespace' => 'AWS/SQS',
    'MetricName' => 'ApproximateNumberOfMessagesVisible',
    'Dimensions' => [
        [
            'Name' => 'QueueName',
            'Value' => 'kkumanan-sqs-queue',
        ],
    ],
    'StartTime' => '2017-12-01T21:00:00',
    'EndTime' => '2017-12-15T21:00:00',
    'Period' => 3600,
    'Statistics' => ['Maximum'],
]);
$dataArray = array();
foreach($sqsVisibleMessagesMetricStatistics['Datapoints'] as $data){
    $dataArray['timestamp'][] = $data['Timestamp'];
    $dataArray['maximum'][] = $data['Maximum'];
}
echo json_encode($dataArray);
 ?>
