<!-- Author     : keekeshen -->
<?php

require_once 'User.php';

class UserModel {

    public static function saveToXML(User $user, $xmlFile = '../../xml-files/users.xml') {
        $doc = new DOMDocument();
        $doc->formatOutput = true;

        if (file_exists($xmlFile)) {
            $doc->load($xmlFile);
            $root = $doc->documentElement;
        } else {
            $root = $doc->createElement('users');
            $doc->appendChild($root);
        }

        $userNode = $doc->createElement('user');

        foreach (['name', 'age', 'gender', 'email', 'address', 'password', 'role'] as $field) {
            $value = $user->$field;  
            $elem = $doc->createElement($field, $value);
            $userNode->appendChild($elem);
        }

        $root->appendChild($userNode);
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
