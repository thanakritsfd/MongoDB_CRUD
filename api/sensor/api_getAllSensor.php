<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include files โดยใช้ __DIR__ เพื่อระบุเส้นทางสมบูรณ์
include_once __DIR__ . "/../../connect.php";
include_once __DIR__ . "/../../model/Sensor.php";

// สร้างอ็อบเจ็กต์ของคลาส DatabaseConnect
$databaseConnect = new DatabaseConnect();
$connDB = $databaseConnect->getConnection();

// สร้างอ็อบเจ็กต์ของคลาส Sensor โดยใช้ connection จาก DatabaseConnect
$get_Sensor = new Sensor($connDB);

// เรียกใช้ Function ตามวัตถุประสงค์ของ API ตัวนี้
$cursor = $get_Sensor->getAllSensor(); 

// สร้างตัวแปรมาเก็บข้อมูลที่ได้จากการเรียกใช้ function เพื่อส่งกับไปยังส่วนที่เรียกใช้ API
$get_Sensor_arr = array();

// ตรวจสอบผล และส่งกลับไปยังส่วนที่เรียกใช้ API
if (!empty($cursor)) {
    // มีข้อมูล เอาข้อมูลใส่ตัวแปร และเตรียมส่งกลับ
    foreach ($cursor as $document) {
        // สร้าง UTCDateTime object จาก timestamp ที่ให้มา
        $utcDateTime = $document['timestamp'];
    
        // แปลง UTCDateTime เป็น DateTime object
        $dateTimeUTC = $utcDateTime->toDateTime();
    
        // ตั้งโซนเวลาเป็น Asia/Bangkok
        $dateTimeUTC->setTimezone(new DateTimeZone('Asia/Bangkok'));
    
        // สร้างเอกสารที่ต้องการดึงออกมา
        $get_Sensor_item = array(
            "message" => "1",  
            "temp" => $document['temp'],
            "humidity" => $document['humidity'],
            "timestamp" => $dateTimeUTC->format('Y-m-d H:i:s') // แปลงเป็นรูปแบบที่ต้องการ
        );

        array_push($get_Sensor_arr, $get_Sensor_item);
    }
} else {
    // ไม่มีข้อมูล เอาข้อมูลใส่ตัวแปร และเตรียมส่งกลับ
    $get_Sensor_item = array(
        "message" => "0"
    );
    array_push($get_Sensor_arr, $get_Sensor_item);
}

// คำสั่งจัดการข้อมูลให้เป็น JSON เพื่อส่งกลับ
http_response_code(200);
echo json_encode($get_Sensor_arr);
?>
