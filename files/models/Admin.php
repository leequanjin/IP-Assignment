<!-- Author     : keekeshen -->
<?php
class Admin {
    public $name, $age, $gender, $email, $password, $role;

    public function __construct($name, $age, $gender, $email, $password, $role = 'staff') {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
        $this->email = $email;
        $this->password = $password; 
        $this->role = $role;
    }
}
?>
