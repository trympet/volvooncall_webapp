# A webapp for setting custom timers for Volvo On Call
Webapp for setting attributes for your Volvo car via the Volvo On Call API

# Requirements
1. MySql
2. PHP
3. Your favourite webserver

# Installation
1. Download and install to the root of your web directory.
2. Browse to 'app/config/database.php' and edit database params to your mysql server
3. Run the database seeder
4. Add the PHP task to your crontab with the following command:<br>
  echo 'www-data php /<PATH TO YOUR INSTALL DIRECTORY>/app/scripts/check_timer.php' >> /etc/crontab
5. Login to the app with the default credentials<br> 
Username: 'admin' <br>
Password: 'admin'<br>
Then navigate to 'configure' and add your Volvo On Call username, password and region.

