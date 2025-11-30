<?php
//- Temporarily disable error reporting for mail function
error_reporting(0);

// Function to send a verification email
function send_verification_email($email, $token) {
    // Check if the mail function exists and is enabled
    if (function_exists('mail')) {
        // Recipient
        $to = $email;

        // Subject
        $subject = 'Email Verification for Pure Fit';

        // Message with a verification button
        $message = '
        <html>
        <head>
            <title>Email Verification</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .header {
                    text-align: center;
                    padding-bottom: 20px;
                    border-bottom: 1px solid #dddddd;
                }
                .header h1 {
                    margin: 0;
                    color: #333333;
                }
                .content {
                    padding: 20px 0;
                    color: #555555;
                    line-height: 1.6;
                }
                .button-container {
                    text-align: center;
                    padding: 20px 0;
                }
                .button {
                    background-color: #007bff;
                    color: #ffffff;
                    padding: 12px 25px;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                }
                .footer {
                    text-align: center;
                    padding-top: 20px;
                    border-top: 1px solid #dddddd;
                    color: #999999;
                    font-size: 12px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Pure Fit Email Verification</h1>
                </div>
                <div class="content">
                    <p>Thank you for registering with Pure Fit! To complete your registration, please verify your email address by clicking the button below.</p>
                </div>
                <div class="button-container">
                    <a href="http://localhost/Pure_Fit/verify.php?token=' . $token . '" class="button">Verify Email</a>
                </div>
                <div class="footer">
                    <p>If you did not create an account, please ignore this email.</p>
                    <p>&copy; 2025 Pure Fit. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ';

        // Headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <no-reply@purefit.com>' . "\r\n";

        // Attempt to send the email and return the result
        return mail($to, $subject, $message, $headers);
    }
    
    // Return false if mail function is not available
    return false;
}
?>
