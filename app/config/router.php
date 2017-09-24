<?php

$router = $di->getRouter();

/**
 * 商品情報取得
 * http://items/
 * GETでアクセス
 * ItemsControllerのindexメソッドが実行
 */
$router->addGet(
    '/items/',
    'Items::index'
);

/**
 * 登録
 * http://items/
 * POSTでアクセス
 * ItemsControllerのnewメソッドが実行
 */
$router->addPost(
    '/items/',
    'Items::new'
);

/**
 * 検索結果
 * http://items/search/{title}
 * GETでアクセス（）
 * ItemsControllerのsearchメソッドが実行
 */
$router->addGet(
    '/items/search/{title}',
    'Items::search'
);

/**
 * シングルページ
 * http://items/{id:[0-9]+}
 * GETでアクセス（）
 * ItemsControllerのsingleメソッドが実行
 */
$router->addGet(
    '/items/{id:[0-9]+}',
    'Items::single'
);

/**
 * 更新
 * http://items/{id:[0-9]+}
 * PUTでアクセス
 * ItemsControllerのupdateメソッドが実行
 */
$router->addPut(
    '/items/{id:[0-9]+}',
    'Items::update'
);

/**
 * 削除
 * http://items/{id:[0-9]+}
 * DELETEでアクセス
 * ItemsControllerのdestroyメソッドが実行
 */
$router->addDelete(
    '/items/{id:[0-9]+}',
    'Items::destroy'
);

$router->handle();
