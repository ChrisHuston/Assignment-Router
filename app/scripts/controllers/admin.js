'use strict';

angular.module('assignmentRouterApp')
  .controller('AdminCtrl', function ($scope, UserService, $timeout) {
        $scope.admin = UserService.admin;
        $scope.showGrid = UserService.admin.assignment.canvas_assignment;

        var ed;
        $timeout(function() {
            ed = new tinymce.Editor('role_description', {
                selector: "textarea",
                menubar: false,
                statusbar: true,
                relative_urls: false,
                remove_script_host: false,
                extended_valid_elements : "a[class|href|id|download|type|target|section-link|ng-click]",
                auto_focus: "role_description",
                content_css : "https://www.kblocks.com/app/scheduler/styles/tiny_style.css",
                formats: {custom_format : {table : 'table', classes: "table table-condensed table-bordered table-striped"}},
                plugins: [
                    "advlist autolink lists link charmap preview",
                    "searchreplace visualblocks code",
                    "insertdatetime table paste textcolor"
                ],
                toolbar: "bold italic | subscript superscript | styleselect | bullist numlist table outdent indent | link charmap code preview"
            }, tinymce.EditorManager);

            ed.render();
            //tinymce.get('role_description').setContent($scope.assignment.review_rubric);
        }, 500);


        $scope.addRole = function() {
            UserService.admin.role.role_description = tinyMCE.activeEditor.getContent();
            if (!UserService.admin.role.role_name) {
                alert("Enter a role name");
                return;
            }
            UserService.addRole();
        };

        $scope.saveRole = function() {
            $scope.admin.role.role_description = tinyMCE.activeEditor.getContent();
            UserService.saveRole();
        };


        $scope.addedFile = function(flowFile) {
            if (flowFile) {
                var file_name = flowFile.name.replace(/[ '"&]/g, '');
                flowFile.name = file_name;
            }
        };

        $scope.uploadComplete = function(flowFile) {
            var file_name = flowFile.name;
            UserService.addFile(file_name);
        };

        $scope.getAssignment = function() {
            UserService.getAssignment($scope.admin.assignment);
            UserService.admin.role_id = 0;
            $scope.showGrid = true;
        };

        $scope.addAssignment = function() {
            UserService.addAssignment($scope.admin.assignment);
        };

        $scope.updateAssignment = function() {
            UserService.updateAssignment($scope.admin.assignment);
        };

        $scope.deleteRole = function(e) {
            UserService.deleteRole(e);
        };

        $scope.rolesOptions = {data: 'admin.assignment.roles',
            multiSelect: false,
            showFilter:false,
            footerVisible: false,
            rowHeight:60,
            columnDefs:[
                {width:'50', cellClass:'c-cell', cellTemplate:'<div class="ngCellText colt{{$index}}"><button class="btn btn-link" ng-click="deleteRole(row.entity)"><i class="fa fa-trash-o"></i></button></div>'},
                {field:'role_name', displayName: 'Role Name', width:'200'},
                {field:'role_description', width:'*', displayName:'Description',
                    cellTemplate:'<div class="ngCellText" ng-class="col.colIndex()"><div class="description-cell" ng-bind-html="row.getProperty(col.field)"></div></div>'}
            ],
            beforeSelectionChange:function(itm) {
                if (!itm.selected) {
                    if (itm.entity.role_id !== '0') {
                        UserService.admin.role = angular.copy(itm.entity);
                        tinymce.get('role_description').setContent(itm.entity.role_description);
                    }
                }
                return true;
            }
        };

        $scope.changeFileRole = function(e) {
            UserService.changeFileRole(e);
        };

        $scope.deleteFile = function(e) {
            UserService.deleteFile(e);
        };

        var fileRoleEditor = '<select ng-change="changeFileRole(row.entity)" ng-model="row.entity.role_id" ng-options="r.role_id as r.role_name for r in admin.assignment.roles" ng-input="COL_FIELD"></select>';
        $scope.filesOptions = {data: 'admin.assignment.files',
            multiSelect: false,
            showFilter:false,
            footerVisible: false,
            columnDefs:[
                {width:'50', cellClass:'c-cell', cellTemplate:'<div class="ngCellText colt{{$index}}"><button class="btn btn-link" ng-click="deleteFile(row.entity)"><i class="fa fa-trash-o"></i></button></div>'},
                {field:'role_id', width:'200', displayName:'Role', cellTemplate:fileRoleEditor},
                {field:'file_name', displayName: 'File Name', width:'*'}
            ]
        };

        $scope.changeUserRole = function(e) {
            UserService.changeUserRole(e);
        };

        var userRoleEditor = '<select ng-change="changeUserRole(row.entity)" ng-model="row.entity.role_id" ng-options="r.role_id as r.role_name for r in admin.assignment.roles" ng-input="COL_FIELD"></select>';
        $scope.membersOptions = {data: 'admin.assignment.members',
            multiSelect: false,
            showFilter:true,
            footerVisible: false,
            columnDefs:[
                {field:'user_name', displayName: 'Name', width:'200'},
                {field:'section', displayName: 'Section', width:'100'},
                {field:'downloaded', displayName: 'Downloads', width:'100'},
                {field:'role_id', width:'*', displayName:'Role', cellTemplate:userRoleEditor}
            ]
        };


  });
