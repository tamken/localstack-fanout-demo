import os
import time
import json
import boto3
from dotenv import load_dotenv

load_dotenv()


boto3.setup_default_session(profile_name=os.environ.get("AWS_PROFILE"))
sqs = boto3.resource(
    "sqs",
    region_name=os.environ.get("AWS_REGION"),
    endpoint_url=os.environ.get("AWS_ENDPOINT"),
)
queue_name = os.environ.get("AWS_QUEUE")
try:
    queue = sqs.get_queue_by_name(QueueName=queue_name)
    print("+++ boot python consumer.\n")
except Exception as e:
    params = {
        "message": str(e),
        "queue": queue_name,
    }
    print("localstackが起動していないか、キューが存在していないみたいです. {}".format(json.dumps(params)))
    exit(1)


def consume(body: str):
    print("python has received sqs message. : {}".format(body))


if __name__ == "__main__":
    while True:
        messages = queue.receive_messages()
        for message in messages:
            consume(message.body)
            message.delete()
        time.sleep(1)
