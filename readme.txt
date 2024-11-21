User Authentication System with Login & Registration



Project Overview:
This project is a simple web-based user authentication system that includes:

-User Registration
-User Login
-Password Hashing
-Form Validation (Client-Side & Server-Side)
-SQL Injection Prevention
-Show/Hide Password Feature
-The application uses PHP for backend operations, MySQL for storing user data, and Bootstrap for frontend styling.

Prerequisites:
Before running the project, ensure the following:

-PHP 7.x or later installed on your system
-MySQL server running on localhost
-A web server like XAMPP or WAMP to run the PHP and MySQL components
-Bootstrap for styling (included via CDN)
-Steps to Set Up:


1. Database Setup:
Create a database in your MySQL server:
Database Name: login
Import the SQL file:
The SQL file login.sql contains the necessary table structure and queries for the database.
To import the database:
Open phpMyAdmin (or use MySQL command line).
Create a new database named login.
Go to the Import tab in phpMyAdmin, select the login.sql file, and click Go to import the table structure.



2. Configure Database Connection:
Open the config.php file in your project directory.
Modify the database connection details as follows:
php
Copy code
<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');  // Your MySQL username (default is 'root')
define('DB_PASSWORD', '');      // Your MySQL password (default is empty)
define('DB_NAME', 'login');     // The database you created
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
Ensure the config.php file is in the same directory as your other PHP files.



3. Running the Application:
Start your web server (Apache and MySQL) using XAMPP or WAMP.

Place all PHP files (including login.php, register.php, welcome.php, and config.php) in the htdocs directory (for XAMPP) or the root folder of your web server.

Open a web browser and go to the following URL to access the application:

http://localhost/[your-folder-name]/register.php
Registration:

Navigate to register.php and fill in the registration form with your details (Username, Email, Phone Number, Date of Birth, Password).
Click Register to create an account. The data will be securely saved in the MySQL database after password hashing.
Login:

After successful registration, you can log in via login.php.
Enter the username and password to authenticate and access the welcome.php page.
The "Show Password" feature is available on the login form to toggle the visibility of the password.
Session Handling:

After a successful login, the session is started, and you will be redirected to the welcome.php page.
If the user is already logged in, they are redirected directly to the welcome page.
The session is used to maintain the logged-in state across different pages.
Logout:

To log out, click on the Logout button on the welcome.php page. It will end the session and redirect you to the login page.




4. File Structure:
Your project directory should have the following structure:
/[Project Folder]
    ├── config.php           // Database connection settings
    ├── login.php            // User login page
    ├── register.php         // User registration page
    ├── welcome.php          // Welcome page for logged-in users
    ├── logout.php           // Logout functionality
    ├── login.sql            // SQL file with table structure
    ├── README.txt           // This file (README)



5. Features:
Registration Form:

Fields: Username, Email, Phone Number, Date of Birth, Password
Validation: Ensures that all fields are filled, and the password meets security requirements (uppercase, lowercase, number, special character).
The password is hashed using password_hash() before saving it to the database.
Login Form:

Fields: Username, Password
Validation: The username and password are checked against the database, and a session is created if the login is successful.
Password visibility toggle (Show/Hide).
Password Security:

The application uses password hashing to store passwords securely using password_hash().
On login, the password_verify() function is used to check the hashed password.
Session Management:

Session_start() is used to handle user sessions. Once logged in, the user's session is maintained across the website.



6. Security Considerations:
SQL Injection Prevention: The application uses prepared statements (with mysqli_prepare() and mysqli_stmt_bind_param()) to securely interact with the database.
Cross-Site Scripting (XSS) Prevention: User inputs are sanitized using htmlspecialchars() to prevent XSS attacks.
Password Hashing: Passwords are stored securely using bcrypt hashing (password_hash()), ensuring sensitive data is not stored in plaintext.
Troubleshooting:
Error 1045: If you get an error connecting to the database, ensure that the MySQL username and password are correct in the config.php file.
Page Not Found: Ensure that the PHP files are placed in the correct directory (htdocs for XAMPP or root folder for other servers).
PHP Errors: If any PHP errors occur, check the error logs for detailed messages and ensure that PHP is configured correctly on your server.

Credits:
Created and Programmed by: LeoTechWhiz / Roshan Gautam


Conclusion:
This project demonstrates a secure login and registration system, incorporating important web security measures such as input sanitization, password hashing, and session handling. It’s designed to be easily set up and extended for more advanced features.