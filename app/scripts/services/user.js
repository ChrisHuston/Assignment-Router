'use strict';

angular.module('assignmentRouterApp')
  .factory('UserService', function ($http, $location) {
        var userInstance = {};

        userInstance.appDir = '/app/assignment_router/';

        userInstance.admin = {course_id:null, role_id:null, sections:[], users:[], assignments:[], assignment:{roles:[], assignment_id:null, canvas_assignment:false, assignment_name:'', members:[], files:[]}, role:{role_id:null, role_description:'', assignment_id:null, files:[]}};
        userInstance.user = {loginError:null, priv_level:1};
        userInstance.route = {is_admin:true, is_users:false};
        userInstance.role = {files:[], role_name:'', role_description:'', assignment_id:null};

        userInstance.login = function() {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "lti_login.php";
            var params = {};
            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    if (data.login_error === "NONE") {
                        userInstance.user.priv_level = parseInt(data.priv_level);
                        if (userInstance.user.priv_level > 1) {
                            if (data.assignment_id) {
                                userInstance.admin.assignment.assignment_id = data.assignment_id;
                                userInstance.admin.assignment.roles = data.roles;
                                userInstance.admin.assignment.roles.unshift({role_name:'ALL', role_id:'0', role_description:''});
                                userInstance.admin.assignment.files = data.files;
                                userInstance.admin.assignment.members = data.members;
                                userInstance.admin.assignment.canvas_assignment = true;
                            }
                            if (data.assignments) {
                                userInstance.admin.assignments = data.assignments;
                            }
                            if (data.users) {
                                userInstance.admin.users = data.users;
                            }
                            if (data.sections) {
                                angular.forEach(data.sections, function(s) {
                                    s.section = parseInt(s.section);
                                });
                                userInstance.admin.sections = data.sections;
                            }
                            userInstance.admin.course_id = data.course_id;
                            userInstance.route.is_admin = true;
                            $location.path('/admin');
                        } else {
                            userInstance.role.files = data.files;
                            userInstance.role.role_name = data.role_name;
                            userInstance.role.role_description = data.role_description;
                            userInstance.role.assignment_id = data.assignment_id;
                            canvasGradePassback();
                            $("body").css('padding-top',0);
                            $location.path('/');
                        }

                    } else {
                        userInstance.user.loginError =  data.login_error;
                    }
                }).
                error(function(data, status) {
                    userInstance.user.loginError =  "Error: " + status + " Sign-in failed. Check your internet connection";
                });
        };

        var canvasGradePassback = function() {
            var uniqueSuffix = "?" + new Date().getTime();
            var params = {};
            $http({method: 'POST',
                url: userInstance.appDir + 'php/canvasGradePassback.php' + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    console.log(data);
                    /*
                     var xml_obj = x2js.xml_str2json(data);
                     var res = xml_obj.imsx_POXEnvelopeResponse.imsx_POXHeader[0].imsx_POXResponseHeaderInfo[0].imsx_statusInfo[0].imsx_codeMajor[0].__text;
                     if (res === "unsupported" || res === "success") {

                     } else if (res === "failure") {

                     }
                     */
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Canvas grade passback failed. Check your internet connection");
                });
        };

        userInstance.logDownload = function() {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "logDownload.php";
            var params = {};
            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    if (!data) {
                        console.log("Log download failed. Check your internet connection");
                    }
                }).
                error(function(data, status) {
                    console.log("Error: " + status + " Log download failed. Check your internet connection");
                });
        };

        userInstance.addAssignment = function(assignment) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "addAssignment.php";
            var params = {};
            params.assignment_id = assignment.assignment_id;
            params.assignment_name = assignment.assignment_name;
            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    var new_assignment = {};
                    new_assignment.assignment_id = assignment.assignment_id;
                    new_assignment.assignment_name = assignment.assignment_name;
                    new_assignment.roles = [];
                    new_assignment.members = [];
                    new_assignment.files = [];
                    userInstance.admin.assignments.push(new_assignment);
                    userInstance.admin.assignment = {assignment_id:params.assignment_id, files:[], roles:[], members:[]};
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Add assignment failed. Check your internet connection");
                });
        };

        userInstance.updateAssignment = function(assignment) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "updateAssignment.php";
            var params = {};
            params.assignment_id = assignment.assignment_id;
            params.assignment_name = assignment.assignment_name;
            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    if (!data) {
                        alert("Upadte assignment failed. Check your internet connection");
                    }
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Upadte assignment failed. Check your internet connection");
                });
        };

        userInstance.addRole = function() {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "addRole.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = parseInt(userInstance.admin.assignment.roles[userInstance.admin.assignment.roles.length-1].role_id) + 1;
            params.role_name = userInstance.admin.role.role_name;
            params.role_description = userInstance.admin.role.role_description;

            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    userInstance.admin.assignment.roles.push(params);
                    userInstance.admin.role = {role_id:null, role_description:'', assignment_id:null, files:[]};
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Add role failed. Check your internet connection");
                });
        };

        userInstance.saveRole = function() {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "saveRole.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = userInstance.admin.role.role_id;
            params.role_name = userInstance.admin.role.role_name;
            params.role_description = userInstance.admin.role.role_description;

            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    for (var i = 0; i < userInstance.admin.assignment.roles.length; i++) {
                        if (userInstance.admin.assignment.roles[i].role_id === params.role_id) {
                            userInstance.admin.assignment.roles[i].role_name = params.role_name;
                            userInstance.admin.assignment.roles[i].role_description = params.role_description;
                            break;
                        }
                    }
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Save changes to role failed. Check your internet connection");
                });
        };

        userInstance.addFile = function(file_name) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "addFile.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = userInstance.admin.role_id;
            params.file_name = file_name;

            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    params.file_path = "/app/assignment_router/files/" + params.assignment_id + "/";
                    userInstance.admin.assignment.files.push(params);
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Add role failed. Check your internet connection");
                });
        };

        userInstance.changeFileRole = function(e) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "changeFileRole.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = e.role_id;
            params.file_name = e.file_name;

            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function() {
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Change role failed. Check your internet connection");
                });
        };

        userInstance.deleteRole = function(e) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "deleteRole.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = e.role_id;

            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function() {
                    for (var i = 0; i < userInstance.admin.assignment.roles.length; i++) {
                        if (userInstance.admin.assignment.roles[i].role_id === e.role_id) {
                            userInstance.admin.assignment.roles.splice(i,1);
                            break;
                        }
                    }
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Delete role failed. Check your internet connection");
                });
        };

        userInstance.deleteFile = function(e) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "deleteFile.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.file_name = e.file_name;

            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function() {
                    for (var i = 0; i < userInstance.admin.assignment.files.length; i++) {
                        if (userInstance.admin.assignment.files[i].file_name === e.file_name) {
                            userInstance.admin.assignment.files.splice(i,1);
                            break;
                        }
                    }
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Delete file failed. Check your internet connection");
                });
        };

        userInstance.changeUserRole = function(e) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "changeUserRole.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = e.role_id;
            params.net_id = e.net_id;

            if (!params.assignment_id || params.assignment_id === 0 || params.assignment_id === '0') {
                alert("Invalid assignment ID. Refresh your browser.");
                return;
            }

            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function() {
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Change role failed. Check your internet connection");
                });
        };

        userInstance.getAssignment = function(assignment) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "getAssignment.php";
            var params = {};
            params.assignment_id = assignment.assignment_id;
            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    userInstance.admin.assignment.assignmnent_id = params.assignmnent_id;
                    userInstance.admin.assignment.roles = data.roles;
                    userInstance.admin.assignment.roles.unshift({role_name:'ALL', role_id:'0', role_description:''});
                    userInstance.admin.assignment.files = data.files;
                    userInstance.admin.assignment.members = data.members;
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Get assignment failed. Check your internet connection");
                });
        };

        userInstance.addFile = function(file_name) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "addFile.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = userInstance.admin.role_id;
            params.file_name = file_name
            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data) {
                    userInstance.admin.assignment.files.push(params);
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Add file failed. Check your internet connection");
                });
        };

        userInstance.setRole = function(role, net_id) {
            var uniqueSuffix = "?" + new Date().getTime();
            var php_script = "setRole.php";
            var params = {};
            params.assignment_id = userInstance.admin.assignment.assignment_id;
            params.role_id = role.role_id;
            params.net_id = net_id;
            $http({method: 'POST',
                url: userInstance.appDir + 'php/' + php_script + uniqueSuffix,
                data: params,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function() {
                }).
                error(function(data, status) {
                    alert("Error: " + status + " Set role failed. Check your internet connection");
                });
        };

        return userInstance;
  });
