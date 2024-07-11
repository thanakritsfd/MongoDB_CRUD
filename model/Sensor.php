<?php
class Sensor
{
    //ตัวแปรที่ใช้ในการติดต่อ Database
    private $connect;
    private $collection;

    //ตัวแปรที่จะทำงานคู่กับแต่ละฟิวล์ในตาราง
    public $id;
    public $temp;
    public $humidity;
    public $timestamp;
    
    //ตัวแปรที่เก็บข้อความต่าง ๆ เผื่อไว้ใช้งาน เฉย ๆ
    public $message;

    //คอนตรักเตอร์ที่จะมีคำสั่งที่ใช้ในการติดต่อกับ Database
    public function __construct($db)
    {
        $this->connect = $db;
        $this->collection = $this->connect->selectCollection('project2_db', 'sensor_tb');
    }

    //ฟังก์ชั่นต่าง ๆ ที่จะทำงานกับ Database ตาม API ที่เราจะทำการสร้างมันขึ้นมา ซึ่งมีมากน้อยแล้วแต่
    function getAllSensor()
    {
        $filter = []; 
        $result = $this->collection->find($filter,['projection'=>[
            'temp'=>1,
            'humidity'=>1,
            'timestamp'=>1
        ]])->toArray();
        return $result;  
    }

    function insertSensor()
    {
        //ตรวจสอบข้อมูล
        $this->temp = htmlspecialchars(strip_tags($this->temp));
        $this->humidity = htmlspecialchars(strip_tags($this->humidity));
        $utcDateTime = new MongoDB\BSON\UTCDateTime((new DateTime('now', new DateTimeZone('Asia/Bangkok')))->getTimestamp() * 1000);     

        $document = [
            'temp' => $this->temp,
            'humidity' => $this->humidity,
            'timestamp' => $utcDateTime
        ];  
        return $this->collection->insertOne($document); 
    }
}