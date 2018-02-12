<?php 
session_start();
require 'vendor/autoload.php';
use Aws\CloudWatch\CloudWatchClient;
use Aws\ElasticLoadBalancing\ElasticLoadBalancingClient;
$cloudWatch = CloudWatchClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));
$elbClient = ElasticLoadBalancingClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));
$elbResult = $elbClient->describeLoadBalancers(array(
    'LoadBalancerNames' => array('itmo-544-lb-kkumanan'),
    'PageSize' => 400,
));
$instances=$elbResult['LoadBalancerDescriptions'][0]['Instances'];
$ec2Instances = array();
$ec2Instances['Instance'] = $instances;
$finalDataArray = array();
for ($i = 0; $i < count($instances); $i++) {
    $tempVar = $cloudWatch->getMetricStatistics(array(
    'Namespace' => 'AWS/EC2',
    'MetricName' => 'DiskWriteBytes',
    'Dimensions' => array(
        array(
            'Name' => 'InstanceId',
            'Value' => strval ($instances[$i]['InstanceId']),
        ),
    ),
    'StartTime' => '2017-12-01T21:00:00',
    'EndTime' => '2017-12-15T21:00:00',
    'Period' => 3600,
    'Statistics' => array('Maximum'),
));
foreach($ec2Instances['Instance'] as $finalData){
    foreach($tempVar['Datapoints'] as $data){
         $finalData['timestamp'][] = $data['Timestamp'];
         $finalData['maximum'][] = $data['Maximum'];
         }
         $finalDataArray[] = $finalData;         
      }
  }
  echo json_encode($finalDataArray);
 ?>