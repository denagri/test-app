# laravel-docker-template
# 環境構築
Dockerビルド
```
git@github.com:denagri/test-app.git
docker-compose up -d --build
```
laravel環境構築(上から順に構築していく)
```
docker-compose exec php bash
composer install
cp .env.example .env
```
.envの環境変数を変更
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
```
php artisan key:generate
php artisan migrate
php artisan db:seed
```
# 使用技術(実行環境)
* php 8.1.34
* laravel 8.83.8
* mysql 11.8.3

# ER図
![alt text](<スクリーンショット 2026-04-02 201411.png>)

# URL
* 商品一覧画面
http://localhost/
* 商品一覧画面_マイリスト
http://localhost/?tab=mylist
* 会員登録画面
http://localhost/register
* ログイン画面
http://localhost/login
* 商品詳細画面
http://localhost/item/{item_id}
* 商品購入画面
http://localhost/purchase/{item_id}
* 送付先住所変更画面
http://localhost/address/{idem_id}
* 商品出品画面
http://localhost/sell
* プロフィール画面
http://localhost/mypage
* プロフィール編集画面
http://localhost/mypage/profile
* プロフィール画面_購入した商品一覧
http://localhost/mypage?page=buy
* プロフィール画面_出品した商品一覧
http://localhost/mypage?page=sell