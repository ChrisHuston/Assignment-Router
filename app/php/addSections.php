<?php
session_start();
$_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION['course_id'])) {
    include("advanced_user_oo.php");
    Define('DATABASE_SERVER', $hostname);
    Define('DATABASE_USERNAME', $username);
    Define('DATABASE_PASSWORD', $password);
    Define('DATABASE_NAME', 'assignment_router');
    $mysqli = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

    $inserts = $_POST['inserts'];
    $query = "INSERT INTO course_sections
                (course_id, section, section_id)
                VALUES ".$inserts." ON DUPLICATE KEY UPDATE section_id=VALUES(section_id)";
    $result = $mysqli->query($query);

    $mysqli->close();
    echo json_encode($result);
}

?>