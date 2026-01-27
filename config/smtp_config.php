<?php
// SMTP Configuration for PHPMailer
// Update these settings with your email provider details

return [
    'host' => 'smtp.gmail.com',           // SMTP server (Gmail, Outlook, etc.)
    'username' => 'prakashdharaviya2005@gmail.com',  // Your email address
    'password' => 'gxjk ednj ultd otad',     // Your email app password (not regular password)
    'port' => 587,                         // SMTP port (587 for TLS, 465 for SSL)
    'encryption' => 'tls',                 // 'tls' or 'ssl'
    'from_email' => 'prakashdharaviya2005@gmail.com', // From email address
    'from_name' => 'Pure Fit',             // From name
    'reply_to' => 'prakashdharaviya2005@gmail.com'    // Reply-to email
];

/*
=== HOW TO SETUP SMTP ===

1. FOR GMAIL:
   - Enable 2-factor authentication
   - Go to: https://myaccount.google.com/apppasswords
   - Generate an "App Password"
   - Use the app password (16 characters) in the password field above
   - Example: 
     username: 'john@gmail.com'
     password: 'abcd efgh ijkl mnop' (without spaces)

2. FOR OUTLOOK/HOTMAIL:
   - Use: smtp.office365.com
   - Port: 587
   - Encryption: tls
   - Use your Outlook email and password

3. FOR YAHOO:
   - Use: smtp.mail.yahoo.com
   - Port: 587
   - Encryption: tls
   - Generate an app password from Yahoo settings

4. FOR OTHER PROVIDERS:
   - Check your email provider's SMTP settings
   - Update host, port, and encryption accordingly

=== TESTING ===

After updating the config, test by:
1. Registering a new account
2. Check if verification email arrives
3. Check error logs if not working

=== TROUBLESHOOTING ===

If emails don't send:
1. Check if credentials are correct
2. Make sure 2FA is enabled and using app password
3. Check firewall/antivirus blocking SMTP
4. Check PHP error logs: error_log('SMTP Error: ' . $e->getMessage());

=== SECURITY NOTES ===

- Never commit real passwords to git
- Use environment variables in production
- Use app passwords, not main passwords
- Keep this file secure (chmod 600)
*/
?>
