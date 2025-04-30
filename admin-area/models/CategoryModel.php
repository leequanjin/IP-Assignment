<?php

class CategoryModel {

    private $xmlFile;

    public function __construct($xmlFile = '../xml-files/categories.xml') {
        $this->xmlFile = $xmlFile;
    }

    public function getAllCategories() {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $categories = $dom->getElementsByTagName('category');

        $result = [];
        foreach ($categories as $category) {
            $title = $category->getElementsByTagName('title')->item(0)->nodeValue;
            $result[] = $title;
        }
        return $result;
    }

    public function insertCategory($title) {
        
    }

    public function deleteCategory($titleToDelete) {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $categories = $dom->getElementsByTagName('category');

        foreach ($categories as $category) {
            $title = $category->getElementsByTagName('title')->item(0)->nodeValue;
            if ($title === $titleToDelete) {
                $category->parentNode->removeChild($category);
                $dom->save($this->xmlFile);
                return true;
            }
        }
        return false;
    }
}
