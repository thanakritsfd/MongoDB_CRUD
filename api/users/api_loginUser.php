<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include files โดยใช้ __DIR__ เพื่อระบุเส้นทางสมบูรณ์
include_once __DIR__ . "/../../connect.php";
include_once __DIR__ . "/../../model/Users.php";

// สร้างอ็อบเจ็กต์ของคลาส DatabaseConnect
$databaseConnect = new DatabaseConnect();
$connDB = $databaseConnect->getConnection();

// สร้างอ็อบเจ็กต์ของคลาส Users โดยใช้ connection จาก DatabaseConnect
$login_Users = new Users($connDB);

//สร้างตัวแปรเก็บค่าของข้อมูลที่ส่งมาซึ่งเป็น JSON ที่ทำการ decode แล้ว
$data = json_decode(file_get_contents("php://input"));

//เอาข้อมูลที่ถูก Decode ไปเก็บในตัวแปร
$login_Users->name = $data->name;
$login_Users->password = $data->password;

// เรียกใช้ Function ตามวัตถุประสงค์ของ API ตัวนี้
$cursor = $login_Users->loginUser(); 

// สร้างตัวแปรมาเก็บข้อมูลที่ได้จากการเรียกใช้ function เพื่อส่งกับไปยังส่วนที่เรียกใช้ API
$login_Users_arr = array();

// ตรวจสอบผล และส่งกลับไปยังส่วนที่เรียกใช้ API
if (!empty($cursor)) {
    // มีข้อมูล เอาข้อมูลใส่ตัวแปร และเตรียมส่งกลับ
    foreach ($cursor as $document) {
        $login_Users_item = array(
            "_id" => $document['_id'],
            "name" => $document['name'],
            "email" => $document['email']
        );

        array_push($login_Users_arr, $login_Users_item);
    }
} else {
    // ไม่มีข้อมูล เอาข้อมูลใส่ตัวแปร และเตรียมส่งกลับ
    $login_Users_item = array(
        "message" => "Invalid"
    );
    array_push($login_Users_arr, $login_Users_item);
}

// คำสั่งจัดการข้อมูลให้เป็น JSON เพื่อส่งกลับ
http_response_code(200);
echo json_encode($login_Users_arr);
?>
