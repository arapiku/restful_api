# 課題１

## 使用した技術要素
- Vagrant 1.9.8
- CentOS 7.3.1
- PHP 7.1.9
- Phalcon DevTools 3.2.3
- Apache 2.4.6
- jQuery 3.1.0
- Bootstrap 3.3.7
- Eclipse Oxygen (4.7.0)

## 全体の設計・構成
|               　　　　　　 | GET                 | POST           | PUT          | DELETE       |
|:-------------------------|:-------------------:|:--------------:|:------------:|:------------:|
| /items                   | ○ 商品の一覧表示      | ○ 商品の新規登録 | ×            | ×            |
| /items/search/{title}    | ○ タイトルで商品検索   | ×              | ×            | ×            |
| /items/{id:[0-9]+}       | ○ 商品のシングルページ | ×              | ○ 商品の更新   | ○ 商品の削除   |

##### 入力値の制限
- 商品タイトル　100文字以内
- 商品説明　500文字以内

## 開発環境のセットアップ手順
- 下記サイトを参考にさせていただきました

  [[LAMP]VagrantでCentOS7+Apache2.4+PHP7.1+MySQL5.7&Phalcon3.1.2環境を構築する](http://qiita.com/shiromegane/items/b782ce64f5c54fd54a60)

#### Vagrant準備
- BOX追加

  ``` コマンド
  vagrant box add CentOS7 https://github.com/holms/vagrant-centos7-box/releases/download/7.1.1503.001/CentOS-7.1.1503-x86_64-netboot.box
  ```

- 初期化

  ``` コマンド
  mkdir /vagrant/CentOS7 && cd $_
  vagrant init CentOS7
  ```

- 設定

  お好みでVagrantfileを編集

  ``` Vagrantfile
  # ホスト名
  config.vm.hostname = "centos7.vm"
  # ネットワーク
  config.vm.network "private_network", ip: "192.168.33.10"
  # ドキュメントルートを同期フォルダにする ※この設定はhttpdのインストールが終わってからやる
  config.vm.synced_folder "/var/www", "/var/www", owner: "apache", group: "apache", mount_options: ["dmode=777", "fmode=777"]
  ```

- 起動

  ``` コマンド
  vagrant up
  ```

#### CentOS7初期設定
- rootユーザーになっておく

  ``` コマンド
  sudo su -
  ```

- アップデート

  ``` コマンド
  yum -y update
  ```

- Vimを入れる
  - インストール

    ``` コマンド
    mkdir /vagrant/CentOS7 && cd $_
    vagrant init CentOS7
    ```

  - エイリアス設定

    ``` コマンド
    echo alias vi='vim' >> /etc/profile
    source /etc/profile
    ```

- Firewall設定
  - Firewallを止める

    ``` コマンド
    systemctl stop firewalld
    ```

  - サービスを無効にする

    ``` コマンド
    systemctl disable firewalld
    ```

- 各種リポジトリ登録
  - EPEL

    ``` コマンド
    yum -y install epel-release
    ```

  - デフォルトでは無効にする

    ``` コマンド
    sed -i -e "s/enabled=1/enabled=0/g" /etc/yum.repos.d/epel.repo
    ```

  - Remi

    ``` コマンド
    yum -y install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
    ```

  - MySQL5.7

    ``` コマンド
    yum -y install http://dev.mysql.com/get/mysql57-community-release-el7-9.noarch.rpm
    ```

  - Nginx

    ``` コマンド
    yum -y install http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm
    ```

- 再起動

  ``` コマンド
  reboot
  ```

#### LAMP+いろいろ環境構築
- httpd
  - インストール

    ``` コマンド
    yum -y install httpd
    ```

  - 初期設定

    ``` コマンド
    vim /etc/httpd/conf/httpd.conf
    ```
    ネットワークマウントやNFSマウントされたドキュメントルートでは、
    静的ファイルへの変更が反映されなかったりするので以下の設定をしておく
    ``` /etc/httpd/httpd.conf
    EnableMMAP off
    EnableSendfile off
    ```

  - 起動

    ``` コマンド
    systemctl start httpd
    ```

  - サービス登録

    ``` コマンド
    systemctl enable httpd
    ```

- MySQL5.7
  - インストール

    ``` コマンド
    yum -y install mysql-community-server
    ```

  - 起動

    ``` コマンド
    systemctl start mysqld
    ```

  - パスワードの確認

    ``` コマンド
    cat /var/log/mysqld.log | grep "temporary password"
    ```

    ``` /var/log/mysqld.log
    2017-09-06T22:58:22.399219Z 1 [Note] A temporary password is generated for root@localhost: {ここにパスワード}
    ```

  - 初期セットアップ

    ``` コマンド
    mysql_secure_installation
    ```

    ``` mysql_secure_installation
    # パスワード要求
    > Enter current password for root (enter for none):
      {上記にて調べた初期パスワードを入力}

    # パスワードの変更を求められる
    > The existing password for the user account root has expired. Please set a new password.
    > New password:
      {新しいパスワードを入力}
    > Re-enter new password:
      {パスワードを再入力}

    ※この時、ポリシーに反しているとやり直しさせられる。
    ポリシーの初期値はMEDIUMで、長さは8字以上で英字大小/数字/特殊文字(英数以外の記号)を1つ以上ずつ含む必要があるらしい。
    例：P@ssword#123

    # root ユーザーのパスワードを変更するか？
    > Change the password for root ? ((Press y|Y for Yes, any other key for No) :
      {先程変更してるので空Enter}

    # 匿名ユーザーを削除するか？
    > Remove anonymous users? (Press y|Y for Yes, any other key for No) :
      {'y'を入力してEnter}

    # リモートから root ユーザーのログインを禁止するか？
    > Disallow root login remotely? (Press y|Y for Yes, any other key for No) :
      {'y'を入力してEnter}

    # test データベースを削除するか？
    > Remove test database and access to it? (Press y|Y for Yes, any other key for No) :
      {'y'を入力してEnter}

    # 権限テーブルをリロードするか？
    > Reload privilege tables now? (Press y|Y for Yes, any other key for No) :
      {'y'を入力してEnter}
    ```

- PHP7.1
  - 本体インストール

    ``` コマンド
    yum -y install --enablerepo=remi-php71 php
    ```

  - Composer
    - インストール

      ``` コマンド
      cd /tmp && curl -s http://getcomposer.org/installer | php
      ```

    - コマンド化

      ``` コマンド
      mv /tmp/composer.phar /usr/local/bin/composer
      ```

  - Apache起動

    ``` コマンド
    apachectl start
    ```

- Phalcon3環境構築
  - Zephir
    - インストール

      ``` コマンド
      yum -y install --enablerepo=remi-php71 zephir
      ```

  - Phalcon
    - インストール

      ``` コマンド
      yum -y install --enablerepo=remi-php71 php-phalcon3
      ```

  - Phalcon DevTools
    - インストール

      ``` コマンド
      git clone https://github.com/phalcon/phalcon-devtools.git /usr/local/src/phalcon-devtools
      ```

    - コマンド化

      ``` コマンド
      ln -s /usr/local/src/phalcon-devtools/phalcon.php /usr/bin/phalcon
      chmod ugo+x /usr/bin/phalcon
      ```

- Phalconを動かす
  - Phalcon用にApacheの設定ファイルを作成

    ``` コマンド
    vim /etc/httpd/conf.d/phalcon.conf
    ```

    ``` /etc/httpd/conf.d/phalcon.conf
    <VirtualHost *:80>
        DocumentRoot "/var/www/phalcon/public"
        DirectoryIndex index.php
        ServerName phalcon.vm
        <Directory "/var/www/phalcon/public">
            Options All
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost
    ```

  - Phalcon Devtoolsを使ってプロジェクトを生成

    ``` コマンド
    # ドキュメントルートに移動して作成
    cd /var/www/ && phalcon create-project phalcon
    ```

  - ホストOSのブラウザから見れるようにする
    **※この項目はゲストOSではなくホストOS側での操作**

    - hosts設定

      ``` コマンド
      sudo vim /etc/hosts
      ```

    - 以下の設定を追記

      ``` /etc/hosts
      192.168.33.10 phalcon.vm
      ```
      ブラウザから http://phalcon.vm にアクセスして、以下のような画面が表示されればOK。

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
  curl -i -X GET http://localhost/items/

  ```

- 商品の新規登録

  ```
  curl -i -X POST -d '{"title":"タイトル","description":"説明文","price":12345,"image":"@aaa.png"}' http://localhost/items/
  ```

- タイトルで商品検索

  ```
  curl -i -X GET http://localhost/items/search/タイトル
  ```

- 商品のシングルページ

  ```
  curl -i -X GET http://localhost/items/1
  ```

- 商品の更新

  ```
  curl -i -X PUT -d '{"title":"タイトル更新","description":"説明文更新","price":54321,"image":"@new.png"}' http://localhost/items/1
  ```

- 商品の削除

  ```
  curl -i -X DELETE http://localhost/items/search/1
  ```
