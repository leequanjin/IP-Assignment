<!-- Author     : keekeshen -->
<?php

require_once '../models/AdminModel.php';
require_once '../apis/admin_mail_service.php';

class AdminController {

    public function handleRequest() {
        session_start();
        $action = $_POST['action'] ?? $_GET['action'] ?? '';

        if ($action === 'register') {
            $this->register();
        } elseif ($action === 'login') {
            $this->login();
        }
    }

    private function register() {
        $name = htmlspecialchars(trim($_POST['name']));
        $age = intval($_POST['age']);
        $gender = htmlspecialchars(trim($_POST['gender']));
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $role = 'staff';

        if (empty($name) || empty($age) || empty($gender) || empty($email) || empty($password)) {
            $_SESSION['message'] = 'All fields are required.';
        } elseif ($age <= 0) {
            $_SESSION['message'] = 'Age must be a positive number.';
        } elseif ($age >= 100) {
            $_SESSION['message'] = 'Maximum age is 100.';
        } elseif (!in_array($gender, ['Male', 'Female'])) {
            $_SESSION['message'] = 'Invalid gender.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = 'Invalid email format.';
        } elseif (strlen($password) < 6) {
            $_SESSION['message'] = 'Password must be at least 6 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password)) {
            $_SESSION['message'] = 'Password must contain uppercase, lowercase, and a number.';
        } elseif (AdminModel::emailExists($email)) {
            $_SESSION['message'] = 'Email already registered.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $admin = new Admin($name, $age, $gender, $email, $hashedPassword, $role);
            AdminModel::saveToXML($admin);

            $mailSent = sendConfirmationEmail($email, $name);
            $_SESSION['message'] = $mailSent 
                ? 'Admin registered successfully.' 
                : 'Registered, but failed to send welcome email.';
        }

        header('Location: ../views/admin_register_view.php');
        exit();
    }

    private function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $xml = simplexml_load_file('../../xml-files/admins.xml');

        foreach ($xml->admin as $admin) {
            if ((string)$admin->email === $email && password_verify($password, (string)$admin->password)) {
                $_SESSION['admin'] = (string)$admin->name;
                $_SESSION['email'] = (string)$admin->email;
                $_SESSION['role'] = 'staff';
                header('Location: ../adminIndex.php');
                exit();
            }
        }

        $_SESSION['error'] = 'Invalid admin credentials';
        header('Location: ../views/admin_login_view.php');
        exit();
    }
}

$controller = new AdminController();
$controller->handleRequest();
