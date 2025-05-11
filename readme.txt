Configuration for fake sendmail
Step 1: open C:\xampp\php\php.ini
Step 2: change ;SMTP=localhost -> SMTP=smtp.gmail.com
Step 3: change ;smtp_port=25 -> smtp_port=587
Step 4: change ;sendmail_from = me@example.com -> sendmail_from = {sender email}
Step 5: change ;sendmail_path -> C:\xampp\sendmail\sendmail.exe
Step 6: open C:\xampp\sendmail\sendmail.ini
Step 7: change ;SMTP=localhost -> SMTP=smtp.gmail.com
Step 8: change ;smtp_port=25 -> smtp_port=587
Step 8: fill in auth_username=your_email_address_here
Step 9: fill in auth_password=your_password_here

Database Configuration MySQl
CREATE TABLE cart_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    total_amount FLOAT NOT NULL,
    payment_status ENUM('completed', 'pending') NOT NULL
);

 
