<?php

use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Response;

class ItemsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $items = Items::createItems();
        
        echo json_encode($items);
    }
    
    public function searchAction($title)
    {
        $items = Items::findByTitle($title);
        echo json_encode($items);
    }
    
    public function singleAction($id)
    {               
        $items = Items::findById($id);
        
        // レスポンスを作成
        $response = new Response();
        
        if ($items === false) {
            // ステータスコードを変える
            $response->setStatusCode(404, 'NOT-FOUND');
            
            $response->setJsonContent(
                [
                    'status' => 'NOT-FOUND'
                ]
            );
        } else {
            $response->setJsonContent(
                [
                    'status' => 'FOUND',
                    'data' => $items
                ]
            );
        }
        
        return $response;
    }
    
    public function newAction()
    {
        $items = $this->request->getJsonRawBody();

        $status = Items::createItems($items);
        
        // レスポンスを作成
        $response = new Response();
        
        // もしinsertionが成功したら
        if ($status->success() == true) {
            // HTTPステータスコードを変える
            $response->setStatusCode(201, 'Created');
            
            $items->id = $status->getModel()->id;
            
            $response->setJsonContent(
                [
                    'status' => 'OK',
                    'data' => $items,
                ]
            );
            // 失敗したら
        } else {
            // HTTPステータスコードを変える
            $response->setStatusCode(409, 'Conflict');
            
            $errors = [];
            
            foreach($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            
            $response->setJsonContent(
                [
                    'status' => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }
        
        return $response;
    }
    
    public function updateAction($id)
    {
        $find_items = Items::findById($id);
        
        // レスポンスを作成
        $response = new Response();
        
        // 対象データが存在しなかったら
        if (count($find_items) <= 0) {
            // ステータスコードを変える
            $response->setStatusCode(404, 'NOT-FOUND');
            
            $response->setJsonContent(
                [
                    'status' => 'NOT-FOUND'
                ]
            );
            
        // 対象データが存在したら
        } else {
            
            $items = $this->request->getJsonRawBody();

            $status = Items::updateItems($items, $id);
            
            // もしアップデートが成功したら
            if ($status->success() === true) {
                // HTTPステータスコードを変える
                $response->setStatusCode(204, 'No Content');
                
                $response->setJsonContent(
                    [
                        'status' => 'OK',
                        'message' => $find_items
                    ]
                );
            // 失敗したら
            } else {
                // HTTPステータスコードを変える
                $response->setStatusCode(409, 'Conflict');
                
                $errors = [];
                
                foreach($status->getMessages() as $message) {
                    $errors[] = $message->getMessage();
                }
                
                $response->setJsonContent(
                    [
                        'status' => 'ERROR',
                        'messages' => $errors,
                    ]
                );
            }
            
        }
        
        return $response;
    }
    
    public function destroyAction($id)
    {
        $status = Items::deleteItems($id);
        
        // レスポンスを作成
        $response = new Response();
        
        // もし削除が成功したら
        if ($status->success() === true) {
            // HTTPステータスコードを変える
            $response->setStatusCode(204, 'No Content');
            
            $response->setJsonContent(
                [
                    'status' => 'OK'
                ]
            );
        // 失敗したら
        } else {
            // HTTPステータスコードを変える
            $response->setStatusCode(409, 'Conflict');
            
            $errors = [];
            
            foreach($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            
            $response->setJsonContent(
                [
                    'status' => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }
        
        return $response;
    }

}

