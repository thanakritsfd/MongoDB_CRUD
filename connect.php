<?php
require 'vendor/autoload.php'; // โหลด Composer autoload
class DatabaseConnect
{
    protected $connect;
    public function getConnection()
    {
        $this->connect = null;
        try {
            // connect to OVHcloud Public Cloud Databases for MongoDB (cluster in version 4.4, MongoDB PHP Extension in 1.8.1)
            // $this->connect = new MongoDB\Client('mongodb+srv://Dozore:0814332021@cluster0.40vbedp.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
            $this->connect = new MongoDB\Client('mongodb://localhost:27017');
            //echo "Connection to database successfully";
            // display the content of the driver, for diagnosis purpose
            //var_dump($m);
        } catch (Throwable $e) {
            // catch throwables when the connection is not a success
            //echo "Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
        }
        return $this->connect;
    }
}

// สร้างอ็อบเจ็กต์ของคลาส DatabaseConnect
//$db = new DatabaseConnect();

// เรียกใช้งานฟังก์ชัน getConnection() เพื่อเชื่อมต่อกับฐานข้อมูล
//$connection = $db->getConnection();