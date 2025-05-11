<!-- Author     : keekeshen -->

<?php
class User{
    public $name, $age, $gender, $email, $address, $password, $role;

    public function __construct($name, $age, $gender, $email, $address, $password, $role = 'user') {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
        $this->email = $email;
        $this->address = $address;
        $this->password = $password; 
        $this->role = $role;
    }
}
?>
