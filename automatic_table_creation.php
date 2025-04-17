<?php
// Database credentials
$hostname = 'localhost';
$username = 'mrx';
$password = '2905';
$database = 'projectx';

// Connect to the database
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// SQL statements
$createTableStatements = [
    "CREATE TABLE IF NOT EXISTS external_abilities(id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL,category VARCHAR(100) NOT NULL,file_name VARCHAR(255) NOT NULL)",
    "CREATE TABLE IF NOT EXISTS password_resets (id INT NOT NULL AUTO_INCREMENT, email VARCHAR(255) NOT NULL,token VARCHAR(255) NOT NULL,expires_at DATETIME NOT NULL,PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS subjects (course varchar(20), subject varchar(255),collegeCode varchar(10))",
    "CREATE TABLE IF NOT EXISTS class_incharges (class_id varchar(30), faculty_id varchar(255))",
    "CREATE TABLE IF NOT EXISTS admin_details (id INT NOT NULL AUTO_INCREMENT,collegeCode VARCHAR(10),name VARCHAR(100),contact VARCHAR(15),email VARCHAR(100),staffType VARCHAR(50),designation VARCHAR(50),password VARCHAR(255),desig_abb VARCHAR(10),staff_abb VARCHAR(10),userID VARCHAR(30),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS certificates (id INT NOT NULL AUTO_INCREMENT, class_id varchar(20), reg_num varchar(20), certificate_name varchar(255), certificate_description text, file_path varchar(255),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS club_chat_table (id INT NOT NULL AUTO_INCREMENT, projectCode varchar(255), reg_num varchar(255), chat text, post_time datetime,PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS club_files (id INT NOT NULL AUTO_INCREMENT, projectName varchar(255), description text, fileName varchar(255), question varchar(255), options text,PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS club_questions (id INT NOT NULL AUTO_INCREMENT, projectCode varchar(50), username varchar(50), question text, option1 varchar(255), option2 varchar(255), option3 varchar(255), option4 varchar(255), option5 varchar(255), correctOption varchar(255), imagePath varchar(255),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS club_responses (projectCode varchar(50), responder_reg_num varchar(20), full_name varchar(255), phone_num varchar(15), status_of_request varchar(15), description text, reg_num varchar(20))",
    "CREATE TABLE IF NOT EXISTS club_uploads (id INT NOT NULL AUTO_INCREMENT, projectName varchar(255), projectCode varchar(50), description text, filePath varchar(255), reg_num varchar(20),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS clv_videos (video_id INT NOT NULL AUTO_INCREMENT, class_id varchar(20), subject varchar(255), lesson varchar(255), topic varchar(255), video_path varchar(255),PRIMARY KEY (video_id))",
    "CREATE TABLE IF NOT EXISTS fees (reg_num varchar(255), tution_fee bigint, building_fund bigint, crt_fee bigint, bus_fee bigint, hostel_fee bigint, total_fee bigint, amount_paid bigint, due_amount bigint, class_id varchar(20))",
    "CREATE TABLE IF NOT EXISTS file_uploads (id INT NOT NULL AUTO_INCREMENT, username varchar(255), description text, file_path varchar(255), upload_timestamp timestamp, reg_num varchar(20),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS files (file_id int not null, file_name varchar(255), upload_timestamp timestamp,PRIMARY KEY (file_id))",
    "CREATE TABLE IF NOT EXISTS hod_registration (clg_name varchar(255), clg_code varchar(10), stream varchar(10), branch varchar(10), user_id varchar(255), name varchar(255), phone_num varchar(15), email varchar(255), user_pass varchar(255), photo_path varchar(255), sub_time datetime)",
    "CREATE TABLE IF NOT EXISTS images (id INT NOT NULL AUTO_INCREMENT, name varchar(255), description text, filepath varchar(255),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS library (id INT NOT NULL AUTO_INCREMENT, category varchar(255), course varchar(255), branch varchar(255), year int, book_name varchar(255), description text, image_path varchar(255), file_path varchar(255), upload_date timestamp,PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS master_faculty (regulation varchar(5), clg_code varchar(10), facultyName varchar(255), contactNumber varchar(20), whatsappNumber varchar(20), contactEmail varchar(255), stream varchar(50), subject_name varchar(255), subject_code varchar(20), subject_abb varchar(20), password varchar(255), confirmPassword varchar(255), faculty_id varchar(255), profile_photo varchar(255))",
    "CREATE TABLE IF NOT EXISTS master_sub (regulation varchar(20), clg_code varchar(5), stream varchar(20), branch varchar(20), year varchar(20), semester varchar(20), section varchar(1), subject_name varchar(255), subject_code varchar(20), subject_abb varchar(20), class_id varchar(25))",
    "CREATE TABLE IF NOT EXISTS meeting (id INT NOT NULL AUTO_INCREMENT, class_id varchar(20), meeting_link text, meeting_date date, meeting_time time,PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS meetings (id INT NOT NULL AUTO_INCREMENT, class_id varchar(20), meeting_link varchar(255), meeting_date date, meeting_time time,PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS parent_feedback (reg_num varchar(255), class_id varchar(255), feedback text)",
    "CREATE TABLE IF NOT EXISTS parent_reg (user_id varchar(255), full_name varchar(255), mobile_number varchar(15), user_pass varchar(255))",
    "CREATE TABLE IF NOT EXISTS pri_dir_registration (collegeName varchar(255), collegeCode varchar(50), course varchar(50), fullName varchar(100), contact varchar(255), email varchar(100), designation varchar(50), password varchar(255), user_id varchar(20), profile_photo varchar(255))",
    "CREATE TABLE IF NOT EXISTS slides (id INT NOT NULL AUTO_INCREMENT, title varchar(255), content varchar(6555), image_path varchar(255),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS staff_details (id INT NOT NULL AUTO_INCREMENT, collegeCode varchar(10), name varchar(100), contact varchar(15), email varchar(100), staffType varchar(50), designation varchar(50), password varchar(255), desig_abb varchar(10), staff_abb varchar(10), userID varchar(30),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS stdattd (reg_num varchar(15), attendance_status varchar(1), attendance_date datetime)",
    "CREATE TABLE IF NOT EXISTS stdreg (clg_name text, clg_code varchar(10), course varchar(20), full_name varchar(255), reg_num varchar(255) NOT NULL, sem_year varchar(20), semester varchar(20), branch varchar(255), section varchar(255), class_id varchar(255), phone_num varchar(255), parent_name varchar(255), parent_phone_num varchar(13), email varchar(255), blood_g varchar(4), user_pass varchar(255), cnf_pass varchar(255), aadhar_num varchar(255), pan_num varchar(255), profile_photo varchar(255), sub_time datetime, PRIMARY KEY (reg_num))",
    "CREATE TABLE IF NOT EXISTS t_auth (class_id varchar(50), class_id_pass varchar(50))",
    "CREATE TABLE IF NOT EXISTS teacher_info (id INT NOT NULL AUTO_INCREMENT, file_name varchar(255), file_path varchar(255), class_id varchar(20),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS think_diff_comments (reg_num varchar(20), description text, id int)",
    "CREATE TABLE IF NOT EXISTS thoughts (title varchar(255), description text, created_at timestamp, video_path varchar(255), reg_num varchar(20))",
    "CREATE TABLE IF NOT EXISTS thoughts_profile (username varchar(255), profile_photo varchar(255), bio text, reg_num varchar(20))",
    "CREATE TABLE IF NOT EXISTS time_table (id INT NOT NULL AUTO_INCREMENT, file_path varchar(255), file_name varchar(255), class_id varchar(20),PRIMARY KEY (id))",
    "CREATE TABLE IF NOT EXISTS timetable (id INT NOT NULL AUTO_INCREMENT, class_id varchar(50), day varchar(10), period int, subject varchar(50), faculty_id varchar(255),PRIMARY KEY (id))"
];

// Execute each SQL statement
foreach ($createTableStatements as $sql) {
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Tables created successfully');window.location.href='admin_home.php';</script>";
    } else {
        echo "<script>alert('Error creating table: " . $mysqli->error . "');window.location.href='admin_home.php';</script>";
    }
}

// Close the connection
$mysqli->close();