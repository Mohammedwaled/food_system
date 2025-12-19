 <?php
$conn = mysqli_connect("localhost", "root", "", "food_lover_site");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if (!$conn) { die("Connection failed"); }

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $user = mysqli_real_escape_string($conn, $data['username']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $pass = password_hash($data['password'], PASSWORD_DEFAULT); // تشفير للأمان

    // بنجرب ندخل البيانات
    $sql = "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$pass')";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "This email or username is already taken!";
    }
}
mysqli_close($conn);
?>