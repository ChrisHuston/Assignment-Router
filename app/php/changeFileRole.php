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

    $assignment_id = $_POST['assignment_id'];
    $role_id = $_POST['role_id'];
    $file_name = $mysqli->real_escape_string($_POST['file_name']);

    $query = "UPDATE assignment_files SET role_id='$role_id'
     WHERE assignment_id='$assignment_id' AND file_name='$file_name'";
    $result = $mysqli->query($query);

    $mysqli->close();
    echo json_encode($result);
}

?>