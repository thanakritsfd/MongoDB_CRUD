<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . "/../../connect.php";
include_once __DIR__ . "/../../model/Users.php";

$databaseConnect = new DatabaseConnect();
$connDB = $databaseConnect->getConnection();

$delete_User = new Users($connDB);

//สร้างตัวแปรเก็บค่าของข้อมูลที่ส่งมาซึ่งเป็น JSON ที่ทำการ decode แล้ว
$data = json_decode(file_get_contents("php://input"));

//เอาข้อมูลที่ถูก Decode ไปเก็บในตัวแปร
$delete_User->id = $data->id;

//เรียกใช้ Function ตามวัตถุประสงค์ของ API ตัวนี้
if($stmt = $delete_User->deleteUser()){
    //บันทึกข้อมูลสำเร็จ
   http_response_code(200);
   echo json_encode(array("message"=>"1")); 
}else{
    //บันทึกข้อมูลไม่สำเร็จ
    http_response_code(200);
    echo json_encode(array("message"=>"0"));     
}