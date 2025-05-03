<?php

require_once '../models/UserModel.php';
require_once '../apis/user_mail_service.php';

session_start();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'register') {
    $name = htmlspecialchars(trim($_POST['name']));
    $age = intval($_POST['age']);
    $gender = htmlspecialchars(trim($_POST['gender']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $address = htmlspecialchars(trim($_POST['address']));
    $password = $_POST['password'];
    $role = 'user';

    if (!$email || !$password) {
        $_SESSION['message'] = 'Invalid email or password.';
    } elseif (User::emailExists($email)) {
        $_SESSION['message'] = 'Email already registered.';
    } else {
        $user = new User($name, $age, $gender, $email, $address, $password, $role);
        $user->saveToXML();

        $mailSent = sendConfirmationEmail($email, $name);

        if ($mailSent) {
            $_SESSION['message'] = 'Registration successful.';
        } else {
            $_SESSION['message'] = 'Registered, but failed to send welcome email.';
        }
    }

    header('Location: ../views/user_register_view.php');
    exit();
} elseif ($action === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $xml = simplexml_load_file('../../xml-files/users.xml');

    foreach ($xml->user as $user) {
        foreach ($xml->user as $user) {
            if ($user->email == $email && password_verify($password, $user->password)) {
                $_SESSION['user'] = (string) $user->name;
                $_SESSION['role'] = (string) $user->role;
                $_SESSION['email'] = (string) $user->email;
                header('Location: ../views/user_dashboard.php');
                exit();
            }
        }
        $_SESSION['error'] = 'Invalid credentials';
        header('Location: ../views/user_login_view.php');
        exit();
    }
}
elseif ($action === 'updateProfile') {
    $name = htmlspecialchars(trim($_POST['name']));
    $age = intval($_POST['age']);
    $gender = htmlspecialchars(trim($_POST['gender']));
    $address = htmlspecialchars(trim($_POST['address']));
    $password = $_POST['password'];
    $currentEmail = $_SESSION['email'];

    $xml = simplexml_load_file('../../xml-files/users.xml');
    foreach ($xml->user as $user) {
        if ((string)$user->email === $currentEmail) {
            $user->name = $name;
            $user->age = $age;
            $user->gender = $gender;
            $user->address = $address;
            if (!empty($password)) {
                $user->password = password_hash($password, PASSWORD_DEFAULT);
            }
            break;
        }
    }

    $xml->asXML('../../xml-files/users.xml');
    require_once '../apis/user_mail_service.php';
    sendProfileUpdatedEmail($currentEmail, $name);

    $_SESSION['message'] = 'Profile updated successfully.';
    header('Location: ../views/edit_profile.php');
    exit();
}



?>