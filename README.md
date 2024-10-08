# OAuth2.0 API created with PHP/cURL

This app demonstrates a simple method to build a login system where users log in using their existing accounts on another system. The app does the following:
* Determines if the user's login succeeded
* Create a session
* Generates a token with one-hour expire time
* Store the token in the database
* Redirect the user to another page with token

The redirect page compares the session token with the token stored in the database. If the match is found, the page content will show up. Otherwise, the user is redirected back to the login page.

## Create a project directory
If you are using XAMPP, prepare a project directory on a local drive. For example, the file path C:\xampp\htdocs\oauth2 is accessible via http://localhost/oauth2 once the Apache web server has been started.

## Create a database
This app works with the registration and login system previously used in the class. If you don't have the project files with you, create aa 'accounts' database and use this SQL to generate a 'users' table.

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE access_tokens (
  id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  token varchar(64) NOT NULL,
  expires_at datetime NOT NULL
);

If the 'users' table is empty, use register.php to create one account for testing. You may add a new record manually on phpMyAdmin.

## Web components
### config.php
Keep database connection credentials in this file.

### login_form.html
Open this page to log in. The form use **authorize.php** to handle login. The **redirect_uri** is sent as a parameter.

### authorize.php
This file verifies if the login succeeded. If so, it
* Generates a session variable 'user_id' in which its value is set to the user id from the database
* Generates a random string to be used as an access token
* Store the token in the sesssion
* Set token expiration to one hour
* Insert token into database along with the expire time
* Redirect the user to the redirect URI with the token

### redirect_page.php
This page checks if:
* The $_SESSION['user_id'] is set, implying the user successfully logged in
* The $_GET['access_token'] is set, implying the access token was sent with the request
* The access token matches with its copy in the database, and it is not expired or expires_at > NOW()

If so, the page content shows up. Otherwise, the user is redirected back to the login page.

### logout.php
This file contains a logout script which destroys the login session and redirect the user back to the login page.
