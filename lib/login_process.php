<?php
// 1. الاتصال بقاعدة البيانات
$conn = mysqli_connect("localhost", "root", "", "food_lover_site");

// التأكد من جودة الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. استقبال البيانات من الجافا سكريبت (JSON)
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // تنظيف البيانات المدخلة للأمان
    $user_input = mysqli_real_escape_string($conn, $data['username']);
    $pass_input = $data['password'];

    // 3. البحث عن المستخدم في الداتا بيز (بناءً على الإيميل أو الاسم)
    $sql = "SELECT * FROM users WHERE email='$user_input' OR username='$user_input'";
    $result = mysqli_query($conn, $sql);
    
    // هل في يوزر بالبيانات دي؟
    if ($row = mysqli_fetch_assoc($result)) {
        
        // 4. مقارنة الباسورد اللي كتبه بالباسورد "المتشفّر" اللي في الداتا بيز
        if (password_verify($pass_input, $row['password'])) {
            
            // هنا بنبدأ "جلسة" (Session) عشان الموقع يفضل فاكر اليوزر
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['username'];
            
            echo "success"; // الرد اللي JS مستنيه عشان يحولك للهوم
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "No account found with this username or email.";
    }
}

mysqli_close($conn);
?>