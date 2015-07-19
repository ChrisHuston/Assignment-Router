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
    $file_name = $_POST['file_name'];

    $query = "DELETE FROM assignment_files
     WHERE assignment_id='$assignment_id' AND file_name='$file_name'";
    $result = $mysqli->query($query);

    $mysqli->close();

    $path = "/full_path to your server/assignment_router/files/".$assignment_id."/".$file_name;

    $del = unlink($path);

    if ($del) {
        echo $del;
    } else {
        echo $path;
    }

}

?>