<!-- Author     : keekeshen -->
<?php

require_once 'Admin.php';

class AdminModel {
    public static function saveToXML(Admin $admin, $xmlFile = '../../xml-files/admins.xml') {
        $doc = new DOMDocument();
        $doc->formatOutput = true;

        if (file_exists($xmlFile)) {
            $doc->load($xmlFile);
            $root = $doc->documentElement;
        } else {
            $root = $doc->createElement('admins');
            $doc->appendChild($root);
        }

        $adminNode = $doc->createElement('admin');
        foreach (['name', 'age', 'gender', 'email', 'password', 'role'] as $field) {
            $elem = $doc->createElement($field, $admin->$field);
            $adminNode->appendChild($elem);
        }

        $root->appendChild($adminNode);
        $doc->save($xmlFile);
    }

    public static function emailExists($email, $xmlFile = '../../xml-files/admins.xml') {
        if (!file_exists($xmlFile)) return false;
        $xml = simplexml_load_file($xmlFile);
        foreach ($xml->admin as $admin) {
            if ((string)$admin->email === $email) return true;
        }
        return false;
    }
}
?>
