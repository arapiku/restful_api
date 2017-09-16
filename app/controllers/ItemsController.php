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
        $item = Items::find("title = '$title'");
        
        echo json_encode($item);
    }
    
    public function singleAction($id)
    {            
        $item = Items::find("id = $id");        
        
        echo json_encode($item);
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
    
    public function updateAction()
    {
        
    }
    
    public function destroyAction()
    {
        
    }

}

