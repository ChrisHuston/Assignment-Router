<?php
session_start();
class UserInfo {
    var $login_error = "NONE";
    var $msg;
    var $assignments = [];
    var $users = [];
    var $sections = [];
    var $course_id = 1;
    var $files = [];
    var $role_name;
    var $role_description;
    var $assignment_id;
    var $priv_level = 1;
    var $roles = [];
    var $members = [];
}

$res = new UserInfo();

$_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION['course_id'])) {
    include("advanced_user_oo.php");
    Define('DATABASE_SERVER', $hostname);
    Define('DATABASE_USERNAME', $username);
    Define('DATABASE_PASSWORD', $password);
    Define('DATABASE_NAME', 'assignment_router');

    $mysqli = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

    $res->priv_level = $_SESSION['priv_level'];
    $res->roles = $_SESSION['roles'];

    $course_id = $_SESSION['course_id'];
    $res->course_id = $course_id;

    if (isset($_SESSION['msg'])) {
        $res->msg = $_SESSION['msg'];
    }
    
    if ($_SESSION['priv_level']==1) {
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
    } else {
        if ($_SESSION['is_assignment']) {
            $res->assignment_id = $_SESSION['assignment_id'];
            $assignment_id = $_SESSION['assignment_id'];
            $query = "SELECT file_name, role_id
                FROM assignment_files
                WHERE assignment_id='$assignment_id'
                ORDER BY file_name; ";

            $query .= "SELECT role_id, role_name, role_description
                FROM assignment_roles
                WHERE assignment_id='$assignment_id'
                ORDER BY role_name; ";

            $query .= "SELECT r.role_id, u.net_id, u.user_name, u.section_id, s.section, r.downloaded
                FROM course_users u
                INNER JOIN course_sections s
                    ON s.section_id=u.section_id
                LEFT JOIN role_members r
                    ON u.net_id=r.net_id AND r.assignment_id='$assignment_id'
                WHERE u.course_id='$course_id'
                ORDER BY s.section, u.user_name; ";

            $query .= "SELECT u.user_name, u.net_id, u.section_id, s.section
                FROM course_users u
                INNER JOIN course_sections s
                    ON s.course_id=u.course_id AND s.section_id=u.section_id
                WHERE u.course_id='$course_id'
                ORDER BY s.section, u.user_name;";

            $query .= "SELECT s.section_id, s.section
                FROM course_sections s
                WHERE s.course_id='$course_id'
                ORDER BY s.section";

            $result = $mysqli->multi_query($query);

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->files = $json;
            }

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->roles = $json;
            }

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->members = $json;
            }

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->users = $json;
            }

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->sections = $json;
            }

        } else {
            $query = "SELECT assignment_id, assignment_name
                FROM assignments
                WHERE course_id='$course_id'
                ORDER BY assignment_id; ";

            $query .= "SELECT u.user_name, u.net_id, u.section_id, s.section
                FROM course_users u
                INNER JOIN course_sections s
                    ON s.course_id=u.course_id AND s.section_id=u.section_id
                WHERE u.course_id='$course_id'
                ORDER BY s.section, u.user_name;";

            $query .= "SELECT s.section_id, s.section
                FROM course_sections s
                WHERE s.course_id='$course_id'
                ORDER BY s.section";

            $result = $mysqli->multi_query($query);

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->assignments = $json;
            }

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->users = $json;
            }

            if ($mysqli->more_results()) {
                $mysqli->next_result();
                $result = $mysqli->store_result();
                $json = array();
                while ($row = $result->fetch_assoc()) {
                    $json[] = $row;
                }
                $res->sections = $json;
            }
        }
    }
    
    $mysqli->close();
    echo json_encode($res);

} else {
    $res->login_error = "Authentication error.";
    echo json_encode($res);
}

?>