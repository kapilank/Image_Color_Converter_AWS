#!/bin/bash

load_balancer_name=`aws elb describe-load-balancers --query 'LoadBalancerDescriptions[*].LoadBalancerName'`

launch_config_name=`aws autoscaling describe-launch-configurations --query 'LaunchConfigurations[].LaunchConfigurationName'`

auto_scaling_name=`aws autoscaling describe-auto-scaling-groups --query 'AutoScalingGroups[*].AutoScalingGroupName'`

instance_id_running=`aws ec2 describe-instances --filters "Name=instance-state-code,Values=16" --query 'Reservations[*].Instances[].InstanceId'`

db_instance_id=`aws rds describe-db-instances --query 'DBInstances[*].DBInstanceIdentifier'`

aws autoscaling detach-load-balancers --load-balancer-names $load_balancer_name --auto-scaling-group-name $auto_scaling_name;

aws autoscaling update-auto-scaling-group --auto-scaling-group-name $auto_scaling_name --min-size 0;

instanceattachedtoautoscaling=`aws autoscaling describe-auto-scaling-groups --query 'AutoScalingGroups[*].Instances[].InstanceId'`;

aws autoscaling detach-instances --instance-ids $instanceattachedtoautoscaling --auto-scaling-group-name $auto_scaling_name --should-decrement-desired-capacity;

aws autoscaling set-desired-capacity --auto-scaling-group-name $auto_scaling_name --desired-capacity 0;

aws ec2 terminate-instances --instance-ids $instanceattachedtoautoscaling;

aws ec2 wait instance-terminated --instance-ids $instanceattachedtoautoscaling;

aws elb delete-load-balancer --load-balancer-name $load_balancer_name;

aws autoscaling delete-auto-scaling-group --auto-scaling-group-name $auto_scaling_name;

aws autoscaling delete-launch-configuration --launch-configuration-name $launch_config_name;

aws ec2 terminate-instances --instance-ids $instance_id_running;

aws ec2 wait instance-terminated --instance-ids $instance_id_running;

aws rds delete-db-instance --skip-final-snapshot --db-instance-identifier itmo-544-kkumanan-dbreplica

aws rds wait db-instance-deleted --db-instance-identifier itmo-544-kkumanan-dbreplica

aws rds delete-db-instance --skip-final-snapshot --db-instance-identifier itmo544-kkumanan-mysqldb

aws rds wait db-instance-deleted --db-instance-identifier itmo544-kkumanan-mysqldb

BucketNames=`aws s3api list-buckets --query 'Buckets[].Name'`

arrayOfBucketname=($BucketNames)
for Bucketname in "${arrayOfBucketname[@]}";
do
        aws s3 rb s3://$Bucketname --force
done

queueToBeDeleted=`aws sqs list-queues`

queueToBeDeletedArray=($queueToBeDeleted)

for queueArrayIterator in "${queueToBeDeletedArray[@]}";
do
if [ $queueArrayIterator == "QUEUEURLS" ]
then
echo ""
else
aws sqs delete-queue --queue-url $queueArrayIterator
fi
done

snsTopics=`aws sns list-topics`

arrayOfSNSTopics=($snsTopics)

for arrayOfSNSTopicsIterator in "${arrayOfSNSTopics[@]}";
do
if [ $arrayOfSNSTopicsIterator == "TOPICS" ]
then
echo ""
else
aws sns delete-topic --topic-arn $arrayOfSNSTopicsIterator
fi
done
