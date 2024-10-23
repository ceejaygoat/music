<?php
// Database connection
$servername = "https://github.com/ceejaygoat/music.git";
$username = "root";
$password = "";
$dbname = "amap_records";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['admin_id'] = $id;
            header("Location: admin_dashboard.php"); // Redirect to the admin dashboard
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Admin not found!";
    }

    $stmt->close();
}
$conn->close();
?>
