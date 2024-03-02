<?php 
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

function sendemail_verify($name, $email, $number, $want_to, $message) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();                          // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';     // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                 // Enable SMTP authentication
    $mail->Username   = 'schoolcode2014@gmail.com'; // SMTP username
    $mail->Password   = 'qqrjlstjjoxxzcpi';   // App-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // Enable implicit TLS encryption
    $mail->Port       = 465;                  // TCP port to connect to

    // Recipients
    $mail->setFrom('schoolcode2014@gmail.com', 'Sender Name');
    $mail->addAddress($email);    

    // Content
    $mail->isHTML(true);                      // Set email format to HTML
    $mail->Subject = 'SchoolCode Africa';  //the email head
    //the email body/template
    $email_template =  <<<EOT
    <div class="success_body">
        <div class="success_head">
            <h1>SchoolCode Africa</h1>
        </div>
        <div class="success_message">
            <h3>We have received your message, and will get back to you soon</h3>
            <h2>Thank you for choosing SchoolCode Africa</h2>
            <a href="#">SchoolCode Africa</a>
        </div>
    </div>
EOT;
    $mail->Body = $email_template;
    
    // Attempt to send the email
    if ($mail->send()) {
        echo 'Message has been sent';
    } else {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

//collect data from the form
if (isset($_POST['submit_btn'])) {
    $name = htmlspecialchars($_POST['name']); 
    $phone = htmlspecialchars($_POST['phone']); 
    $email = htmlspecialchars($_POST['email']);  
    $want_to = htmlspecialchars($_POST['want_to']);
    $message = htmlspecialchars($_POST['message']);
    
    // Insert user data into the database
    $query = "INSERT INTO details (name, email, phone, want_to, message) VALUES (?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $name, $email, $phone, $want_to, $message);
    //conditional statement for the prepare statement
    if ($stmt->execute()) { 
        sendemail_verify($name, $email, $phone, $want_to, $message);
        $_SESSION['status'] = "Message Sent Successfully. Thank you";
        header("Location: success.php");
        exit; 
    } else {
        $_SESSION['status'] = "Failed";
        header("Location: contact.php");
        exit;
    }
}
