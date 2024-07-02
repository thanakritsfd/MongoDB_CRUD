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
$get_Users = new Users($connDB);

// เรียกใช้ Function ตามวัตถุประสงค์ของ API ตัวนี้
$cursor = $get_Users->getAllUsers(); 

// สร้างตัวแปรมาเก็บข้อมูลที่ได้จากการเรียกใช้ function เพื่อส่งกับไปยังส่วนที่เรียกใช้ API
$get_Users_arr = array();

// ตรวจสอบผล และส่งกลับไปยังส่วนที่เรียกใช้ API
if (!empty($cursor)) {
    // มีข้อมูล เอาข้อมูลใส่ตัวแปร และเตรียมส่งกลับ
    foreach ($cursor as $document) {
        $get_Users_item = array(
            "message" => "1",  
            "name" => $document['name'],
            "email" => $document['email'],
            //"password" => $document['password']
        );

        array_push($get_Users_arr, $get_Users_item);
    }
} else {
    // ไม่มีข้อมูล เอาข้อมูลใส่ตัวแปร และเตรียมส่งกลับ
    $get_Users_item = array(
        "message" => "0"
    );
    array_push($get_Users_arr, $get_Users_item);
}

// คำสั่งจัดการข้อมูลให้เป็น JSON เพื่อส่งกลับ
http_response_code(200);
echo json_encode($get_Users_arr);
?>
