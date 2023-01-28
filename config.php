<?php

// DATABASE CREDENTIALS
define("DB_HOST", 'localhost');
define("DB_USER", 'root');
define("DB_PASSWORD", '');
define("DB_NAME", 'king');

$db_connection_error;
$manager_table_creation_error;

// connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create users table
$sql_create_users_table = "CREATE TABLE Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'USER';
    password VARCHAR(255) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

// create Users table
if ($conn->query($sql_create_users_table) !== TRUE) {
    // echo "Error creating table: " . $conn->error;
}

// sql to create managers table
$sql_create_managers_table = "CREATE TABLE Managers (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'MANAGER';
    password VARCHAR(255) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

// create Managers table
if ($conn->query($sql_create_managers_table) !== TRUE) {
    // echo "Error creating table: " . $conn->error;
}

// sql to create managers table
$sql_create_admins_table = "CREATE TABLE Admins (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'ADMIN';
    password VARCHAR(255) NOT NULL,    
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

// create Managers table
if ($conn->query($sql_create_admins_table) !== TRUE) {
    // echo "Error creating table: " . $conn->error;
}

// sql to create managers table
$sql_create_locations_table = "CREATE TABLE locations (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
    )";

// create Managers table
if ($conn->query($sql_create_locations_table) !== TRUE) {
    // echo "Error creating table: " . $conn->error;
}

// sql to create managers table
$sql_create_types_table = "CREATE TABLE types (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
    )";

// create Managers table
if ($conn->query($sql_create_types_table) !== TRUE) {
    // echo "Error creating table: " . $conn->error;
}

// sql to create managers table
$sql_create_prperties_table = "CREATE TABLE properties (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    property_description VARCHAR(255) NOT NULL,
    price VARCHAR(255) NOT NULL,
    property_image LONGBLOB NOT NULL,  

    property_location INT(6) UNSIGNED,
    FOREIGN KEY (property_location) REFERENCES locations(id),

    property_type INT(6) UNSIGNED,
    FOREIGN KEY (property_type) REFERENCES types(id),
    
    manager INT(6) UNSIGNED,
    FOREIGN KEY (manager) REFERENCES types(id),
     
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

// create Managers table
if ($conn->multi_query($sql_create_prperties_table) !== TRUE) {
    // DISPLAY ERROR HERE IF DB HAS NOT BEEN CREATED
    // echo "Error creating table: " . $conn->error;
}

// echo 'Connected successfully';
// mysqli_close($conn);

?>