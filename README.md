# 課題１

## 使用した技術要素
- Vagrant 1.9.8
- PHP 7.1.9
- Phalcon DevTools 3.2.3
- jQuery 3.1.0
- Bootstrap 3.3.7
- Eclipse Oxygen (4.7.0)

## 全体の設計・構成
- /localhost/items
  - GET
  	- 商品の一覧表示
  - POST
  	- 商品の新規登録
- /localhost/items/search/{title}
  - GET
  	- タイトルで商品検索
- /localhost/items/{id:[0-9]+}
  - GET
  	- 商品のシングルページ
  - PUT
  	- 商品の更新
  - DELETE
  	- 商品の削除

## 開発環境のセットアップ手順
- 下記サイトを参考にさせていただきました（長いので省略）

  [[LAMP]VagrantでCentOS7+Apache2.4+PHP7.1+MySQL5.7&Phalcon3.1.2環境を構築する](http://qiita.com/shiromegane/items/b782ce64f5c54fd54a60)
- Eclipseに Remote Systems Explorer をインストール
- Eclipseから開発環境にssh接続

## ディレクトリ構成
  ```
  .
  ├── app/ .. コアコード
  	   ├── config/ .. 設定ファイル
  	   ├── controllers/ .. コントローラーファイル
  	   ├── migrations/ .. マイグレーションファイル
  	   ├── models/ .. モデルファイル
  	   └── views/ .. ビューファイル
  └── public/ .. index.php
  
  ```

## テスト実行手順
APIをcurlコマンドで叩く

- 商品の一覧表示

  ```  
  curl -i -X GET http://localhost/restful_api/items/
  
  ```
  
- 商品の新規登録

  ```
  curl -i -X POST -d '{"title":"タイトル","description":"説明文","price":12345,"image":"@aaa.png"}' http://localhost/restful_api/items/
  ```
  
- タイトルで商品検索

  ```
  curl -i -X GET http://localhost/restful_api/items/search/タイトル
  ```

- 商品のシングルページ

  ```
  curl -i -X GET http://localhost/restful_api/items/1
  ```

- 商品の更新

  ```
  curl -i -X PUT -d '{"title":"タイトル更新","description":"説明文更新","price":54321,"image":"@new.png"}' http://localhost/restful_api/items/1
  ```

- 商品の削除

  ```
  curl -i -X DELETE http://localhost/restful_api/items/search/1
  ```
