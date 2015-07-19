<?php
session_start();
$_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION['assignment_id'])) {
    include("advanced_user_oo.php");
    Define('DATABASE_SERVER', $hostname);
    Define('DATABASE_USERNAME', $username);
    Define('DATABASE_PASSWORD', $password);
    Define('DATABASE_NAME', 'assignment_router');
    $mysqli = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

    $assignment_id = $_SESSION['assignment_id'];
    $net_id = $_SESSION['net_id'];

    $query = "UPDATE role_members SET downloaded=(downloaded+1)
        WHERE assignment_id='$assignment_id' AND net_id='$net_id'; ";
    $result = $mysqli->query($query);

    $mysqli->close();
    echo json_encode($result);
}

?>