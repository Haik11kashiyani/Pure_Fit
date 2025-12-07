<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require_once __DIR__ . '/../PHPMailer/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';

// Load SMTP configuration
$smtp_config = require_once __DIR__ . '/../config/smtp_config.php';

function send_verification_email_smtp($email, $token) {
    global $smtp_config;
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtp_config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_config['username'];
        $mail->Password   = $smtp_config['password'];
        $mail->SMTPSecure = $smtp_config['encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtp_config['port'];
        
        // Recipients
        $mail->setFrom($smtp_config['from_email'], $smtp_config['from_name']);
        $mail->addAddress($email);
        $mail->addReplyTo($smtp_config['reply_to'], 'Pure Fit Support');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification for Pure Fit';
        
        // Generate verification URL
        $host = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
        $verify_url = "http://" . $host . $path . "/verify.php?token=" . $token;
        
        // Email template
        $mail->Body = '
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
                    color: #713600;
                    font-size: 28px;
                }
                .logo {
                    color: #713600;
                    font-weight: bold;
                }
                .content {
                    padding: 20px 0;
                    color: #555555;
                    line-height: 1.6;
                }
                .button-container {
                    text-align: center;
                    padding: 30px 0;
                }
                .button {
                    background: linear-gradient(135deg, #713600, #713600);
                    color: #ffffff;
                    padding: 15px 30px;
                    text-decoration: none;
                    border-radius: 25px;
                    font-size: 16px;
                    font-weight: bold;
                    display: inline-block;
                    transition: all 0.3s ease;
                }
                .button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(99, 107, 47, 0.3);
                }
                .footer {
                    text-align: center;
                    padding-top: 20px;
                    border-top: 1px solid #dddddd;
                    color: #999999;
                    font-size: 12px;
                }
                .security-note {
                    background-color: #fff3cd;
                    border: 1px solid #ffeaa7;
                    border-radius: 4px;
                    padding: 10px;
                    margin: 20px 0;
                    color: #856404;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1><span class="logo">Pure</span> Fit</h1>
                    <h2>Email Verification</h2>
                </div>
                <div class="content">
                    <p>Thank you for registering with <strong>Pure Fit</strong>! We\'re excited to have you join our fitness community.</p>
                    
                    <p>To complete your registration and activate your account, please verify your email address by clicking the button below:</p>
                    
                    <div class="security-note">
                        <strong>Security Note:</strong> This verification link will expire in 24 hours for your security.
                    </div>
                </div>
                <div class="button-container">
                    <a href="' . $verify_url . '" class="button">Verify Email Address</a>
                </div>
                <div class="content">
                    <p>Once verified, you\'ll be able to:</p>
                    <ul>
                        <li>✓ Login to your account</li>
                        <li>✓ Browse our fitness apparel collection</li>
                        <li>✓ Add items to your cart</li>
                        <li>✓ Save your favorite products</li>
                        <li>✓ Track your orders</li>
                    </ul>
                    
                    <p>If the button above doesn\'t work, you can also copy and paste this link into your browser:</p>
                    <p style="word-break: break-all; color: #713600; font-family: monospace;">' . $verify_url . '</p>
                </div>
                <div class="footer">
                    <p>If you did not create an account with Pure Fit, please ignore this email or contact our support team.</p>
                    <p>&copy; 2025 Pure Fit. All rights reserved.</p>
                    <p>Building a healthier, stronger you, one workout at a time.</p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        $mail->AltBody = 'Thank you for registering with Pure Fit! Please visit this link to verify your email: ' . $verify_url;
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log('Email sending failed: ' . $mail->ErrorInfo);
        return false;
    }
}

function send_welcome_email($email, $first_name) {
    global $smtp_config;
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtp_config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_config['username'];
        $mail->Password   = $smtp_config['password'];
        $mail->SMTPSecure = $smtp_config['encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtp_config['port'];
        
        // Recipients
        $mail->setFrom($smtp_config['from_email'], $smtp_config['from_name']);
        $mail->addAddress($email);
        $mail->addReplyTo($smtp_config['reply_to'], 'Pure Fit Support');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Pure Fit - Your Account is Activated!';
        
        $mail->Body = '
        <html>
        <head>
            <title>Welcome to Pure Fit</title>
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
                    color: #713600;
                    font-size: 28px;
                }
                .logo {
                    color: #713600;
                    font-weight: bold;
                }
                .content {
                    padding: 20px 0;
                    color: #555555;
                    line-height: 1.6;
                }
                .button-container {
                    text-align: center;
                    padding: 30px 0;
                }
                .button {
                    background: linear-gradient(135deg, #713600, #713600);
                    color: #ffffff;
                    padding: 15px 30px;
                    text-decoration: none;
                    border-radius: 25px;
                    font-size: 16px;
                    font-weight: bold;
                    display: inline-block;
                    transition: all 0.3s ease;
                }
                .button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(99, 107, 47, 0.3);
                }
                .footer {
                    text-align: center;
                    padding-top: 20px;
                    border-top: 1px solid #dddddd;
                    color: #999999;
                    font-size: 12px;
                }
                .success-box {
                    background-color: #d4edda;
                    border: 1px solid #c3e6cb;
                    border-radius: 4px;
                    padding: 15px;
                    margin: 20px 0;
                    color: #155724;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1><span class="logo">Pure</span> Fit</h1>
                    <h2>Welcome Aboard!</h2>
                </div>
                <div class="content">
                    <div class="success-box">
                        <strong>🎉 Congratulations ' . htmlspecialchars($first_name) . '!</strong><br>
                        Your email has been successfully verified and your Pure Fit account is now active!
                    </div>
                    
                    <p>Welcome to the Pure Fit family! We\'re thrilled to have you join our community of fitness enthusiasts.</p>
                    
                    <p>Your account is now fully activated and you can:</p>
                    <ul>
                        <li>✓ Login to your account anytime</li>
                        <li>✓ Shop our premium fitness apparel collection</li>
                        <li>✓ Add items to your shopping cart</li>
                        <li>✓ Save your favorite products for later</li>
                        <li>✓ Track your orders and delivery status</li>
                        <li>✓ Receive exclusive offers and updates</li>
                    </ul>
                </div>
                <div class="button-container">
                    <a href="http://' . $_SERVER['HTTP_HOST'] . '/Pure_Fit/login.php" class="button">Login to Your Account</a>
                </div>
                <div class="content">
                    <p><strong>Get Started:</strong></p>
                    <ol>
                        <li>Click the button above to login</li>
                        <li>Browse our collection of fitness apparel</li>
                        <li>Add your favorite items to cart</li>
                        <li>Complete your purchase</li>
                    </ol>
                    
                    <p>Need help? Our support team is always here to assist you on your fitness journey.</p>
                </div>
                <div class="footer">
                    <p>Thank you for choosing Pure Fit!</p>
                    <p>&copy; 2025 Pure Fit. All rights reserved.</p>
                    <p>Building a healthier, stronger you, one workout at a time.</p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        $mail->AltBody = 'Welcome to Pure Fit! Your account is now active. Login here: http://' . $_SERVER['HTTP_HOST'] . '/Pure_Fit/login.php';
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log('Welcome email sending failed: ' . $mail->ErrorInfo);
        return false;
    }
}

function send_password_reset_otp($email, $first_name, $otp) {
    global $smtp_config;
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtp_config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_config['username'];
        $mail->Password   = $smtp_config['password'];
        $mail->SMTPSecure = $smtp_config['encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtp_config['port'];
        
        // Recipients
        $mail->setFrom($smtp_config['from_email'], $smtp_config['from_name']);
        $mail->addAddress($email, $first_name);
        $mail->addReplyTo($smtp_config['reply_to'], 'Pure Fit Support');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Pure Fit Password Reset Code';
        
        $mail->Body = '
        <html>
        <head>
            <title>Password Reset OTP</title>
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
                .otp-box {
                    text-align: center;
                    background: #f8f9fa;
                    border: 2px dashed #636B2F;
                    border-radius: 12px;
                    padding: 20px;
                    margin: 25px 0;
                    font-size: 32px;
                    letter-spacing: 8px;
                    color: #3D4127;
                    font-weight: bold;
                }
                .content {
                    color: #555555;
                    line-height: 1.6;
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
                    <h1>Pure Fit Password Reset</h1>
                </div>
                <div class="content">
                    <p>Hello ' . htmlspecialchars($first_name) . ',</p>
                    <p>We received a request to reset the password for your Pure Fit account. Use the OTP below to verify your request:</p>
                </div>
                <div class="otp-box">' . $otp . '</div>
                <div class="content">
                    <p>This OTP is valid for 10 minutes. If you did not request a password reset, please ignore this email.</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' Pure Fit. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        $mail->AltBody = 'Hello ' . $first_name . ', your Pure Fit password reset OTP is: ' . $otp . '. It will expire in 10 minutes.';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Password reset email failed: ' . $mail->ErrorInfo);
        return false;
    }
}
?>
