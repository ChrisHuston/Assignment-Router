<?php
session_start();
class DbRes {
    var $files;
    var $role_name;
    var $role_description;
    var $assignment_id;
}

$res = new DbRes();

$_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION['net_id'])) {
    include("advanced_user_oo.php");
    Define('DATABASE_SERVER', $hostname);
    Define('DATABASE_USERNAME', $username);
    Define('DATABASE_PASSWORD', $password);
    Define('DATABASE_NAME', 'assignment_router');

    $mysqli = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    $net_id = $_SESSION['net_id'];
    $assignment_id = $_SESSION['assignment_id'];

    $query = "SELECT m.role_id, r.role_name, r.role_description
        FROM role_members m
        INNER JOIN assignment_roles r
            ON r.role_id=m.role_id AND m.assignment_id=r.assignment_id
        WHERE m.net_id='$net_id' AND m.assignment_id='$assignment_id'";

    $result = $mysqli->query($query);
    list($role_id, $role_name, $role_description) = $result->fetch_row();

    $res->assignment_id = $assignment_id;
    $res->role_description = $role_description;
    $res->role_name = $role_name;


    $query = "SELECT f.file_name
        FROM assignment_files f
        WHERE (f.role_id='$role_id' OR f.role_id='0') AND f.assignment_id='$assignment_id'
        ORDER BY f.file_name";

    $result = $mysqli->query($query);
    $json = array();
    while ($row = $result->fetch_assoc()) {
        $json[] = $row;
    }
    $res->files = $json;

    $mysqli->close();
    echo json_encode($res);

} else {
    echo json_encode(false);
}

?>