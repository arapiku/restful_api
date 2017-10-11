<?php

require_once $config->application->libraryDir.'plogger.php';

// use App\Library\Log\Plogger;

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
            Plogger::debug("データ取得成功");
            // レスポンス
            $this->response->setContent($json);
            $this->response->setContentType("application/json", "utf-8");
            $this->response->send();
            $this->view->disable();
            
        // 検索が失敗すれば
        } else {
            // ログ出力
            Plogger::error("データ取得失敗");
            // レスポンス
            $this->response->setStatusCode(404, 'NOT-FOUND');
            $this->response->setContentType("application/json", "utf-8");
            $this->view->disable();
        }
        
        return;
    }
    
    public function searchAction($title)
    {
        // 商品タイトル検索
        $items = Items::findByTitle($title);
        
        // 検索結果が1件以上であれば
        if (count($items) != 0) {
            // json化
            $json = json_encode($items);
            // ログ出力
            Plogger::debug("検索結果が".count($items)."件見つかりました");
            // レスポンス
            $this->response->setContent($json);
            $this->response->setContentType("application/json", "utf-8");
            $this->response->send();
            $this->view->disable();
        // 検索結果が0件であれば
        } else {
            // ステータスコードを変える
            $this->response->setStatusCode(404, 'NOT-FOUND');
            // ログ出力
            Plogger::error("検索結果が見つかりませんでした");
            // レスポンス
            $this->response->setContentType("application/json", "utf-8");
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
            Plogger::debug("検索結果が見つかりました");
            // レスポンス
            $this->response->setContent($json);
            $this->response->setContentType("application/json", "utf-8");
            $this->response->send();
            $this->view->disable();
        
        // 検索結果が見つからなければ
        } else {
            // ステータスコードを変える
            $this->response->setStatusCode(404, 'NOT-FOUND');
            // ログ出力
            Plogger::error("検索結果が見つかりませんでした");
            // レスポンス
            $this->response->setContentType("application/json", "utf-8");
            $this->view->disable();
        }
        
        return;
    }
    
    public function newAction()
    {
        $items = $this->request->getJsonRawBody();
        var_dump($items);
        $status = Items::createItems($items);
        
        // もしinsertionが成功したら
        if ($status->success() == true) {
            // json化
            $json = json_encode($status);
            // ログ出力
            Plogger::debug("データの作成に成功しました");
            // レスポンス
            $this->response->setStatusCode(201, 'Created');
            $this->response->setContentType("application/json", "utf-8");
            $items->id = $status->getModel()->id;
            $this->response->setContent($json);
            $this->response->send();
            $this->view->disable();
            
        // insertionが失敗したら
        } else {
            // ログ出力
            Plogger::error("データの作成に失敗しました");
            // エラーメッセージの用意
            $errors = [];
            foreach($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            // レスポンス
            $this->response->setStatusCode(409, 'Conflict');
            $this->response->setContent(var_dump($errors));
            $this->response->setContentType("application/json", "utf-8");
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
            var_dump($items);
            $status = Items::updateItems($items, $id);
            
            // もしアップデートが成功したら
            if ($status->success() === true) {
                // json化
                $json = json_encode($status);
                // ログ出力
                Plogger::debug("データの更新に成功しました");
                // レスポンス
                $this->response->setStatusCode(201, 'Updated');
                $items->id = $status->getModel()->id;
                $this->response->setContent($json);
                $this->response->setContentType("application/json", "utf-8");
                $this->response->send();
                $this->view->disable();
                
            // 失敗したら
            } else {
                // ログ出力
                Plogger::error("データの更新に失敗しました");
                // エラーメッセージの用意
                $errors = [];
                foreach($status->getMessages() as $message) {
                    $errors[] = $message->getMessage();
                }
                // レスポンス
                $this->response->setStatusCode(409, 'Conflict');
                $this->response->setContent(var_dump($errors));
                $this->response->setContentType("application/json", "utf-8");
                $this->response->send();
                $this->view->disable();
            }
            
        // 対象データが存在しなかったら
        } else {
            // ログ出力
            Plogger::error("対象のデータは存在しませんでした");
            // レスポンス
            $this->response->setStatusCode(404, 'NOT-FOUND');
            $this->response->setContentType("application/json", "utf-8");
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
                Plogger::error("データの削除に失敗しました");
                // エラーメッセージの用意
                $errors = [];
                foreach($status->getMessages() as $message) {
                    $errors[] = $message->getMessage();
                }
                // レスポンス
                $this->response->setStatusCode(409, 'Conflict');
//                 echo"エラーメッセージ：";
                $this->response->setContent(var_dump($errors));
                $this->response->setContentType("application/json", "utf-8");
                $this->response->send();
                $this->view->disable();
            }
            
        // 対象データが存在しなかったら
        } else {
            // ログ出力
            Plogger::error("対象のデータは存在しませんでした");
            // レスポンス
            $this->response->setStatusCode(404, 'NOT-FOUND');
            $this->response->setContentType("application/json", "utf-8");
            $this->view->disable();
        }
        
        return;
    }

}

