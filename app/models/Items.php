<?php

include_once $config->application->libraryDir.'plogger.php';

use App\Library\Log\Plogger;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use Phalcon\Validation;
use Phalcon\Validation\Validator\StringLength;

class Items extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $title;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $description;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $price;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $image;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("rest");
        $this->setSource("items");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'items';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Items[]|Items|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        $items = parent::find($parameters);
        
        if(count($items) != 0) {
            // ログ出力
            $plogger = new Plogger("Itemsのfindメソッドが実行されました");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return $items;
            
        } else {
            // ログ出力
            $plogger = new Plogger("Itemsのfindメソッドが実行されませんでした");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return;
            
        }
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Items|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    
    /**
     * タイトル検索用メソッド
     */
    public static function findByTitle($parameters = null)
    {
        $criteria = Items::query();
        $criteria->where('title LIKE :title:', ['title' => '%' . $parameters . '%']);
        $items = $criteria->execute();
        // executeされたら
        if($items) {
            // ログ出力
            $plogger = new Plogger("ItemsのfindByTitleメソッドが実行されました");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return $items;
        
        // executeされなかったら
        } else {
            // ログ出力
            $plogger = new Plogger("ItemsのfindByTitleメソッドが実行されませんでした");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return;
            
        }
    }

    /**
     * id検索用メソッド
     */
    public static function findById($parameters = null)
    {
        $items = parent::findById($parameters);
        if(count($items) != 0) {
            // ログ出力
            $plogger = new Plogger("ItemsのfindByIdメソッドが実行されました");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return $items;
            
        } else {
            // ログ出力
            $plogger = new Plogger("ItemsのfindByIdメソッドが実行されませんでした");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return;
            
        }
    }
    
    /**
     * 新規登録用メソッド
     */
    public static function createItems($array)
    {
        $phql = 'INSERT INTO Items (title, description, price, image)
                 VALUES (:title:, :description:, :price:, :image:)';
        $items = new Items();
        $array = [
            'title' => $array->title,
            'description' => $array->description,
            'price' => $array->price,
            'image' => $array->image,
        ];
        $execute = $items->modelsManager->executeQuery($phql, $array);
        
        if($execute) {
            // ログ出力
            $plogger = new Plogger("ItemsのfindByIdメソッドが実行されました");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return $execute;
            
        } else {
            // ログ出力
            $plogger = new Plogger("ItemsのcreateItemsメソッドが実行されませんでした");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return;
            
        }
        
    } 
    
    /**
     * 更新用メソッド
     */
    public static function updateItems($array, $id)
    {
        $phql = "UPDATE Items SET title = :title:,
                description = :description:, price = :price:,
                image = :image: WHERE id = :id:";
        $items = new Items();
        $array = [
            'id' => $id,
            'title' => $array->title,
            'description' => $array->description,
            'price' => $array->price,
            'image' => $array->image,
        ];
        
        $execute = $items->modelsManager->executeQuery($phql, $array);
        
        if($execute) {
            // ログ出力
            $plogger = new Plogger("ItemsのupdateItemsメソッドが実行されました");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return $execute;
        } else {
            // ログ出力
            $plogger = new Plogger("ItemsのupdateItemsメソッドが実行されませんでした");
            $plogger->debug();
            echo "<pre>";
            var_dump($plogger);
            echo "</pre>";
            
            return;
        }

    }
    
    /**
     * 削除用メソッド
     */
    public static function deleteItems($id)
    {
        $phql = "DELETE FROM Items WHERE id = :id:";
        $items = new Items();
        $array = [
          'id' => $id  
        ];
        
        return $items->modelsManager->executeQuery($phql, $array);
    }
    
    /**
     * バリデーション
     */
    public function validation()
    {
        $validator = new Validation();
        
        // タイトル文字数
        $validator->add(
            'title',
            new StringLength(
                [
                    'max' => '100',
                    'messageMaximum' => 'Please enter title in 100 characters or less.',
                ]
            )
        );
        
        // 説明文文字数
        $validator->add(
            'description',
            new StringLength(
                [
                    'max' => '500',
                    'messageMaximum' => 'Please enter description in 500 characters or less.',
                ]
            )
        );
        
        return $this->validate($validator);
       
    }
    
}
