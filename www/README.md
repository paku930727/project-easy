Laravel 10のDocker環境作成 (PHP8.1, Apache, MariaDB, Xdebug)

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

ゴール

    http://localhost でデフォルトのトップページ(welcome.blade.php)の内容が表示されること

ディレクトリ構成

scss

プロジェクトルート
├── .vscode
│    └── launch.json (VSCode エディタのデバッガー設定)
├── www
│    └── [laravel関連のもの]
├── docker-compose.yml
└── docker
    ├── app
    │    ├── apache2
    │    │    ├── sites-available
    │    │    │   └── 000-default.conf
    │    │    └ apache2.conf
    │    ├── php.ini
    │    └── Dockerfile
    ├── msmtp
    │    └── msmtprc (メール送信のSMTP設定用)
    └── mysql
         ├── initdb (SQLの初期化用)
         ├── storage (データのマウント用)
         ├── Dockerfile
         └── server.cnf

手順

以下の手順に従って進めてください。

    Laravelをwwwディレクトリ以下にインストールします。

shell

$ docker-compose build
$ docker-compose run --rm app composer create-project laravel/laravel:^10.0 .

    .envファイルを更新します。DBとSMTPの接続情報の環境変数を更新します。

makefile

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=test_db_name
DB_USERNAME=test_user
DB_PASSWORD=test_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

    タイムゾーンとロケールを日本に変更します。

php

// www/config/app.php

'timezone' => 'Asia/Tokyo',
'locale' => 'ja',

    ドキュメントルートをデフォルトのpublicからhtmlに変更します。

shell

$ mv www/public www/html

    public_path()を上書きします。

www/bootstrap/app.phpファイルに以下のコードを追記します

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
// public_path()の設定 "/var/www/public"(デフォルト) => "/var/www/html"
$app->usePublicPath(base_path('html'));

これにより、public_path()関数が更新されます。

    コンテナを起動します。

shell

$ docker-compose up -d

    その他の初期セットアップを行います。

shell

$ docker-compose exec app bash
# php artisan key:generate
# php artisan storage:link
(# ln -sf /var/www/storage/app/public /var/www/html/storage でもOK)
# chmod -R 777 storage bootstrap/cache

    ブラウザで以下の確認を行います。

    確認1: http://localhost でデフォルトのトップページ(welcome.blade.php)が表示されること
    確認2: phpmyadminにアクセスし、ログインできること
    確認3: メール送信テストを行います。appコンテナにアクセスし、以下のスクリプトを実行します。

shell

$ docker-compose exec app bash
root@f761b2f53458:/var/www# php -r "mail('test@example.com', 'テストタイトル', 'テスト本文', 'From: from@example.com');";

メールの確認は、http://localhost:8025/ にアクセスして行います。

    確認4: デバッガーの確認 (VSCodeをエディタで使っている人のみ)
    例として、www/app/Providers/RouteServiceProvider.phpにブレークポイントを設定し、ページをリロードしてみます。ブレークポイントで停止することを確認します。

これで、Laravel 10のDocker環境が作成されました。お疲れ様でした！