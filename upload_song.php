<?php
session_start();
// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

// Database connection
$servername = "https://github.com/ceejaygoat/music.git";
$username = "root";
$password = "";
$dbname = "amap_records";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling song upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['song'])) {
    $song_name = $_POST['song_name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["song"]["name"]);
    $song_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allow only certain file formats
    $allowed_formats = ['mp3', 'wav', 'flac'];
    if (!in_array($song_file_type, $allowed_formats)) {
        echo "Sorry, only MP3, WAV & FLAC files are allowed.";
    } elseif (move_uploaded_file($_FILES["song"]["tmp_name"], $target_file)) {
        // Save song information in the database
        $stmt = $conn->prepare("INSERT INTO songs (song_name, file_path, upload_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $song_name, $target_file);
        if ($stmt->execute()) {
            echo "Song uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading the song.";
    }
}

$conn->close();
?>
