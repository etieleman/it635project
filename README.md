# IT 635 Project - Enterprise Management System
This is a system which a company can use to track and view information regarding their technological assets, such as computers, phones, monitors, and so on. System allows for employees to request the use of assets, as well as the purchase of new assets and retirement of old ones, and allows a manager to approve or deny these requests.

For testing, the following credentials can be used to log in with the included .sql database:

fmg10/password10 - Manager Privileges

lol6/password6 - Employee Privileges

Code in functions.php uses the mysql credentials root/it635root to connect to database it635; change this if necessary

#Final Deliverable Changes

Live replications have been set up between a slave server running on AWS

Daily incremenetal backups using the binary logs, along with weekly full backups, are performed with root cron; the contents of the crontab file are in this repository

Mongo table "Descriptions" containing technical information about assets is implemented in mlab

Stored procedure for asset adding function is implemented in the code; sql file containing the procedure is in the repository

UML Diagram is in the repository
