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
 * 検索結果
 * http://localhost/items/search/{title}
 * GETでアクセス（）
 * ItemsControllerのsearchメソッドが実行
 */
$router->addGet(
    '/items/search/{title}',
    'Items::search'
);

/**
 * シングルページ
 * http://localhost/items/{id}
 * GETでアクセス（）
 * ItemsControllerのsingleメソッドが実行
 */
$router->addGet(
    '/items/{id:[0-9]+}',
    'Items::single'
);

/**
 * 更新
 * http://localhost/items/update
 * PUTでアクセス
 * ItemsControllerのupdateメソッドが実行
 */
$router->addPut(
    '/items/update',
    'Items::update'
);

/**
 * 削除
 * http://localhost/items/delete
 * DELETEでアクセス
 * ItemsControllerのdestroyメソッドが実行
 */
$router->addDelete(
    '/items/delete',
    'Items::destroy'
);

$router->handle();
