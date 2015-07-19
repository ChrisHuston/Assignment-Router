<?php
session_start();
// Load up the Basic LTI Support code
require_once 'ims-blti/blti.php';
// Initialize, all secrets are 'secret', do not set session, and do not redirect
$context = new BLTI(“your_secret_here”, false, false);

if ( $context->valid ) {
    if (isset($_POST['custom_canvas_course_id'])) {
        $_SESSION['course_id'] = $_POST['custom_canvas_course_id'];
        $_SESSION['net_id'] = strtolower($_POST['custom_canvas_user_login_id']);
        $roles = $_POST['roles'];
        if (strpos($roles, 'Administrator') !== false || strpos($roles, 'Instructor') !== false || strpos($roles, 'Designer') !== false || strpos($roles, 'ContentDeveloper') !== false) {
            $_SESSION['priv_level'] = 3;
        } else if (strpos($roles, 'TeachingAssistant') !== false) {
            $_SESSION['priv_level'] = 2;
        } else {
            $_SESSION['priv_level'] = 1;
        }
        $_SESSION['roles'] = $roles;
        if (isset($_POST['custom_canvas_assignment_id'])) {
            $_SESSION['assignment_id'] = $_POST['custom_canvas_assignment_id'];
            if (isset($_POST['lis_result_sourcedid'])) {
                $_SESSION['lis_result_sourcedid'] = $_POST['lis_result_sourcedid'];
                $_SESSION['lis_outcome_service_url'] = $_POST['lis_outcome_service_url'];
                $_SESSION['oauth_consumer_key'] = $_POST['oauth_consumer_key'];
            }
            $_SESSION['is_assignment'] = true;
        } else {
            $_SESSION['is_assignment'] = false;
        }
    }

}
?>

<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Assignment Router</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bower_components/ng-grid/ng-grid.min.css" />
    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/main.css">
    <!-- endbuild -->
</head>
<body ng-app="assignmentRouterApp">
<div ng-controller="MenuCtrl">
    <nav class="navbar navbar-default navbar-fixed-top no-print" role="navigation" ng-init="user.priv_level=1" ng-show="user.priv_level>1">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#session-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="session-navbar-collapse">
            <ul class="nav navbar-nav">
                <li ng-class="{active:route.is_admin}"><a href="" ng-click="setActive('/admin')">Admin</a></li>
                <li ng-class="{active:route.is_users}"><a href="" ng-click="setActive('/users')">Users</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</div>
<!-- Add your site or application content here -->
<div class="container" ng-view=""></div>

<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/angular/angular.min.js"></script>
<script src="../bower_components/angular-sanitize/angular-sanitize.min.js"></script>
<script src="../bower_components/angular-route/angular-route.min.js"></script>
<script src="../bower_components/angular-touch/angular-touch.min.js"></script>

<script src="scripts/app.js"></script>
<script src="scripts/controllers/admin.js"></script>
<script src="scripts/controllers/main.js"></script>
<script src="scripts/controllers/users.js"></script>
<script src="scripts/controllers/menu.js"></script>
<script src="scripts/services/user.js"></script>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script src="../bower_components/underscore/underscore-min.js"></script>
<script src="../bower_components/ng-flow/dist/ng-flow-standalone.min.js"></script>
<script src="../bower_components/ng-grid/build/ng-grid.min.js"></script>
<script src="../bower_components/ng-grid/plugins/ng-grid-csv-export.js"></script>
</body>
</html>