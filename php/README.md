## 概要
- SQSコンシューマのサンプル *php*
    - 受信メッセージ/ペイロードをコンソール出力する.

## 前提
- ^8.2 で動確済.
- `composer` を使用してます.
- LocasStack起動していないと動きません.
- `mi-queue` キューへのメッセージを受け取ります.

## セットアップ
1. ライブラリインストール
    ```
    composer install
    ```
1. `.env` 配置（コピー）
    ```
    cp .env.sample .env
    ```

## 動作確認手順
1. LocalStack起動
1. 実行
    ```
    php index.php
    ```

## 他
- `Ctrl-c` で停止.
