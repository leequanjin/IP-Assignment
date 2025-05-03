<!-- Author     : Lee Quan Jin -->

<?php

class CategoryModel {

    private $xmlFile;

    public function __construct() {
        $this->xmlFile = '../xml-files/categories.xml';
    }

    public function getAllCategories() {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        return $dom->getElementsByTagName('category');
    }

    public function insertCategory($category_title) {
        $error = '';
        $successMessage = '';

        if (empty($category_title)) {
            $error = "Please enter a category title.";
        } else {
            if (file_exists($this->xmlFile)) {
                $dom = new DOMDocument();
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->load($this->xmlFile);
                $root = $dom->documentElement;
            } else {
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $root = $dom->createElement('categories');
                $dom->appendChild($root);
            }

            $xpath = new DOMXPath($dom);
            $query = "//category[title='$category_title']";
            $entries = $xpath->query($query);

            if ($entries->length > 0) {
                $error = "This category already exists.";
            } else {
                $newCategory = $dom->createElement('category');
                $title = $dom->createElement('title', $category_title);
                $newCategory->appendChild($title);
                $root->appendChild($newCategory);
                $dom->save($this->xmlFile);

                $successMessage = "Category has been inserted successfully!";
            }
        }

        return [$error, $successMessage];
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
