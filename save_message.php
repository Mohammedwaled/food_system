<?php
// 1. الاتصال بقاعدة البيانات
$conn = mysqli_connect("localhost", "root", "", "food_lover_site");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. استقبال البيانات من الفورم (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // 3. كويري الإدخال في جدول messages
    $sql = "INSERT INTO messages (name, email, subject, message) 
            VALUES ('$name', '$email', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
        // لو نجح، يطلعه رسالة ويرجعه لصفحة الهوم
        echo "<script>
                alert('Thank you! Your message has been sent.');
                window.location.href = 'home.html'; 
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>