<?php

require_once $config->application->libraryDir.'plogger.php';

use App\Library\Log\Plogger;

use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Response;

use Phalcon\Logger;

class ItemsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        // 全件検索
        $items = Items::find();
        
        // 検索が成功すれば
        if($items) {
            //json化
            $json = json_encode($items);
            // ログ出力
            $plogger = new Plogger("データ取得成功");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            // レスポンス
            $this->response->setContent($json);
            $this->response->send();
            $this->view->disable();
            
        // 検索が失敗すれば
        } else {
            // ログ出力
            $plogger = new Plogger("データ取得失敗");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            // レスポンス
            $this->response->setStatusCode(404, 'NOT-FOUND');
            $this->view->disable();
        }
        
        return;
    }
    
    public function searchAction($title)
    {
        // 商品タイトル検索
        $items = Items::findByTitle($title);
        
        // 検索結果が1件以上であれば
        if(count($items) != 0) {
            // json化
            $json = json_encode($items);
            // ログ出力
            $plogger = new Plogger("検索結果が".count($items)."件見つかりました");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            // レスポンス
            $this->response->setContent($json);
            $this->response->send();
            $this->view->disable();
        // 検索結果が0件であれば
        } else {
            // ステータスコードを変える
            $this->response->setStatusCode(404, 'NOT-FOUND');
            // ログ出力
            $plogger = new Plogger("検索結果が見つかりませんでした");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            // レスポンス
            $this->view->disable();
        }
            
        return;
    }
    
    public function singleAction($id)
    {
        // id検索
        $items = Items::findById($id);
        
        // 検索結果が見つかれば
        if (count($items) != 0) {
            // json化
            $json = json_encode($items);
            // ログ出力
            $plogger = new Plogger("検索結果が見つかりました");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            // レスポンス
            $this->response->setContent($json);
            $this->response->send();
            $this->view->disable();
        
        // 検索結果が見つからなければ
        } else {
            // ステータスコードを変える
            $this->response->setStatusCode(404, 'NOT-FOUND');
            // ログ出力
            $plogger = new Plogger("検索結果が見つかりませんでした");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            // レスポンス
            $this->view->disable();
        }
        
        return;
    }
    
    public function newAction()
    {
        $items = $this->request->getJsonRawBody();
        echo "作成データ：";
        var_dump($items);
        $status = Items::createItems($items);
        
        // もしinsertionが成功したら
        if ($status->success() == true) {
            // json化
            $json = json_encode($status);
            // ログ出力
            $plogger = new Plogger("データの作成に成功しました");
            $plogger->debug();
            var_dump($plogger);
            // レスポンス
            $this->response->setStatusCode(201, 'Created');
            $items->id = $status->getModel()->id;
            $this->response->setContent($json);
            $this->response->send();
            $this->view->disable();
            
        // insertionが失敗したら
        } else {
            // ログ出力
            $plogger = new Plogger("データの作成に失敗しました");
            $plogger->debug();
            var_dump($plogger);
            // エラーメッセージの用意
            $errors = [];
            foreach($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            // レスポンス
            $this->response->setStatusCode(409, 'Conflict');
            $this->response->setContent(var_dump($errors));
            $this->response->send();
            $this->view->disable();
        }
        
        return;
    }
    
    public function updateAction($id)
    {
        // id検索
        $find_items = Items::findById($id);
        
        // 対象データが存在したら
        if (count($find_items) != 0) {
            
            $items = $this->request->getJsonRawBody();
            echo "更新データ：";
            var_dump($items);
            $status = Items::updateItems($items, $id);
            
            // もしアップデートが成功したら
            if ($status->success() === true) {
                // json化
                $json = json_encode($status);
                // ログ出力
                $plogger = new Plogger("データの更新に成功しました");
                $plogger->debug();
                var_dump($plogger);
                // レスポンス
                $this->response->setStatusCode(201, 'Updated');
                $items->id = $status->getModel()->id;
                $this->response->setContent($json);
                $this->response->send();
                $this->view->disable();
                
            // 失敗したら
            } else {
                // ログ出力
                $plogger = new Plogger("データの更新に失敗しました");
                $plogger->debug();
                var_dump($plogger);
                // エラーメッセージの用意
                $errors = [];
                foreach($status->getMessages() as $message) {
                    $errors[] = $message->getMessage();
                }
                // レスポンス
                $this->response->setStatusCode(409, 'Conflict');
                $this->response->setContent(var_dump($errors));
                $this->response->send();
                $this->view->disable();
            }
            
        // 対象データが存在しなかったら
        } else {
            // ログ出力
            $plogger = new Plogger("対象のデータは存在しませんでした");
            $plogger->debug();
            var_dump($plogger);
            // レスポンス
            $this->response->setStatusCode(404, 'NOT-FOUND');
            $this->view->disable();
        }
        
        return;
    }
    
    public function destroyAction($id)
    {
        // id検索
        $find_items = Items::findById($id);
        
        // 対象データが存在したら
        if (count($find_items) != 0) {
            
            // 対象データを削除
            $status = Items::deleteItems($id);
            
            // もし削除が成功したら
            if ($status->success() === true) {
                // レスポンス
                $this->response->setStatusCode(204, 'No Content');
            } else {
                // ログ出力
                $plogger = new Plogger("データの削除に失敗しました");
                $plogger->debug();
                var_dump($plogger);
                // エラーメッセージの用意
                $errors = [];
                foreach($status->getMessages() as $message) {
                    $errors[] = $message->getMessage();
                }
                // レスポンス
                $this->response->setStatusCode(409, 'Conflict');
                $this->response->setContent(var_dump($errors));
                $this->response->send();
                $this->view->disable();
            }
            
        // 対象データが存在しなかったら
        } else {
            // ログ出力
            $plogger = new Plogger("対象のデータは存在しませんでした");
            $plogger->debug();
            var_dump($plogger);
            // レスポンス
            $this->response->setStatusCode(404, 'NOT-FOUND');
            $this->view->disable();
        }
        
        return;
    }

}

