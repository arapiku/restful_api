<?php

$router = $di->getRouter();

/**
 * 商品情報取得
 * http://localhost/items/
 * GETでアクセス
 * ItemsControllerのindexメソッドが実行
 */
$router->addGet(
    '/items/',
    'Items::index'
);

/**
 * 登録
 * http://localhost/items/
 * POSTでアクセス
 * ItemsControllerのnewメソッドが実行
 */
$router->addPost(
    '/items/',
    'Items::new'
    );

/**
 * 検索画面
 * http://localhost/items/search/
 * GETでアクセス
 * ItemsControllerのsearchメソッドが実行
 */
$router->addGet(
    '/items/search',
    'Items::search'
    );

/**
 * 検索結果
 * http://localhost/items/search/{name}
 * GETでアクセス（）
 * ItemsControllerのsearchResultメソッドが実行
 */
$router->addGet(
    '/items/search/{name}',
    'Items::searchResult'
);

/**
 * 更新
 * http://localhost/items/{id:[0-9]+}/
 * PUTでアクセス
 * ItemsControllerのupdateメソッドが実行
 */
$router->add(
    '/items/{id:[0-9]+}',
    'Items::update',
    )->via(
        [
            'PUT'
        ]
);

/**
 * 削除
 * http://localhost/items/{id:[0-9]+}
 * DELETEでアクセス
 * ItemsControllerのdestroyメソッドが実行
 */
$router->add(
    '/items/{id:[0-9]+}',
    'Items::destroy',
    )->via(
        [
            'DELETE'
        ]
);

$router->handle();
