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
    $role_name = $mysqli->real_escape_string($_POST['role_name']);
    $role_description = $mysqli->real_escape_string($_POST['role_description']);

    $query = "UPDATE assignment_roles SET role_name ='$role_name', role_description='$role_description'
      WHERE assignment_id='$assignment_id' AND role_id='$role_id'";
    $result = $mysqli->query($query);

    $mysqli->close();
    echo json_encode($result);
}

?>