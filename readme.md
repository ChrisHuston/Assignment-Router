Assignment Router
======
**Assignment Router** is an LTI app to create roles and files for each role that go to different students. For example, a negotiation exercise has students read different readings to prepare for an in-class negotiation. Can have any number of roles with any number of files for each role. Integrated into an assignment with optional complete grade passback.


## Usage
0. Click Settings in the Course menu
0. Click the Apps tab
0. Click the View App Configurations button
0. Click Add App
0. For Configuration Type, select By URL
0. Enter the name that will appear in the Course navigation menu, i.e. Role Router
0. Consumer key =  your key
0. Shared Secret =  your secret
0. Config URL = https://your domain here/assignment_router/config/assignment_router.xml
0. Click Submit
0. Click the app in the course navigation and click Users in the top menu or the assignment if you are using the Assignment configuration.
0. For Assignments, select Submission Type as External Tool and select the Role Router app name you set in step 5. A grade of "complete" will be passed back when a student accesses the assignment so set the points value to 1 or some other non-zero value.
0. Click Users in the top menu bar
0. Click Get Sections
0. Click Get Students. If there are any changes in the course enrollment, you will need to click Get Students again to sync with the changes.