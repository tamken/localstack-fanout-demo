# localstack-fanout-demo
- SQSコンシューマのデモです.
    - 下記3言語のデモ.
        - python
        - js/ts
        - php
    - 処理内容
        - SQSキューを刈り取りメッセージ/ペイロードをコンソール出力. ただそれだけですmm
## 動作前提事項
- Mac.
- 下記ソフトがインストールされていること.
    - docker / docker-compose
    - awscli
        - LocalStack用のプロファイル追加
        ```
        aws configure --profile localstack
        > AWS Access Key ID [None]: dummy
        > AWS Secret Access Key [None]: dummy
        > Default region name [None]: ap-northeast-1
        > Default output format [None]: json
        ```
    - jq
- Topic / Queu は下記名前で作成. また各QueueはTopicへサブスクライブしてます.
    - Topic
        - `xxx-topic`
    - Queue
        - `do-queue`
        - `re-queue`
        - `mi-queue`
    - 補足
        - メッセージ配信はrawメッセージ送信設定してます.
            - 属性 `RawMessageDelivery` を 有効 （`true`） 設定
- 各言語（リンク先に記載してます）
    - [python](./python/README.md)
    - [js/ts](./js/README.md)
    - [php](./php/README.md)
## 動作確認手順
1. LocalStack起動.
    ```
    ./localstack/boot.sh
    ```
1. 各言語のpg起動.（↑リンク参照）
    - 3言語全て起動する必要なしです.
1. SNS Topic の TopicARN を確認.（awscli で確認する）
    ```
    asw sns list-topics --endpoint-url http://localhost:4566

    {
        "Topics": [
            {
                "TopicArn": "arn:aws:sns:ap-northeast-1:000000000000:xxx-topic"
            }
        ]
    }
    ```
1. SNS publishを実行.
    ```
    aws sns publish --topic-arn arn:aws:sns:ap-northeast-1:000000000000:xxx-topic --message '{"sampleKey": "sampleValue"}' --endpoint-url http://localhost:4566
    ```
    - publish 実施後、各言語実行時のコンソールを確認（以下のようにメッセージが出力される）
        - python
            ```
            python has received sqs message. : {"sampleKey": "sampleValue"}
            ```
        - js/ts
            ```
            Js/Ts has received sqs message. :{"sampleKey": "sampleValue"}
            ```
        - php
            ```
            php has received sqs message. {"sampleKey": "sampleValue"}
            ```
    - `--message` 箇所で送信メッセージ設定.

## 他
- 動確終了時は以下実行
    ```
    ./localstack/stop.sh
    ```
