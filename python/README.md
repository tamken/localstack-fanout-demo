## 概要
- SQSコンシューマのサンプル *python*
    - 受信メッセージ/ペイロードをコンソール出力する.

## 前提など
- ^3.10 で動確済.
- `Poetry` （パッケージマネージャ）を使用してます.
- LocasStack起動していないと動きません.
- `do-queue` キューへのメッセージを受け取ります.

## セットアップ
1. ライブラリインストール
    ```
    poetry install
    ```
1. `.env` 配置（コピー）
    ```
    cp .env.sample .env
    ```

## 動作確認手順
1. LocalStack起動
1. 実行
    ```
    poetry run python sqs_consumer/consumer.py
    ```

## 他
- `Ctrl-c` で停止.
