<!-- Author     : keekeshen -->
<?php

require_once '../models/UserModel.php';
require_once '../apis/user_mail_service.php';

session_start();

class UserController
{
    public function handleRequest()
    {
        $action = $_POST['action'] ?? $_GET['action'] ?? '';

        switch ($action) {
            case 'register':
                $this->register();
                break;
            case 'verifyRegisterCode':
                $this->verifyRegisterCode();
                break;
            case 'login':
                $this->login();
                break;
            case 'updateProfile':
                $this->updateProfile();
                break;
            case 'sendResetCode':
                $this->sendResetCode();
                break;
            case 'verifyCode':
                $this->verifyResetCode();
                break;
            case 'resetPassword':
                $this->resetPassword();
                break;
            default:
                echo "Invalid action.";
        }
    }

    private function register()
    {
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
        } elseif (strlen($password) < 6 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password)) {
            $_SESSION['message'] = 'Password must contain uppercase, lowercase, and a number.';
        } elseif (User::emailExists($email)) {
            $_SESSION['message'] = 'Email already registered.';
        } else {
            $code = rand(100000, 999999);
            $_SESSION['register_code'] = $code;
            $_SESSION['register_data'] = compact('name', 'age', 'gender', 'email', 'address', 'password', 'role');

            sendVerificationCodeEmail2($email, $name, $code);

            header('Location: ../views/verify_register_code_view.php');
            exit();
        }

        header('Location: ../views/user_register_view.php');
        exit();
    }

    private function verifyRegisterCode()
    {
        $enteredCode = $_POST['code'];
        if ($_SESSION['register_code'] == $enteredCode) {
            $data = $_SESSION['register_data'];
            $user = new User(
                $data['name'],
                $data['age'],
                $data['gender'],
                $data['email'],
                $data['address'],
                $data['password'],
                $data['role']
            );
            $user->saveToXML();
            sendConfirmationEmail($data['email'], $data['name']);

            unset($_SESSION['register_code'], $_SESSION['register_data']);
            $_SESSION['message'] = 'Registration successful.';
            header('Location: ../views/user_register_view.php');
            exit();
        } else {
            $_SESSION['error'] = 'Incorrect verification code.';
            header('Location: ../views/verify_register_code_view.php');
            exit();
        }
    }

    private function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $xml = simplexml_load_file('../../xml-files/users.xml');

        foreach ($xml->user as $user) {
            if ((string) $user->email === $email) {
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

        $_SESSION['error'] = 'Email is not registered.';
        header('Location: ../views/user_login_view.php');
        exit();
    }

    private function updateProfile()
    {
        $name = htmlspecialchars(trim($_POST['name']));
        $age = intval($_POST['age']);
        $gender = htmlspecialchars(trim($_POST['gender']));
        $address = htmlspecialchars(trim($_POST['address']));
        $password = $_POST['password'];
        $currentEmail = $_SESSION['email'];

        if (empty($name) || empty($age) || empty($gender)) {
            $_SESSION['error'] = 'All fields (except password) are required.';
        } elseif ($age <= 0) {
            $_SESSION['error'] = 'Age must be a positive number.';
        } elseif ($age >= 100) {
            $_SESSION['error'] = 'Maximum age is 100.';
        } elseif (!in_array($gender, ['Male', 'Female'])) {
            $_SESSION['error'] = 'Invalid gender.';
        } elseif (!empty($password) && (strlen($password) < 6 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password))) {
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

    private function sendResetCode()
    {
        $email = $_POST['email'];
        $xml = simplexml_load_file('../../xml-files/users.xml');

        foreach ($xml->user as $user) {
            if ((string) $user->email === $email) {
                $name = (string) $user->name;
                $code = rand(100000, 999999);
                $_SESSION['reset_code'] = $code;
                $_SESSION['reset_email'] = $email;

                sendVerificationCodeEmail($email, $name, $code);
                header('Location: ../views/verify_code_view.php');
                exit();
            }
        }

        $_SESSION['error'] = 'Email not found.';
        header('Location: ../views/forgot_password_view.php');
        exit();
    }

    private function verifyResetCode()
    {
        $enteredCode = $_POST['code'];
        if ($_SESSION['reset_code'] == $enteredCode) {
            $_SESSION['verified'] = true;
            header('Location: ../views/set_new_password_view.php');
        } else {
            $_SESSION['error'] = 'Incorrect code.';
            header('Location: ../views/verify_code_view.php');
        }
        exit();
    }

    private function resetPassword()
    {
        if (!$_SESSION['verified']) {
            header('Location: ../views/forgot_password_view.php');
            exit();
        }

        $newPassword = $_POST['password'];

        if (strlen($newPassword) < 6 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $newPassword)) {
            $_SESSION['error'] = 'Password must contain uppercase, lowercase, and a number.';
            header('Location: ../views/set_new_password_view.php');
            exit();
        }

        $email = $_SESSION['reset_email'];
        $xml = simplexml_load_file('../../xml-files/users.xml');

        foreach ($xml->user as $user) {
            if ((string) $user->email === $email) {
                $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
                break;
            }
        }

        $xml->asXML('../../xml-files/users.xml');
        session_destroy();
        header('Location: ../views/user_login_view.php');
        exit();
    }
}

$controller = new UserController();
$controller->handleRequest();
