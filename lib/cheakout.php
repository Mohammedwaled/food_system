<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// 1. الاتصال بقاعدة البيانات
$conn = mysqli_connect("localhost", "root", "", "food_lover_site");

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit;
}

// 2. استقبال بيانات السلة (JSON)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 3. التحقق من وجود بيانات
if (!empty($data['cart']) && !empty($data['email'])) {
    
    $email = mysqli_real_escape_string($conn, $data['email']);
    $success_count = 0;

    // 4. لفّة (Loop) على كل صنف في السلة وحفظه كطلب منفصل
    foreach ($data['cart'] as $item) {
        $itemName = mysqli_real_escape_string($conn, $item['name']);
        $itemPrice = mysqli_real_escape_string($conn, $item['price']);

        $sql = "INSERT INTO orders (user_email, item_name, item_price) 
                VALUES ('$email', '$itemName', '$itemPrice')";
        
        if (mysqli_query($conn, $sql)) {
            $success_count++;
        }
    }

    if ($success_count > 0) {
        echo json_encode(["status" => "success", "message" => "Order saved successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save order"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Empty cart or missing email"]);
}

mysqli_close($conn);
?>