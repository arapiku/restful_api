<?php

use Phalcon\Mvc\Model\Query;

class ItemsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        
        $items = Items::find();
        
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
    
    public function singleAction()
    {            
        
    }
    
    public function newAction()
    {
        
    }
    
    public function updateAction()
    {
        
    }
    
    public function destroyAction()
    {
        
    }

}

