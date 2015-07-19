<?php
session_start();
class DbRes {
    var $files;
    var $roles;
    var $members;
}

$res = new DbRes();

$_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION['course_id'])) {
    include("advanced_user_oo.php");
    Define('DATABASE_SERVER', $hostname);
    Define('DATABASE_USERNAME', $username);
    Define('DATABASE_PASSWORD', $password);
    Define('DATABASE_NAME', 'assignment_router');

    $mysqli = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    $course_id = $_SESSION['course_id'];
    $assignment_id = $_POST['assignment_id'];

    $_SESSION['assignment_id'] = $assignment_id;

    $query = "SELECT file_name, role_id
        FROM assignment_files
        WHERE assignment_id='$assignment_id'
        ORDER BY file_name; ";

    $query .= "SELECT role_id, role_name, role_description
        FROM assignment_roles
        WHERE assignment_id='$assignment_id'
        ORDER BY role_name; ";

    $query .= "SELECT r.role_id, u.net_id, u.user_name, u.section_id, s.section
        FROM course_users u
        INNER JOIN course_sections s
            ON s.section_id=u.section_id
        LEFT JOIN role_members r
            ON u.net_id=r.net_id AND r.assignment_id='$assignment_id'
        WHERE u.course_id='$course_id'
        ORDER BY s.section, u.user_name; ";

    $result = $mysqli->multi_query($query);

    $mysqli->next_result();
    $result = $mysqli->store_result();
    $json = array();
    while ($row = $result->fetch_assoc()) {
        $json[] = $row;
    }
    $res->files = $json;

    $mysqli->next_result();
    $result = $mysqli->store_result();
    $json = array();
    while ($row = $result->fetch_assoc()) {
        $json[] = $row;
    }
    $res->roles = $json;

    $mysqli->next_result();
    $result = $mysqli->store_result();
    $json = array();
    while ($row = $result->fetch_assoc()) {
        $json[] = $row;
    }
    $res->members = $json;


    $mysqli->close();
    echo json_encode($res);

} else {
    $login_result->login_error = "Authentication error.";
    echo json_encode($login_result);
}

?>