<!-- Author     : keekeshen -->

<?php
class User {
    public $name, $age, $gender, $email, $address, $password, $role;

    public function __construct($name, $age, $gender, $email, $address, $password, $role = 'user') {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
        $this->email = $email;
        $this->address = $address;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
    }

    public function saveToXML($xmlFile = '../../xml-files/users.xml') {
        $doc = new DOMDocument();
        $doc->formatOutput = true;

        if (file_exists($xmlFile)) {
            $doc->load($xmlFile);
            $root = $doc->documentElement;
        } else {
            $root = $doc->createElement('users');
            $doc->appendChild($root);
        }

        $user = $doc->createElement('user');
        foreach (['name', 'age', 'gender', 'email', 'address', 'password','role'] as $field) {
            $elem = $doc->createElement($field, $this->$field);
            $user->appendChild($elem);
        }

        $root->appendChild($user);
        $doc->save($xmlFile);
    }
    
    public static function emailExists($email, $xmlFile = '../../xml-files/users.xml') {
    if (!file_exists($xmlFile)) return false;

    $xml = simplexml_load_file($xmlFile);
    foreach ($xml->user as $user) {
        if ((string)$user->email === $email) {
            return true;
        }
    }
    return false;
}

}
?>