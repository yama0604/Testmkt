# COACHTECH フリマ

## プロジェクトの目的

アイテムの出品と購入を行うためのフリマアプリを開発する

## 環境構築

1. DockerDesktop アプリを立ち上げ、Git よりクローンで作成されたフォルダ上で下記コマンドを実行してください

```bash
docker-compose up -d --build
```

2. コンテナを起動させ、下記コマンドから.env を作成してください

```text
docker-compose exec php bash
cp .env.example .env
```

3. .env の以下項目の値を変更要

```text
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
MAIL_FROM_NAME="${APP_NAME}"
MAIL_FROM_ADDRESS=example@coachtech.com
MAIL_FROM_NAME="COACHTECH"
```

4. 必要に応じてコンポーザーをインストールしてください

```text
composer install
composer -v
```

5. アプリケーションキーの作成

```text
php artisan key:generate
php artisan config:clear
php artisan cache:clear
```

6. アプリケーションキーの作成

```bash
php artisan migrate
```

7. シーディングの作成

```bash
php artisan db:seed
```

8. シンボリックリンク作成

```text
php artisan storage:link
exit
```

9. mailhog 起動

```bash
docker-compose up -d mailhog
```

10. Stripe 決済導入

```text
composer require stripe/stripe-php
php artisan config:clear
php artisan cache:clear
```

## 使用技術

- PHP 8.3.20
- Laravel 8.83.29
- MySQL 9.2.0

## ER 図

- ![Image](https://github.com/user-attachments/assets/9dbd05a9-2cd0-4c02-849a-b322ee34325d)

## URL

- ログイン: http://localhost/login
- 会員登録: http://localhost/register
- mailhog（メール認証）: http://localhost:8025
- Stripe（）: https://dashboard.stripe.com/test/apikeys
- Stripe にログイン後、開発者メニューから pk と sk の２種類を取得し、.env の最下部にそのまま追記する
