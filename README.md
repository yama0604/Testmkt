##　プロジェクト名

- coachtech フリマの開発

## プロジェクトの目的

- アイテムの出品と購入を行うためのフリマアプリを開発する

## サービス名

- coachtech フリマ

## 環境構築

- Docker ビルド
- docker-compose up -d --build

- Docker イメージのビルドとコンテナの起動
- docker-compose exec php bash

- composer インストール有無確認
- composer -v

- マイグレーション実行
- php artisan migrate

- シーディング実行
- php artisan db:seed

- シンボリックリンク作成
- php artisan storage:link

- mailhog 起動とアクセス先
- docker-compose up -d mailhog
- http://localhost:8025

## 使用技術

- PHP 8.3.20

- Laravel 8.83.29

- MySQL 9.2.0

## ER 図

- ![Image](https://github.com/user-attachments/assets/9dbd05a9-2cd0-4c02-849a-b322ee34325d)

## URL

- 商品登録ページ: http://localhost/products/register
- 商品一覧ページ: http://localhost/products
