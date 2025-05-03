<!-- Author     : keekeshen -->

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

    if (empty($name) || empty($age) || empty($gender) || empty($email) || empty($address) || empty($password)) {
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

    $emailFound = false;

    foreach ($xml->user as $user) {
        if ((string) $user->email === $email) {
            $emailFound = true;

            // Check password
            if (password_verify($password, (string) $user->password)) {
                $_SESSION['user'] = (string) $user->name;
                $_SESSION['role'] = (string) $user->role;
                $_SESSION['email'] = (string) $user->email;
                header('Location: ../userIndex.php');
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect password.';
                header('Location: ../views/user_login_view.php');
                exit();
            }
        }
    }

    // If email was not found at all
    if (!$emailFound) {
        $_SESSION['error'] = 'Email is not registered.';
        header('Location: ../views/user_login_view.php');
        exit();
    }
} elseif ($action === 'updateProfile') {
    $name = htmlspecialchars(trim($_POST['name']));
    $age = intval($_POST['age']);
    $gender = htmlspecialchars(trim($_POST['gender']));
    $address = htmlspecialchars(trim($_POST['address']));
    $password = $_POST['password'];
    $currentEmail = $_SESSION['email'];

    // Validation
    if (empty($name) || empty($age) || empty($gender)) {
        $_SESSION['error'] = 'All fields (except password) are required.';
    } elseif ($age <= 0) {
        $_SESSION['error'] = 'Age must be a positive number.';
    } elseif ($age >= 100) {
        $_SESSION['error'] = 'Maximum age is 100.';
    } elseif (!in_array($gender, ['Male', 'Female'])) {
        $_SESSION['error'] = 'Invalid gender.';
    } elseif (!empty($password) && strlen($password) < 6) {
        $_SESSION['error'] = 'Password must be at least 6 characters.';
    } elseif (!empty($password) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password)) {
        $_SESSION['error'] = 'Password must contain uppercase, lowercase, and a number.';
    } else {
        $xml = simplexml_load_file('../../xml-files/users.xml');
        foreach ($xml->user as $user) {
            if ((string) $user->email === $currentEmail) {
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
        $_SESSION['user'] = $name;

        sendProfileUpdatedEmail($currentEmail, $name);

        $_SESSION['message'] = 'Profile updated successfully.';
    }

    header('Location: ../views/edit_profile.php');
    exit();
}

?>