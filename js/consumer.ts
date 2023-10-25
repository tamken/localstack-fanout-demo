import { SQSClient, ReceiveMessageCommand, DeleteMessageCommand, GetQueueUrlCommand } from "@aws-sdk/client-sqs"
import * as dotenv from 'dotenv'

dotenv.config()

const options = {
  profile: process.env.AWS_PROFILE,
  region: process.env.AWS_REGION,
  endpoint: process.env.AWS_ENDPOINT_URL,
}
const queue = process.env.AWS_QUEUE || "none"

const client = new SQSClient(options)

const getQueueUrl = (queue: string) =>
  client.send(
    new GetQueueUrlCommand({
      QueueName: queue
    })
  )

const receiveMessage = (queueUrl: string) =>
  client.send(
    new ReceiveMessageCommand({
      MaxNumberOfMessages: 1,
      MessageAttributeNames: ["All"],
      QueueUrl: queueUrl
    })
  )

const sleep = (msec: number) => new Promise(resolve => {
  setTimeout(resolve, msec)
})

export const consume = async (queue: string) => {
  let queue_url: any = "none"
  try {
    queue_url = await (await getQueueUrl(queue)).QueueUrl
    console.log("+++ boot js/ts consumer.\n")
  } catch (e: any) {
    const param = {
      "message": e.message,
      "queue": queue,
    }
    console.log("localstackが起動していないか、キューが存在していないみたいです. " + JSON.stringify(param))
    process.exit(1)
  }
  while (true) {
    const { Messages } = await receiveMessage(queue_url);
    if (Messages) {
      console.log("Js/Ts has received sqs message. : " + Messages[0].Body)
      await client.send(
        new DeleteMessageCommand({
          QueueUrl: queue_url,
          ReceiptHandle: Messages[0].ReceiptHandle
        })
      )
    }
    await sleep(1000);
  }
}
consume(queue)
