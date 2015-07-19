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
    $net_id = $_POST['net_id'];

    $query = "INSERT INTO role_members (assignment_id, role_id, net_id) VALUES
     ('$assignment_id', '$role_id', '$net_id')
     ON DUPLICATE KEY UPDATE role_id='$role_id'";
    $result = $mysqli->query($query);

    $mysqli->close();
    echo json_encode($result);
}

?>