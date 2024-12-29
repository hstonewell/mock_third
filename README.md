# COACHTECHフリマ
フリーマーケットアプリです。

![alt](rese.png)

## 作成した目的
勉強中のフレームワーク（Laravel）のアウトプット

## 機能一覧
### 一般ユーザ
- 会員登録・ログイン：新規ユーザの登録とログイン
- 商品出品：ブランド名・ジャンル・商品状態などを登録可能。商品単価は300円以上
- 商品検索：商品名から検索が可能
- お気に入り機能：商品をお気に入りに追加・管理
- コメント機能：商品に対してのコメント追加・自分のコメントの削除
- プロフィール編集：ユーザ名、アイコンと、住所を変更できます。
### 管理ユーザ
- ユーザの削除：一般ユーザの削除
- コメント削除：一般ユーザのコメント削除

## 使用技術
- HTML/CSS
- PHP8.2
- Laravel11.33.2
- MySQL8.3.0

## テーブル設計
![alt](table.png)

## ER図
![alt](er.png)

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:hstonewell/mock_second.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret_key
CASHIER_CURRENCY=jpy
CASHIER_CURRENCY_LOCALE=ja_JP
CASHIER_LOGGER=daily

ADMIN_PASSWORD=Your-Pass1234
```
※STRIPE_KEYおよびSTRIPE_SECRETにはStripeのダッシュボードより取得した公開キーとシークレットキーを入力してください。
https://dashboard.stripe.com/dashboard

5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

8. シンボリックリンクの作成
``` bash
php artisan storage:link
```

## 管理ユーザのログイン方法
シーディングを行なった時点で自動的に管理者ユーザが作成されます。

### デフォルト値
- メールアドレス：adminuser@testuser.com
- パスワード：Your-Pass1234

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
