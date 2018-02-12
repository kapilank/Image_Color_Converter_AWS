#!/bin/bash

aws elb create-load-balancer --load-balancer-name itmo-544-lb-kkumanan --listeners Protocol=Http,LoadBalancerPort=80,InstanceProtocol=Http,InstancePort=80 --availability-zones us-west-2b --security-groups $3

aws autoscaling create-launch-configuration --launch-configuration-name $4 --image-id $1  --key-name $2 --instance-type t2.micro --user-data file://install-app-env.sh --security-groups $3 --iam-instance-profile $5 --instance-monitoring Enabled=true 

aws autoscaling create-auto-scaling-group --auto-scaling-group-name itmo-544-autoscaling-kkumanan --launch-configuration-name $4 --availability-zones us-west-2b --min-size 0 --max-size 10 --desired-capacity 3

aws autoscaling attach-load-balancers --load-balancer-names itmo-544-lb-kkumanan --auto-scaling-group-name itmo-544-autoscaling-kkumanan

aws rds create-db-instance --db-instance-identifier itmo544-kkumanan-mysqldb --allocated-storage 5 --db-instance-class db.t2.micro --engine mysql --master-username controller --master-user-password controllerpass  --availability-zone us-west-2b --db-name itmo544mp

db_instance_id=`aws rds describe-db-instances --query 'DBInstances[*].DBInstanceIdentifier'`

aws elb create-lb-cookie-stickiness-policy --load-balancer-name itmo-544-lb-kkumanan --policy-name my-duration-cookie-policy --cookie-expiration-period 60

aws elb set-load-balancer-policies-of-listener --load-balancer-name itmo-544-lb-kkumanan --load-balancer-port 80 --policy-names my-duration-cookie-policy

aws rds wait db-instance-available --db-instance-identifier $db_instance_id

db_instance_url=`aws rds describe-db-instances --query 'DBInstances[*].Endpoint[].Address'`
mysql --host=$db_instance_url --user='controller' --password='controllerpass' itmo544mp << EOF
CREATE TABLE records(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,email VARCHAR(255),phone VARCHAR(255),s3_raw_url VARCHAR(255),s3_finished_url VARCHAR(255),status INT(1),receipt BIGINT);
commit;
EOF

aws rds create-db-instance-read-replica --db-instance-identifier itmo-544-kkumanan-dbreplica --source-db-instance-identifier itmo544-kkumanan-mysqldb --db-instance-class db.t2.micro --availability-zone us-west-2b

aws rds wait db-instance-available --db-instance-identifier itmo-544-kkumanan-dbreplica

aws s3api create-bucket --bucket $6 --region us-west-2 --create-bucket-configuration LocationConstraint=us-west-2

aws s3api create-bucket --bucket $7 --region us-west-2 --create-bucket-configuration LocationConstraint=us-west-2

aws s3api wait bucket-exists --bucket $6

aws s3api wait bucket-exists --bucket $7

topic_arn_name=`aws sns create-topic --name kkumanan--sns-topic`

aws sns subscribe --topic-arn $topic_arn_name --protocol sms --notification-endpoint $8

aws sqs create-queue --queue-name kkumanan-sqs-queue

aws ec2 run-instances --image-id $1 --key-name $2 --security-group-ids $3 --instance-type t2.micro --user-data file://processor.sh --placement AvailabilityZone=us-west-2b --iam-instance-profile Name="$5"

echo "Fully Completed  "
