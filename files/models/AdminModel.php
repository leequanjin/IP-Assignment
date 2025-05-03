<?php
class AdminModel {
    public $name, $age, $gender, $email, $password, $role;

    public function __construct($name, $age, $gender, $email, $password, $role='staff') {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
    }

    public function saveToXML($xmlFile = '../../xml-files/admins.xml') {
        $doc = new DOMDocument();
        $doc->formatOutput = true;

        if (file_exists($xmlFile)) {
            $doc->load($xmlFile);
            $root = $doc->documentElement;
        } else {
            $root = $doc->createElement('admins');
            $doc->appendChild($root);
        }

        $admin = $doc->createElement('admin');
        foreach (['name', 'age', 'gender', 'email', 'password','role'] as $field) {
            $elem = $doc->createElement($field, $this->$field);
            $admin->appendChild($elem);
        }

        $root->appendChild($admin);
        $doc->save($xmlFile);
    }

    public static function emailExists($email, $xmlFile = '../xml/admins.xml') {
        if (!file_exists($xmlFile)) return false;
        $xml = simplexml_load_file($xmlFile);
        foreach ($xml->admin as $admin) {
            if ((string)$admin->email === $email) return true;
        }
        return false;
    }
}
