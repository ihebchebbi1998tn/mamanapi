<?php
$servername = "sql11.freesqldatabase.com";
$username = "sql11687350";
$password = "4r8x73pH2u";
$dbname = "sql11687350";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT title_post, descri_post, imagelink_post, createdat_post , category_post , type_post  FROM Post WHERE status_post = 'Accepted' ORDER BY type_post DESC";
$result = $conn->query($sql);

$posts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($posts);
?>
