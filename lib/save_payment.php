<?php
// إعدادات الاستجابة
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// 1. الاتصال بقاعدة البيانات
$conn = mysqli_connect("localhost", "root", "", "food_lover_site");

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit;
}

// 2. استقبال بيانات الـ JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 3. التحقق من وجود البيانات
if (!empty($data['cardholder_name']) && !empty($data['card_number'])) {
    
    // تنظيف البيانات (mysqli_real_escape_string) بدون كلمة with
    $name = mysqli_real_escape_string($conn, $data['cardholder_name']);
    $card = mysqli_real_escape_string($conn, $data['card_number']);
    $amount = 100.00; // قيمة افتراضية

    // 4. كويري الإدخال
    $sql = "INSERT INTO payments (cardholder_name, card_number, amount_paid) 
            VALUES ('$name', '$card', '$amount')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No data received in PHP"]);
}

mysqli_close($conn);
?>