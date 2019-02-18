Project is written in Symfomy 3.4, PHP 7.2, Apache 2.4, MySQL 5.6.
Has one external bundle Curl\Curl.
Project's folder is "test". \test\web must be web server's "Document Root".
Files:
\test\src\AppBundle\Entity\Group.php - group entity
\test\src\AppBundle\Entity\User.php  - user entity
\test\src\AppBundle\Controller\GroupAPIController.php - group controller
\test\src\AppBundle\Controller\UserAPIController.php  - user controller
\test\src\AppBundle\Command\* - console commands, have prefix "app:" in console commands list

Database name "test", database structure recreated with "doctrine:schema:create" command.

How to use controllers (user data enclosed with {}):
Create user:
<domain>/userapi/createuser?name={username}&email={email}
Read user:
<domain>/userapi/showuser/{uid}
Update user:
<domain>/userapi/modifyuser/{uid}?name={username}&email={email}
or
<domain>/userapi/modifyuser?id={uid}&name={username}&email={email}
Delete user:
<domain>/userapi/deleteuser/{uid}
Add group to user:
<domain>/userapi/addgrouptouser/{uid}/{gid}
Remove group from user:
<domain>/userapi/deletegroupfromuser/{uid}/{gid}
List all users:
<domain>/userapi/listuser
Create group:
<domain>/userapi/creategroup?name={groupname}
Read group:
<domain>/userapi/showgroup/{gid}
Update group:
<domain>/userapi/modifygroup/{gid}?name={groupname}
Delete group:
<domain>/userapi/deletegroup/{gid}
List all groups:
<domain>/userapi/listgroup
Report with the list of users of each group:
/userapi/report
