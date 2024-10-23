<?php
// Database connection
$servername = "https://ceejaygoat.github.io/music/";
$username = "root";
$password = "";
$dbname = "amap_records";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Processing sign-up
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypting the password

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "Email already registered!";
    } else {
        // Inserting new user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            echo "Sign-up successful!";
            header("Location: login.html"); // Redirect to login page
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $checkEmail->close();
}
$conn->close();
?>
