# Northern Australian Times

Installation:
To install the website on a local server the computer must have WAMP installed. Afterwards, the following steps should be followed (Refer to screenshots in the Project Report Appendix A for guidance):
1. Open both httpd.conf and httpd-vhosts.conf, change document root to the absolute document path of the Code folder and save
2. Restart the WAMP service to ensure the intended effects
3. Ensure both Apache and MySQL are running
4. Refer to Database folder of Northern-Australian-Times folder
5. Open localhost/phpmyadmin in web browser and import 1-createdb.sql to create the database, northern_australian_times_db
6. Load into the Northern-Australian-Times database and import 2-createschema.sql to create the schema
7. Import the 3-loaddata.sql to load the tables with initial data
8. Open web browser and go to localhost, if successful the web application should be running

Uninstallation:
To successfully uninstall the website and database, follow the instructions as per below (Refer to the screenshots in Project Report Appendix B for guidance):
1. Refer to the Database folder of Northern-Australian-Times folder
2. Go to localhost/phpmyadmin in web browser and import deletedb.sql to delete the database
3. Open both httpd.conf and httpd-vhosts.config files and change document root back to default
4. Delete Northern-Australian-Times folder
