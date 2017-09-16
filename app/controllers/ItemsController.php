<?php

use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Response;

class ItemsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $query = $this->modelsManager->createQuery("SELECT * FROM Items");
        $items = $query->execute();

        $data = [];
        
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'price' => $item->price,
                'image' => $item->image,
            ];
        }
        
        echo json_encode($data);
    }
    
    public function searchAction($title)
    {
        $phql = "SELECT * FROM Items WHERE title LIKE :title:";
        $items = $this->modelsManager->executeQuery(
            $phql,
            [
                'title' => '%' . $title . '%'                
            ]
        );
        
        $data = [];
        
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'price' => $item->price,
                'image' => $item->image,
            ];
        }
        
        echo json_encode($data);
    }
    
    public function singleAction($id)
    {               
        $phql = "SELECT * FROM Items WHERE id = :id:";
        $items = $this->modelsManager->executeQuery(
            $phql,
            [
                'id' => $id
            ]
        )->getFirst();
        
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
        
        $phql = 'INSERT INTO Items (title, description, price, image) 
                 VALUES (:title:, :description:, :price:, :image:)';
        
        $status = $this->modelsManager->executeQuery(
            $phql,
            [
                'title' => $items->title,
                'description' => $items->description,
                'price' => $items->price,
                'image' => $items->image,
            ]
        );
        
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
        $items = $this->request->getJsonRawBody();
        
        $phql = "UPDATE Items SET title = :title:, 
                description = :description:, price = :price:, 
                image = :image: WHERE id = :id:";
        
        $status = $this->modelsManager->executeQuery(
            $phql,
            [
                'id' => $id,
                'title' => $items->title,
                'description' => $items->description,
                'price' => $items->price,
                'image' => $items->image,
            ]
        );
        
        // レスポンスを作成
        $response = new Response();
        
        // もしアップデートが成功したら
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
    
    public function destroyAction($id)
    {
        
    }

}

