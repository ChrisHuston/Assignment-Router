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

    $course_id = $_SESSION['course_id'];
    $assignment_id = $mysqli->real_escape_string($_POST['assignment_id']);
    $assignment_name = $mysqli->real_escape_string($_POST['assignment_name']);

    $query = "INSERT IGNORE INTO assignments (course_id, assignment_id, assignment_name)
    VALUES ('$course_id', '$assignment_id', '$assignment_name')";
    $result = $mysqli->query($query);

    $mysqli->close();
    echo json_encode($result);
}

?>