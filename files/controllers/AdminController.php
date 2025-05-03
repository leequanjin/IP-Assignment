<?php

require_once '../models/AdminModel.php';
require_once '../apis/admin_mail_service.php';

session_start();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'register') {
    $name = htmlspecialchars(trim($_POST['name']));
    $age = intval($_POST['age']);
    $gender = htmlspecialchars(trim($_POST['gender']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $role = 'staff';

    if (!$email || !$password) {
        $_SESSION['message'] = 'Invalid email or password.';
    } elseif (AdminModel::emailExists($email)) {
        $_SESSION['message'] = 'Email already registered.';
    } else {
        $admin = new AdminModel($name, $age, $gender, $email, $password, $role);
        $admin->saveToXML();

        $mailSent = sendConfirmationEmail($email, $name);

        if ($mailSent) {
            $_SESSION['message'] = 'Admin registered successfully.';
        } else {
            $_SESSION['message'] = 'Registered, but failed to send welcome email.';
        }
    }

    header('Location: ../views/admin_register_view.php');
    exit();
} elseif ($action === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $xml = simplexml_load_file('../../xml-files/admins.xml');
    foreach ($xml->admin as $admin) {
        if ((string) $admin->email === $email && password_verify($password, (string) $admin->password)) {
            $_SESSION['admin'] = (string) $admin->name;
            $_SESSION['email'] = (string) $admin->email;
            $_SESSION['role'] = 'staff'; // IMPORTANT
            header('Location: ../views/admin_dashboard.php');
            exit();
        }
    }

    $_SESSION['error'] = 'Invalid admin credentials';
    header('Location: ../views/admin_login_view.php');
    exit();
}
