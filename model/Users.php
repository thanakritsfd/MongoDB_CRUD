<?php
class Users
{
    //ตัวแปรที่ใช้ในการติดต่อ Database
    private $connect;
    private $collection;

    //ตัวแปรที่จะทำงานคู่กับแต่ละฟิวล์ในตาราง
    public $id;
    public $name;
    public $email;
    public $password;
    
    //ตัวแปรที่เก็บข้อความต่าง ๆ เผื่อไว้ใช้งาน เฉย ๆ
    public $message;

    //คอนตรักเตอร์ที่จะมีคำสั่งที่ใช้ในการติดต่อกับ Database
    public function __construct($db)
    {
        $this->connect = $db;
        $this->collection = $this->connect->selectCollection('project2_db', 'users_tb');
    }

    //ฟังก์ชั่นต่าง ๆ ที่จะทำงานกับ Database ตาม API ที่เราจะทำการสร้างมันขึ้นมา ซึ่งมีมากน้อยแล้วแต่
    function getAllUsers()
    {
        $filter = []; 
        $result = $this->collection->find($filter,['projection'=>[
            'name'=>1,
            'email'=>1
        ]])->toArray();
        return $result;  
    }

    function loginUser()
    {
        //ตรวจสอบข้อมูล
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->password = htmlspecialchars(strip_tags($this->password));        

        $filter = ['$and'=>[['name'=>$this->name, 'password'=>$this->password]]];    
        $result = $this->collection->find($filter,['projection'=>[
            '_id'=>1,
            'name'=>1,
            'email'=>1
        ]])->toArray();
        return $result;  
    }

    function insertUser()
    {
        //ตรวจสอบข้อมูล
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));        

        $document = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ];  
        return $this->collection->insertOne($document);  
    }

    function updateUser()
    {       
        //ตรวจสอบข้อมูล
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password)); 

        $filter = ['_id' => new MongoDB\BSON\ObjectID($this->id)];
        $update = [
            '$set' => [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password
            ]
        ];
        return $this->collection->updateOne($filter, $update);
    }

    function deleteUser()
    {       
        //ตรวจสอบข้อมูล
        $this->id = htmlspecialchars(strip_tags($this->id));
        $filter = ['_id' => new MongoDB\BSON\ObjectID($this->id)];
        return $this->collection->deleteOne($filter);
    }
}