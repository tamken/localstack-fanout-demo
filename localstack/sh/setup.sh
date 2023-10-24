#!/bin/bash -eu

ENDPOINT_URL=http://localhost:4566
TOPIC_NAME=xxx-topic
QUEUE_NAMES=(
  "do-queue"
  "re-queue"
  "mi-queue"
)

# 1. Topic登録/TopicARN取得
TOPIC_ARN=`aws sns create-topic --name ${TOPIC_NAME} --endpoint-url ${ENDPOINT_URL} | jq -r '.TopicArn'`  

# 2. Queue登録/Topicをサブスクライブ
for queue in ${QUEUE_NAMES[@]}; do
  QUEUE_URL=`aws sqs create-queue --queue-name ${queue} --endpoint-url ${ENDPOINT_URL} | jq -r '.QueueUrl'`
  QUEUE_ARN=`aws sqs get-queue-attributes --queue-url ${QUEUE_URL} --attribute-names All --endpoint-url ${ENDPOINT_URL} | jq -r '.Attributes.QueueArn'`
  aws sns subscribe --topic-arn ${TOPIC_ARN} --protocol sqs --notification-endpoint ${QUEUE_ARN} --attributes '{"RawMessageDelivery":"true"}' --endpoint-url ${ENDPOINT_URL}
done

echo -e "\n### localstack sqs/sns setup done."

