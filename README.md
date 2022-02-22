# MovieBookingSystem

## General Functionality
In this system there are three different users, each with different type of accounts:

### 1) Customer
Can create/login to an account. After logging in customer can book tickets to available movie showings and make food orders for the corresponding showing Customers can also view their tickets and account information.

### 2) Employee
Can create/login to an account. After logging in employee can view food orders of the theatre location they work at, and change the food order's status. Employees can also view their account information. 

### 3) Manager
After logging in manager can add and remove: movies and movie showings for their theatre. Manager can also see a summary of their account information.


## How to Compile and Run
Copy the project folder in www of AppServ (i.e. C:\AppServ\www\Project).

Load the sql file into your database. In the file Project\Database\connection.php,
change the username and password to your own mysql user and password.
$con=mysqli_connect("127.0.0.1", "<username here>", "<password here>").

Open browser and in the search bar type localhost.
Then type Project and the path to the php file name you want to open. (i.e. http://localhost/Project/Customer Login/filename.php).
Below is the paths for the php files you want to execute:

Customer Login: http://localhost/Project/Customer Login/existingCustomerAccountF.php

Employee Login: http://localhost/Project/Employee Login/existingEmployeeAccountF.php

User guide of how to use website is included in the FINAL REPORT.
