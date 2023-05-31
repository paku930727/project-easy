Laravel 10のDocker環境作成 (PHP 8.1, Apache, MariaDB, Xdebug)

このReadmeでは、LaravelのDocker環境を手軽に作成する手順を紹介します。以下のバージョンを使用します:

    Laravel 10.0.0 (サーバー要件: PHP 8.1以上)
    手元の作業PC: Apple M1 Pro
    Docker: 20.10.21
    イメージ: php:8.1-apache
    イメージ: mariadb:10.3
    イメージ: phpmyadmin:latest
    イメージ: mailhog/mailhog:latest
    PHP: 8.1
    DB: MariaDB 10.3
    Docker-compose: 2.13.0

＊ゴール

http://localhost でデフォルトのトップページ（welcome.blade.php）の内容が表示されること

ディレクトリ構成

plaintext

プロジェクトルート
├── .vscode
│    └── launch.json (VSCode エディタのデバッガー設定)
├── www
│    └── [Laravel関連のファイル群]
├── docker-compose.yml
└── docker
    ├── app
    │    ├── apache2
    │    │    ├── sites-available
    │    │    │   └── 000-default.conf
    │    │    └── apache2.conf
    │    ├── php.ini
    │    └── Dockerfile
    ├── msmtp
    │    └── msmtprc (メール送信のSMTP設定用)
    └── mysql
         ├── initdb (SQLの初期化用)
         ├── storage (データのマウント用)
         ├── Dockerfile
         └── server.cnf


・手順

以下の手順に従って進めてください。

1. Laravelをwwwディレクトリ以下にインストールします。

shell

$ docker-compose build
$ docker-compose run --rm app composer create-project laravel/laravel:^10.0 .

2. .envファイルを更新します。DBとSMTPの接続情報の環境変数を更新します。

.envファイルの例:

APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_db_name
DB_USERNAME=
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"


3. タイムゾーンとロケールを日本に変更します。

// www/config/app.php

'timezone' => 'Asia/Tokyo',
'locale' => 'ja',

4. ドキュメントルートをデフォルトのpublicからhtmlに変更します。

shell

$ mv www/public www/html


5. public_path()を上書きします。

www/bootstrap/app.phpファイルに以下のコードを追記します。

php

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/
// public_path()の設定 "/var/www/public" (デフォルト) => "/var/www/html"
$app->usePublicPath(base_path('html'));

これにより、public_path()関数が更新されます。

6. コンテナを起動します。

shell

$ docker-compose up -d

7. その他の初期セットアップを行います。

$ docker-compose exec app bash
# php artisan key:generate
# php artisan storage:link
# chmod -R 777 storage bootstrap/cache

8. ブラウザで以下の確認を行います。
確認1: http://localhost でデフォルトのトップページ（welcome.blade.php）が表示されること
確認2: phpmyadminにアクセスし、ログインできること
確認3: メール送信テストを行います。appコンテナにアクセスし、以下のスクリプトを実行します。

$ docker-compose exec app bash
root@container_id:/var/www# php -r "mail('test@example.com', 'テストタイトル', 'テスト本文', 'From: from@example.com');"


これで、Laravel 10のDocker環境が作成されました。お疲れ様でした！